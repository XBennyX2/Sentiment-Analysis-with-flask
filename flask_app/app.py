from flask import Flask, request, jsonify
import joblib

app = Flask(__name__)


# Load the ML model and vectorizer
model = joblib.load('model/sentiment_model.joblib')
vectorizer = joblib.load('model/vectorizer.joblib')

@app.route('/predict', methods=['POST'])
def predict():
    data = request.get_json()
    text = data.get('text')
    if text:
        text_vectorized = vectorizer.transform([text])
        prediction = model.predict(text_vectorized)[0]
        sentiment = 'positive' if prediction == 1 else 'negative'
        return jsonify({'sentiment': sentiment})
    else:
        return jsonify({'error': 'No text provided'}), 400

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
