import pandas as pd
import re
import nltk
from nltk.corpus import stopwords
from nltk.tokenize import word_tokenize

nltk.download("punkt")
nltk.download("stopwords")

# 🔹 1️⃣ Baca file CSV tweet
raw_df = pd.read_csv("tweets.csv")  # Harus punya kolom: text
positive_df = pd.read_csv("positive.tsv", sep="\t")
negative_df = pd.read_csv("negative.tsv", sep="\t")

# 🔹 2️⃣ Gabungkan lexicon positif & negatif
lexicon_df = pd.concat([positive_df, negative_df], ignore_index=True)
lexicon_df = lexicon_df.drop_duplicates(subset="word", keep="last")

# 🔹 3️⃣ Preprocessing
stop_words = set(stopwords.words("indonesian"))

def preprocess_text(text):
    text = str(text).lower()
    text = re.sub(r"http\S+|www\S+|https\S+", "", text)
    text = re.sub(r"@\w+", "", text)
    text = re.sub(r"#\w+", "", text)
    text = re.sub(r"[^\w\s]", "", text)
    text = re.sub(r"\d+", "", text)
    words = word_tokenize(text)
    words = [word for word in words if word not in stop_words]
    return " ".join(words)

raw_df["clean_text"] = raw_df["text"].apply(preprocess_text)

# 🔹 4️⃣ Labeling berdasarkan lexicon
lexicon_dict = dict(zip(lexicon_df["word"], lexicon_df["weight"]))

def get_sentiment(text):
    words = text.split()
    score = sum([lexicon_dict.get(word, 0) for word in words])
    return "positif" if score > 0 else "negatif" if score < 0 else "netral"

raw_df["label"] = raw_df["clean_text"].apply(get_sentiment)

# 🔹 5️⃣ Simpan hasil labeling ke CSV
raw_df.to_csv("labeled_tweets.csv", index=False)
print("✅ Labeling selesai! File disimpan sebagai 'labeled_tweets.csv'")
