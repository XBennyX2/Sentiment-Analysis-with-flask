# flask_app/Dockerfile

FROM python:3.8-slim

WORKDIR /app

# Copy all app files to container
COPY . .

# Install dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Expose the Flask port
EXPOSE 5000

# Run the Flask application
CMD ["python", "app.py"]
