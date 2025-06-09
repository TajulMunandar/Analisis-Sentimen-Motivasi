import subprocess
import pandas as pd
import requests
from flask import Flask, jsonify, request
import os
import time
from flask_cors import CORS
import re
import nltk
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.svm import SVC
from sklearn.model_selection import train_test_split
from sklearn.pipeline import make_pipeline
from sklearn.preprocessing import LabelEncoder
from sklearn.metrics import confusion_matrix, accuracy_score, f1_score

app = Flask(__name__)

CORS(app)

# 🔹 Konfigurasi
BASE_DIR = ""
TWEETS_DIR = os.path.join(BASE_DIR)  # Direktori penyimpanan file
FILENAME = os.path.join(TWEETS_DIR, "motivasi.csv")  # Path lengkap file CSV
SEARCH_KEYWORD = "#kata-katahariini lang:id"
LIMIT = 100
TWITTER_AUTH_TOKEN = "ae46e8064e6634509e8940450b57e1966cd25f18"
LARAVEL_API_URL = "http://127.0.0.1:8000/api/scrape"  # Sesuaikan dengan API Laravel
NPX_PATH = r"C:\Users\tajul\AppData\Roaming\npm\npx.cmd"


nltk.download("punkt")
nltk.download("stopwords")
DATAPREPROCESS_API_URL = "http://127.0.0.1:8000/api/process"
LARAVEL_PREPROCESS_API = "http://127.0.0.1:8000/api/preprocess"

PREPROCESS_DATA_API = "http://127.0.0.1:8000/api/preprocessed-tweets"
LEXICON_API = "http://127.0.0.1:8000/api/lexicons"
SAVE_SENTIMENT_API = "http://127.0.0.1:8000/api/save-sentiment"


def fetch_preprocessed_data():
    """Ambil data yang sudah di-preprocessing dari API Laravel"""
    response = requests.get(PREPROCESS_DATA_API)
    response.raise_for_status()
    return response.json()


def fetch_lexicon():
    """Ambil data lexicon dari API Laravel"""
    response = requests.get(LEXICON_API)
    response.raise_for_status()
    return response.json()


def analyze_sentiment_svm():
    try:
        # 🔹 1️⃣ Ambil data yang sudah di-preprocessing
        preprocessed_data = fetch_preprocessed_data()
        lexicon_data = fetch_lexicon()
        print("Preprocessed Data:", preprocessed_data[:5])
        print("Lexicon Data:", lexicon_data[:5])

        # 🔹 2️⃣ Konversi data ke DataFrame
        df = pd.DataFrame(preprocessed_data)
        lexicon_df = pd.DataFrame(lexicon_data)

        if df.empty or lexicon_df.empty:
            return jsonify({"error": "Data preprocessing atau lexicon kosong!"}), 400

        # 🔹 3️⃣ Labelkan data berdasarkan lexicon
        lexicon_dict = dict(zip(lexicon_df["word"], lexicon_df["polarity"]))

        def get_sentiment(text):
            words = text.split()
            sentiment_score = sum([lexicon_dict.get(word, 0) for word in words])
            return (
                "positif"
                if sentiment_score > 0
                else "negatif" if sentiment_score < 0 else "netral"
            )

        df["sentiment"] = df["clean_text"].apply(get_sentiment)

        # 🔹 4️⃣ Ekstraksi fitur dengan TF-IDF
        vectorizer = TfidfVectorizer()
        X = vectorizer.fit_transform(df["clean_text"])
        y = df["sentiment"]

        # 🔹 5️⃣ Encode label (positif = 1, netral = 0, negatif = -1)
        label_encoder = LabelEncoder()
        y_encoded = label_encoder.fit_transform(y)

        # 🔹 6️⃣ Split data untuk training dan testing
        X_train, X_test, y_train, y_test = train_test_split(
            X, y_encoded, test_size=0.2, random_state=42
        )

        if X_train.shape[0] == 0 or X_test.shape[0] == 0:
            return (
                jsonify({"error": "Dataset terlalu kecil untuk training/testing!"}),
                500,
            )

        # 🔹 7️⃣ Train model SVM
        svm_model = make_pipeline(SVC(kernel="linear", probability=True))
        svm_model.fit(X_train, y_train)

        y_pred = svm_model.predict(X_test)
        conf_matrix = confusion_matrix(y_test, y_pred)
        accuracy = accuracy_score(y_test, y_pred)
        f1 = f1_score(y_test, y_pred, average="weighted")

        print("Confusion Matrix:\n", conf_matrix)
        print("Accuracy:", accuracy)
        print("F1 Score:", f1)

        # 🔹 8️⃣ Prediksi sentimen untuk data baru
        df["predicted_sentiment"] = label_encoder.inverse_transform(
            svm_model.predict(X)
        )

        decision_values = svm_model.decision_function(X)
        if len(decision_values.shape) > 1:
            df["confidence"] = decision_values.max(axis=1)
        else:
            df["confidence"] = decision_values

        if "tweet_id" not in df.columns:
            return (
                jsonify({"error": "Kolom 'tweet_id' tidak ditemukan dalam dataset!"}),
                500,
            )

        results = df[["tweet_id", "predicted_sentiment", "confidence"]].to_dict(
            orient="records"
        )

        # 🔹 9️⃣ Simpan hasil prediksi ke database
        if results:
            save_response = requests.post(
                SAVE_SENTIMENT_API, json={"sentiments": results}
            )
            save_response.raise_for_status()

        return jsonify(
            {
                "message": "Sentiment analysis completed!",
                "data": results,
                "evaluation": {
                    "confusion_matrix": conf_matrix.tolist(),
                    "accuracy": accuracy,
                    "f1_score": f1,
                },
            }
        )

    except Exception as e:
        return jsonify({"error": str(e)}), 500


@app.route("/analyze-sentiment", methods=["GET"])
def analyze_sentiment():
    return analyze_sentiment_svm()


def preprocess_text(text):
    text = text.lower()  # Ubah ke huruf kecil
    text = re.sub(r"http\S+|www\S+|https\S+", "", text, flags=re.MULTILINE)  # Hapus URL
    text = re.sub(r"@\w+", "", text)  # Hapus mention
    text = re.sub(r"#\w+", "", text)  # Hapus hashtag
    text = re.sub(r"[^\w\s]", "", text)  # Hapus karakter spesial
    text = re.sub(r"\d+", "", text)  # Hapus angka
    words = word_tokenize(text)  # Tokenisasi
    stop_words = set(stopwords.words("indonesian"))  # Stopwords Bahasa Indonesia
    words = [word for word in words if word not in stop_words]  # Hapus stopwords
    return " ".join(words)


@app.route("/preprocess", methods=["GET"])
def preprocess_tweets():
    try:
        # 🔹 Ambil data tweet dari API Laravel
        response = requests.get(DATAPREPROCESS_API_URL)
        response.raise_for_status()
        tweets = response.json()

        # 🔹 Preprocessing
        processed_tweets = []
        for tweet in tweets:
            # Lewati jika tidak ada teks
            raw_text = tweet.get("text")
            if not raw_text or not raw_text.strip():
                continue

            clean_text = preprocess_text(raw_text)
            if not clean_text or not clean_text.strip():
                continue  # Lewati jika hasil preprocessing kosong

            tokenized = word_tokenize(clean_text)
            feature_vector = " ".join(tokenized)

            processed_tweets.append(
                {
                    "tweet_id": tweet["id"],
                    "clean_text": clean_text,
                    "tokenized": tokenized,
                    "feature_vector": feature_vector,
                }
            )

        # Kirim ke Laravel jika ada hasil
        if processed_tweets:
            save_response = requests.post(
                LARAVEL_PREPROCESS_API, json={"preprocessed_tweets": processed_tweets}
            )
            save_response.raise_for_status()

        return jsonify({"preprocessed_tweets": processed_tweets})

    except Exception as e:
        return jsonify({"error": str(e)}), 500


@app.route("/scrapping", methods=["post"])
def scrapping():
    try:
        data = request.get_json()
        if not data or "keyword" not in data:
            return jsonify({"error": "Keyword is required"}), 400

        search_keyword = data.get("keyword")

        # 🔹 1️⃣ Scraping data menggunakan tweet-harvest
        command = [
            NPX_PATH,
            "tweet-harvest@2.6.1",
            "-o",
            FILENAME.replace("\\", "/"),  # Pastikan format path benar
            "-s",
            search_keyword,
            "--tab",
            "LATEST",
            "-l",
            str(LIMIT),
            "--token",
            TWITTER_AUTH_TOKEN,
        ]
        subprocess.run(command, shell=True, executable="C:\\Windows\\System32\\cmd.exe")

        # 🔹 2️⃣ Tunggu beberapa detik agar file CSV selesai dibuat
        time.sleep(3)

        # 🔹 3️⃣ Cek apakah file CSV ada sebelum membacanya
        if not os.path.exists(
            "C:\\laragon\\www\\Analisis-Sentimen-Sosmed\\flask\\tweets-data\\"
            + FILENAME
        ):
            print(
                "❌ File tidak ditemukan! Cek apakah tweet-harvest berhasil menyimpan CSV.",
                FILENAME,
            )
            return jsonify({"error": "CSV file not found"}), 500
        else:
            print("✅ File ditemukan, melanjutkan membaca CSV...")

        # 🔹 4️⃣ Baca CSV yang dihasilkan
        df = pd.read_csv(
            "C:\\laragon\\www\\Analisis-Sentimen-Sosmed\\flask\\tweets-data\\"
            + FILENAME
        )

        df = df.fillna("")

        # 🔹 5️⃣ Persiapkan data untuk dikirim ke database
        tweets = []
        for _, row in df.iterrows():

            tweet = {
                "tweet_id": (
                    str(row.get("id_str", ""))
                    if pd.notna(row.get("id_str", ""))
                    else ""
                ),
                "user_id": (
                    str(row.get("user_id_str", ""))
                    if pd.notna(row.get("user_id_str", ""))
                    else ""
                ),
                "username": (
                    row.get("username", "") if pd.notna(row.get("username", "")) else ""
                ),
                "text": (
                    row.get("full_text", "")
                    if pd.notna(row.get("full_text", ""))
                    else ""
                ),
                "sentimen": None,
            }
            tweets.append(tweet)

        # 🔹 6️⃣ Kirim data ke API Laravel
        response = requests.post(LARAVEL_API_URL, json={"tweets": tweets})
        response.raise_for_status()

        return (
            jsonify({"message": "Scraping completed", "response": response.json()}),
            response.status_code,
        )

    except Exception as e:
        print("❌ Error mengirim ke Laravel:", e)
        return jsonify({"error": "Gagal mengirim ke Laravel", "details": str(e)}), 500


if __name__ == "__main__":
    app.run(debug=True)
