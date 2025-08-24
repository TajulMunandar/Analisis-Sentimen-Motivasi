import pandas as pd
import re
import nltk
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize

# 🔹 Download resource NLTK
nltk.download("punkt")
nltk.download("stopwords")

print("🚀 Mulai proses analisis sentimen...")

# 🔹 1️⃣ Baca file CSV tweet
raw_df = pd.read_csv("tweets.csv")  # Harus punya kolom: text
print("📂 Data tweet dibaca. Jumlah baris:", len(raw_df))
print(raw_df.head())

positive_df = pd.read_csv("positive.tsv", sep="\t")
negative_df = pd.read_csv("negative.tsv", sep="\t")
print("\n✅ Lexicon positif & negatif dibaca")
print("Positif:", len(positive_df), "Negatif:", len(negative_df))

# 🔹 2️⃣ Gabungkan lexicon
lexicon_df = pd.concat([positive_df, negative_df], ignore_index=True)
lexicon_df = lexicon_df.drop_duplicates(subset="word", keep="last")
print("\n📌 Total lexicon setelah digabung:", len(lexicon_df))

# 🔹 3️⃣ Preprocessing
stop_words = set(stopwords.words("indonesian"))


def extract_numbers(text):
    """Ambil semua angka dari teks"""
    return re.findall(r"\d+", str(text))


def preprocess_text(text):
    text = str(text).lower()
    text = re.sub(r"http\S+|www\S+|https\S+", "", text)
    text = re.sub(r"@\w+", "", text)
    text = re.sub(r"#\w+", "", text)
    text = re.sub(r"[^\w\s]", "", text)  # hapus simbol
    # ❌ jangan hapus angka di sini
    words = word_tokenize(text)
    words = [word for word in words if word not in stop_words]
    return " ".join(words)


raw_df["numbers"] = raw_df["text"].apply(extract_numbers)
raw_df["clean_text"] = raw_df["text"].apply(preprocess_text)

print("\n🧹 Hasil preprocessing:")
print(raw_df[["text", "numbers", "clean_text"]].head())

# 🔹 4️⃣ Labeling berdasarkan lexicon
lexicon_dict = dict(zip(lexicon_df["word"], lexicon_df["weight"]))


def get_sentiment_and_score(text):
    words = text.split()
    score = sum([lexicon_dict.get(word, 0) for word in words])
    if score > 0:
        label = "positif"
    elif score < 0:
        label = "negatif"
    else:
        label = "netral"
    return label, score


raw_df[["label", "label_score"]] = raw_df["clean_text"].apply(
    lambda x: pd.Series(get_sentiment_and_score(x))
)

print("\n🏷️ Hasil labeling:")
print(raw_df[["clean_text", "label", "label_score"]].head())

# 🔹 5️⃣ Simpan hasil labeling ke CSV
raw_df.to_csv("labeled_tweets.csv", index=False)
print("\n✅ Labeling selesai! File disimpan sebagai 'labeled_tweets.csv'")

# 🔹 Cetak semua angka unik
all_numbers = raw_df["numbers"].explode().dropna().unique().tolist()
print("\n🔢 Semua angka unik yang ditemukan:", all_numbers)
