import pandas as pd
import nltk
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.svm import SVC
from sklearn.pipeline import make_pipeline
from sklearn.model_selection import train_test_split
from sklearn.metrics import confusion_matrix, accuracy_score, f1_score
from sklearn.preprocessing import LabelEncoder

nltk.download("punkt")

# 🔹 1️⃣ Baca file hasil labeling
df = pd.read_csv("labeled_tweets.csv")  # Harus punya kolom clean_text dan label

# 🔹 2️⃣ Ekstraksi fitur dan encoding label
vectorizer = TfidfVectorizer()
X = vectorizer.fit_transform(df["clean_text"])
y = df["label"]
label_encoder = LabelEncoder()
y_encoded = label_encoder.fit_transform(y)

# 🔹 3️⃣ Split & training
X_train, X_test, y_train, y_test = train_test_split(
    X, y_encoded, test_size=0.2, random_state=42
)

svm_model = make_pipeline(SVC(kernel="linear", probability=True))
svm_model.fit(X_train, y_train)

# 🔹 4️⃣ Evaluasi
y_pred = svm_model.predict(X_test)
conf_matrix = confusion_matrix(y_test, y_pred)
accuracy = accuracy_score(y_test, y_pred)
f1 = f1_score(y_test, y_pred, average="weighted")

print("Confusion Matrix:\n", conf_matrix)
print("Accuracy:", accuracy)
print("F1 Score:", f1)

# 🔹 5️⃣ Prediksi ulang seluruh data dan simpan
df["predicted"] = label_encoder.inverse_transform(svm_model.predict(X))

decision_values = svm_model.decision_function(X)
if len(decision_values.shape) > 1:
    df["confidence"] = decision_values.max(axis=1)
else:
    df["confidence"] = decision_values

df.to_csv("sentiment_results.csv", index=False)
print("\n✅ Klasifikasi selesai! Hasil disimpan di 'sentiment_results.csv'")
