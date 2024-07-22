# -*- coding: utf-8 -*-
"""
AYA
see https://nbviewer.jupyter.org/github/rare-technologies/gensim/blob/develop/docs/notebooks/atmodel_tutorial.ipynb
"""
import string
import copy
import csv
import pickle
from bokeh.io import output_notebook
from sklearn.manifold import TSNE
import seaborn as sns
import pandas as pd
import pyLDAvis.gensim_models as gensimvis
import pyLDAvis
from gensim import corpora, models
import numpy as np
from collections import OrderedDict
import warnings
import matplotlib.pyplot as plt
from gensim.models import AuthorTopicModel, LdaMulticore
from gensim.corpora import Dictionary, MmCorpus
from gensim.models.phrases import Phrases
from nltk.corpus import stopwords
import spacy
__author__ = """Giovanni Colavizza"""
# TODO: there are duplicates of documents now, if a document is in multiple collections. This needs to be solved.

import sys
import codecs
import json
import pickle
import re
import multiprocessing
import argparse
from pyzotero import zotero
from collections import defaultdict, OrderedDict
from supporting_functions import compare_authors
import sqlite3
from os.path import abspath, dirname, join

parser = argparse.ArgumentParser()

# add arguments to the parser
parser.add_argument(
    "--id", help="Zotero Library ID (digits)", default=argparse.SUPPRESS)
parser.add_argument(
    "--key", help="Zotero Api Key", default=argparse.SUPPRESS)

# parse the arguments
args = parser.parse_args()

if 'id' in args:
    LIBRARY_ID = args.id
else:
    sys.exit("Library id not specified")

if 'key' in args:
    API_KEY = args.key
else:
    sys.exit("Api Key not specified")

# DATABASE CONNECTION
conn = sqlite3.connect(abspath(join(dirname(__file__), '../data/aya_years.db')))

cursor = conn.cursor()
cursor.execute(
    "UPDATE synchronizations SET status='Retrieving data' WHERE id=(SELECT MAX(id) FROM synchronizations)")
conn.commit()
# Load Data
zot = zotero.Zotero(LIBRARY_ID, 'group', API_KEY)


items = zot.everything(zot.items())

all_types = defaultdict(int)
abstracts_count = defaultdict(int)
with codecs.open("abstracts.txt", "w", encoding="utf-8") as f:
    for i in items:
        all_types[i["data"]["itemType"]] += 1
        if not 'title' in i['data'].keys():
            continue
        i["data"]["text"] = i["data"]["title"]
        i["data"]["has_abstract"] = False
        if "abstractNote" in i["data"].keys() and len(i["data"]["abstractNote"]) > 0 and not i["data"]["abstractNote"].startswith('ResearchGate'):
            abstracts_count[i["data"]["itemType"]] += 1
            f.write(i["data"]["abstractNote"])
            f.write("\n------------\n")
            i["data"]["text"] = i["data"]["title"] + \
                " " + i["data"]["abstractNote"]
            i["data"]["has_abstract"] = True
all_types = sorted(all_types.items(), key=lambda x: x[1], reverse=True)
abstracts_count = sorted(abstracts_count.items(),
                         key=lambda x: x[1], reverse=True)
print("Loaded item data")

# author disambiguation
author_dict = dict()
unique_author_dict = dict()
current_key = 0
for i in items:
    if not 'title' in i['data'].keys() or not 'creators' in i['data'].keys():
        continue
    for c in i['data']['creators']:
        if not "lastName" in c.keys():
            continue
        fn = ""
        ln = c["lastName"]
        if "firstName" in c.keys():
            fn = c["firstName"]
        key = (fn, ln)
        local_key = -1
        for a, k in author_dict.items():
            if compare_authors(key, a):
                local_key = k
                break
        if local_key < 0:
            unique_author_dict[key] = current_key
            author_dict[key] = current_key
            current_key += 1
        else:
            # if there is a match, but the surface is different, add entry, if not, overwrite same entry
            author_dict[key] = local_key
auth_rev_index = {k: a for a, k in unique_author_dict.items()}
# dump authors
cursor.execute('''DROP TABLE IF EXISTS authors''')
cursor.execute('''CREATE TABLE authors
			(id integer primary key, name text, surname text, library_id text)''')
authors = [(k, a[0], a[1], LIBRARY_ID) for a, k in unique_author_dict.items()]
cursor.executemany('INSERT INTO authors VALUES (?,?,?,?)', authors)
# Save (commit) the changes
cursor.execute("UPDATE synchronizations SET status='%d authors retrieved' WHERE id=(SELECT MAX(id) FROM synchronizations)" %
               len(unique_author_dict), )
conn.commit()
print("Dumped author data, %d" % len(unique_author_dict))

# dump raw article info into a table
cursor.execute('''DROP TABLE IF EXISTS items''')
cursor.execute('''CREATE TABLE items
			(id integer primary key, zot_key text, title text, text text, has_abstract integer, abstract text, year integer, date text, language text,
				pages text, volume integer, publication text, place text, url text, itemType text,
				DOI text, ISBN text, conferenceName text, proceedingsTitle text, zotero_url text, library_id text)''')
items_dict = dict()
rev_items_dict = dict()
current_key = 0
for i in items:
    if not 'title' in i['data'].keys():
        continue
    abstract = ""
    if "abstractNote" in i["data"].keys():
        abstract = i['data']['abstractNote']
    date = ""
    if "date" in i["data"].keys():
        date = i['data']['date']
    year = None
    if date:
        res = re.findall(r"\d{4}", date)
        if len(res) > 0:
            year = int(res[0])
    language = ""
    if "language" in i["data"].keys():
        language = i['data']['language']
    pages = ""
    if "pages" in i["data"].keys():
        pages = i['data']['pages']
    volume = ""
    if "volume" in i["data"].keys():
        volume = i['data']['volume']
    publication = ""
    if "publication" in i["data"].keys():
        publication = i['data']['publication']
    place = ""
    if "place" in i["data"].keys():
        place = i['data']['place']
    url = ""
    if "url" in i["data"].keys():
        url = i['data']['url']
    itemType = ""
    if "itemType" in i["data"].keys():
        itemType = i['data']['itemType']
    DOI = ""
    if "DOI" in i["data"].keys():
        DOI = i['data']['DOI']
    ISBN = ""
    if "ISBN" in i["data"].keys():
        ISBN = i['data']['ISBN']
    conferenceName = ""
    if "conferenceName" in i["data"].keys():
        conferenceName = i['data']['conferenceName']
    proceedingsTitle = ""
    if "proceedingsTitle" in i["data"].keys():
        proceedingsTitle = i['data']['proceedingsTitle']

    items_dict[current_key] = (current_key, i['key'], i['data']['title'], i['data']['text'], i['data']['has_abstract'], abstract, year, date, language,
                               pages, volume, publication, place, url, itemType, DOI, ISBN, conferenceName, proceedingsTitle, i['links']['alternate']['href'], LIBRARY_ID)
    rev_items_dict[i["key"]] = current_key
    current_key += 1
cursor.executemany('INSERT INTO items VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [
                   v for v in items_dict.values()])
cursor.execute("UPDATE synchronizations SET status='%d articles retrieved' WHERE id=(SELECT MAX(id) FROM synchronizations)" %
               len(rev_items_dict), )
# Save (commit) the changes
conn.commit()
print("Dumped article data, %d" % len(rev_items_dict))

# dump author-item links
cursor.execute('''DROP TABLE IF EXISTS author_item''')
cursor.execute('''CREATE TABLE author_item
             (author_id integer, item_id integer,
             	FOREIGN KEY (author_id) REFERENCES authors(id),
             	FOREIGN KEY (item_id) REFERENCES items(id))''')
ai_links = list()
for i in items:
    if not 'title' in i['data'].keys() or not 'creators' in i['data'].keys():
        continue
    for c in i['data']['creators']:
        if not "lastName" in c.keys():
            continue
        fn = ""
        ln = c["lastName"]
        if "firstName" in c.keys():
            fn = c["firstName"]
        ai_links.append((author_dict[(fn, ln)], rev_items_dict[i['key']]))
cursor.executemany('INSERT INTO author_item VALUES (?,?)', ai_links)
# Save (commit) the changes
conn.commit()
print("Dumped a-i data")

# dump classification information into a separate table
cols = zot.collections()
# Create table
cursor.execute('''DROP TABLE IF EXISTS collections''')
cursor.execute('''CREATE TABLE collections
             (id text primary key, name text, parent text,
             	FOREIGN KEY (parent) REFERENCES collections(id) ON DELETE SET NULL)''')
collection_items = [(x['key'], x['data']['name'], x['data']
                     ['parentCollection']) for x in cols]
cursor.executemany('INSERT INTO collections VALUES (?,?,?)', collection_items)
# Save (commit) the changes
conn.commit()
print("Dumped collection data, %d" % len(collection_items))

# dump collection-item links
cursor.execute('''DROP TABLE IF EXISTS collection_item''')
cursor.execute('''CREATE TABLE collection_item
             (collection_id text, item_id integer,
             	FOREIGN KEY (collection_id) REFERENCES collections(id),
             	FOREIGN KEY (item_id) REFERENCES items(id))''')
links = [(c, rev_items_dict[i['key']]) for i in items if i['key'] in rev_items_dict.keys(
) and 'collections' in i['data'].keys() for c in i['data']['collections']]
cursor.executemany('INSERT INTO collection_item VALUES (?,?)', links)
# Save (commit) the changes
conn.commit()
print("Dumped c-i data")

# textual similarity
# TODO

# topic modelling
# see https://nbviewer.jupyter.org/github/rare-technologies/gensim/blob/develop/docs/notebooks/atmodel_tutorial.ipynb

nlp = spacy.load('en_core_web_sm')
FR_STOPWORDS = stopwords.words('french')
SP_STOPWORDS = stopwords.words('spanish')
STOPWORDS = stopwords.words('english')

cursor.execute(
    "UPDATE synchronizations SET status='Data preprocessing' WHERE id=(SELECT MAX(id) FROM synchronizations)", )
conn.commit()

processed_docs = list()
docs = list()
doc_ids = list()
doc_dict = OrderedDict()
doc_rev_dict = OrderedDict()
author2doc = defaultdict(list)
counter = 0
for i in items_dict.values():
    if i[4]:  # if len(i[2])>0:
        docs.append(i[3].lower().strip())
        doc_ids.append(i[0])
        doc_dict[i[0]] = counter
        doc_rev_dict[counter] = i[0]
        auths = [x[0] for x in ai_links if x[1] == i[0]]
        for a in auths:
            author2doc[auth_rev_index[a]].append(counter)
        counter += 1
pickle.dump(docs, open(abspath(join(dirname(__file__), 'models/docs.pk')), "wb"))
pickle.dump(author2doc, open(abspath(join(dirname(__file__), 'models/author2doc.pk')), "wb"))

for doc in nlp.pipe(docs, n_process=5, batch_size=100):
    # Process document using Spacy NLP pipeline.
    ents = doc.ents  # Named entities

    # Keep only words (no numbers, no punctuation).
    # Lemmatize tokens, remove punctuation and remove stopwords.
    doc = [token.lemma_ for token in doc if token.is_alpha and not token.is_stop]

    # Remove common words from a stopword list.
    doc = [token for token in doc if token not in STOPWORDS and token not in FR_STOPWORDS and token not in SP_STOPWORDS and len(
        token) > 2]

    # Add named entities, but only if they are a compound of more than word.
    doc.extend([str(entity) for entity in ents if len(entity) > 1])

    processed_docs.append(doc)
docs = processed_docs
del processed_docs

# Compute bigrams.
# Add bigrams and trigrams to docs (only ones that appear 15 times or more).
bigram = Phrases(docs, min_count=15)
for idx in range(len(docs)):
    for token in bigram[docs[idx]]:
        if '_' in token:
            # Token is a bigram, add to document.
            docs[idx].append(token)

# Create a dictionary representation of the documents, and filter out frequent and rare words.

dictionary = Dictionary(docs)
# Remove rare and common tokens.
# Filter out words that occur too frequently or too rarely.
max_freq = 0.5
min_wordcount = 5
dictionary.filter_extremes(no_below=min_wordcount, no_above=max_freq)

_ = dictionary[0]  # This sort of "initializes" dictionary.id2token.
dictionary.compactify()
dictionary.save(abspath(join(dirname(__file__), 'models/dictionary.dict')))
# Vectorize data.

# Bag-of-words representation of the documents.
corpus = [dictionary.doc2bow(doc) for doc in docs]
MmCorpus.serialize(abspath(join(dirname(__file__), 'models/corpus.mm')), corpus)

print('Number of authors: %d' % len(author2doc))
print('Number of unique tokens: %d' % len(dictionary))
print('Number of documents: %d' % len(corpus))

cursor.execute(
    "UPDATE synchronizations SET status='Training AI model' WHERE id=(SELECT MAX(id) FROM synchronizations)")
conn.commit()

# models

params = {'passes': 100, 'random_state': 42}
# base with 10, 20, 50 and 100 topics
n_tops = [10, 20]
base_models = dict()
auth_models = dict()
for nt in n_tops:
    cursor.execute(
        "UPDATE synchronizations SET status='Training AI model %i topics ' WHERE id=(SELECT MAX(id) FROM synchronizations)" % nt)
    conn.commit()
    # fix windows 10
    if __name__ == '__main__':
        multiprocessing.freeze_support()
        model = LdaMulticore(corpus=corpus, num_topics=nt, id2word=dictionary, workers=3,
                             passes=params['passes'], random_state=params['random_state'], eval_every=None)
        model.save(abspath(join(dirname(__file__), 'models/base_model_%d.lda' % nt)))
        base_models[nt] = model
        model = AuthorTopicModel(corpus=corpus, num_topics=nt, id2word=dictionary,
                                 author2doc=author2doc, passes=params['passes'], random_state=params['random_state'])
        model.save(abspath(join(dirname(__file__), 'models/auth_model_%d.atmodel' % nt)))
        auth_models[nt] = model
print("Models trained and stored")

cursor.execute(
    "UPDATE synchronizations SET status='AI Model trained' WHERE id=(SELECT MAX(id) FROM synchronizations)")
conn.commit()

# Store topic models in DB
# dump collection-item links
how_many_words = 20
words = ", ".join(["word_%d text, weight_%d real" % (n, n)
                  for n in range(how_many_words)])
cursor.execute('''DROP TABLE IF EXISTS topic_models''')
cursor.execute('''CREATE TABLE topic_models
             (topic_id text PRIMARY KEY, type text, n_topics integer, %s, UNIQUE (topic_id, type, n_topics))''' % words)
topics = list()
for k, m in base_models.items():
    for topic in range(k):
        topic_id = "base_%d_%d" % (k, topic)
        model_type = "base"
        entry = [topic_id, model_type, k]
        for w in m.show_topic(topic, how_many_words):
            entry.append(w[0])
            entry.append(float(w[1]))
        topics.append(tuple(entry))
for k, m in auth_models.items():
    for topic in range(k):
        topic_id = "auth_%d_%d" % (k, topic)
        model_type = "auth"
        entry = [topic_id, model_type, k]
        for w in m.show_topic(topic, how_many_words):
            entry.append(w[0])
            entry.append(w[1])
        topics.append(tuple(entry))
cursor.executemany(
    'INSERT INTO topic_models VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', topics)
# Save (commit) the changes
conn.commit()
print("Dumped topic data")

# link topics with documents
cursor.execute('''DROP TABLE IF EXISTS topic_docs''')
cursor.execute('''CREATE TABLE topic_docs
             (topic_id text, item_id integer, weight real,
             	FOREIGN KEY (topic_id) REFERENCES topic_models(topic_id),
             	FOREIGN KEY (item_id) REFERENCES items(id))''')
links = list()
for doc, doc_id in zip(corpus, doc_ids):
    for k, m in base_models.items():
        res = sorted(m[doc], key=lambda x: x[1], reverse=True)
        for r in res:
            links.append(("base_%d_%d" % (k, r[0]), doc_id, float(r[1])))
cursor.executemany('INSERT INTO topic_docs VALUES (?,?,?)', links)
# Save (commit) the changes
conn.commit()
print("Dumped topic_docs data")

# link topics with authors
cursor.execute('''DROP TABLE IF EXISTS topic_authors''')
cursor.execute('''CREATE TABLE topic_authors
             (topic_id text, author_id integer, weight real,
             	FOREIGN KEY (topic_id) REFERENCES topic_models(topic_id),
             	FOREIGN KEY (author_id) REFERENCES authors(id))''')
links = list()
for a in author2doc.keys():
    for k, m in auth_models.items():
        res = sorted(m[a], key=lambda x: x[1], reverse=True)
        for r in res:
            links.append(("auth_%d_%d" % (k, r[0]), author_dict[a], r[1]))
cursor.executemany('INSERT INTO topic_authors VALUES (?,?,?)', links)
# Save (commit) the changes
conn.commit()
print("Dumped topic_authors data")

cursor.execute("UPDATE synchronizations SET status='Synchronization DONE', end_timestamp=datetime('now') WHERE id=(SELECT MAX(id) FROM synchronizations)")
conn.commit()

# We can also close the connection if we are done with it.
# Just be sure any changes have been committed or they will be lost.
conn.close()

plt.style.use('ggplot')
warnings.filterwarnings("ignore")

# Basics
# sns.set(color_codes=True)
sns.set_context("paper")

# load models
corpus = corpora.MmCorpus(abspath(join(dirname(__file__), 'models/corpus.mm')))
dictionary = corpora.Dictionary.load(abspath(join(dirname(__file__), 'models/dictionary.dict')))
docs = pickle.load(open(abspath(join(dirname(__file__), 'models/docs.pk')), "rb"))
author2doc = pickle.load(open(abspath(join(dirname(__file__), "models/author2doc.pk")), "rb"))

# 10 topics
n_topics = 10
lda = models.LdaModel.load(abspath(join(dirname(__file__), 'models/base_model_%d.lda' % n_topics)))
auth = models.AuthorTopicModel.load(abspath(join(dirname(__file__), 'models/auth_model_%d.atmodel' % n_topics)))
data = gensimvis.prepare(lda, corpus, dictionary, mds='mmds')
pyLDAvis.save_json(data, abspath(join(dirname(__file__), '../public/data/data_base_10.js')))

# 20 topics
n_topics = 20
lda = models.LdaModel.load(abspath(join(dirname(__file__), 'models/base_model_%d.lda' % n_topics)))
auth = models.AuthorTopicModel.load(abspath(join(dirname(__file__), 'models/auth_model_%d.atmodel' % n_topics)))
data = gensimvis.prepare(lda, corpus, dictionary, mds='mmds')
pyLDAvis.save_json(data, abspath(join(dirname(__file__), '../public/data/data_base_20.js')))

# generate author info

tsne = TSNE(n_components=2, random_state=0)
smallest_author = 0  # Ignore authors with documents less than this.
authors = [auth.author2id[a] for a in auth.author2id.keys() if len(
    auth.author2doc[a]) >= smallest_author]
# Result stored in tsne.embedding_
_ = tsne.fit_transform(auth.state.gamma[authors, :])
scale = 0.1
x = tsne.embedding_[:, 0]
y = tsne.embedding_[:, 1]
author_names = [auth.id2author[a] for a in authors]
author_sizes = [len(auth.author2doc[a]) for a in author_names]
radii = [size * scale for size in author_sizes]
data = {}
data['author_names'] = author_names
data['author_sizes'] = author_sizes
data['radii'] = radii
data['x'] = str(x)
data['y'] = str(y)
with open(abspath(join(dirname(__file__), '../public/data/authors_data.json')), 'w') as outfile:
    json.dump(data, outfile)
