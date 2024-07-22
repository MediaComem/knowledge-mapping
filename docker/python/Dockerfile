FROM python:3.8

WORKDIR /var/www

# Packages
COPY ./docker/python/requirements.txt ./requirements.txt
RUN pip install --no-cache-dir -r requirements.txt
RUN python -m spacy download en_core_web_sm
# Download Python NLP libs dependencies
RUN python -m nltk.downloader stopwords

# Copy analysis python file and generated database
COPY ../../data /var/www/data
COPY ../../python /var/www/python

# Default ENTRYPOINT: python3
CMD ["gunicorn","--config", "./python/gunicorn_config.py", "python.server:app"]