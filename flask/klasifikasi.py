import pandas as pd
import nltk
import numpy as np
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.svm import SVC
from sklearn.pipeline import make_pipeline
from sklearn.model_selection import train_test_split
from sklearn.metrics import confusion_matrix, accuracy_score, f1_score
from sklearn.preprocessing import LabelEncoder

nltk.download("punkt")

print("🚀 Mulai proses klasifikasi sentimen...\n")

# 🔹 1️⃣ Baca file hasil labeling
df = pd.read_csv("labeled_tweets.csv")  # Harus punya kolom clean_text dan label
print("📂 Data berhasil dibaca. Jumlah baris:", len(df))
print(df.head(), "\n")
df["clean_text"] = df["clean_text"].fillna("")

# 🔹 2️⃣ Ekstraksi fitur dan encoding label
vectorizer = TfidfVectorizer()
X = vectorizer.fit_transform(df["clean_text"])
y = df["label"]

label_encoder = LabelEncoder()
y_encoded = label_encoder.fit_transform(y)

print("📝 Label unik:", label_encoder.classes_)
print("Contoh label encoded:", y_encoded[:10], "\n")

# 🔹 Print hasil TF-IDF
feature_names = vectorizer.get_feature_names_out()
tfidf_matrix = X.toarray()

print("🔢 Jumlah fitur (kata unik):", len(feature_names))
print(
    "📌 Daftar kata (fitur):", feature_names[:50], "..."
)  # tampilkan sebagian biar gak kepanjangan

print("\n📊 Contoh matriks TF-IDF (baris = dokumen, kolom = kata):")
print(
    pd.DataFrame(tfidf_matrix[:5, :50], columns=feature_names[:50])
)  # tampilkan sebagian

# 🔹 3️⃣ Split & training
X_train, X_test, y_train, y_test = train_test_split(
    X, y_encoded, test_size=0.2, random_state=42
)

print("\n📂 Jumlah data train:", len(y_train), "Jumlah data test:", len(y_test))

svm_model = make_pipeline(SVC(kernel="linear", probability=True))
svm_model.fit(X_train, y_train)
print("✅ Model SVM selesai dilatih!\n")

# 🔹 4️⃣ Evaluasi
y_pred = svm_model.predict(X_test)
conf_matrix = confusion_matrix(y_test, y_pred)
accuracy = accuracy_score(y_test, y_pred)
f1 = f1_score(y_test, y_pred, average="weighted")

print("📌 Confusion Matrix:\n", conf_matrix)
print("📊 Accuracy:", accuracy)
print("📊 F1 Score:", f1, "\n")

# 🔹 5️⃣ Prediksi ulang seluruh data dan simpan
df["predicted"] = label_encoder.inverse_transform(svm_model.predict(X))

decision_values = svm_model.decision_function(X)
if len(decision_values.shape) > 1:
    df["confidence"] = decision_values.max(axis=1)
else:
    df["confidence"] = decision_values

print("📌 Contoh hasil prediksi:")
print(df[["clean_text", "label", "predicted", "confidence"]].head(), "\n")

tfidf_docs = []
for row in X:  # X masih sparse
    row_data = row.toarray().flatten()
    nonzero_idx = np.where(row_data > 0)[0]
    top_terms = {feature_names[i]: row_data[i] for i in nonzero_idx}
    tfidf_docs.append(top_terms)

df_tfidf_summary = pd.DataFrame(tfidf_docs)
df_tfidf_summary.to_csv("sentiment_results_tfidf_summary.csv", index=False)

df.to_csv("sentiment_results.csv", index=False)
print("✅ Klasifikasi selesai! Hasil disimpan di 'sentiment_results.csv'")
