# Knowledge Mapping - A source visualization tool 📚

<p align="center">
    <img alt="Static Badge" alt="Licence MPL 2.0 badge" src="https://img.shields.io/badge/licence-MPL_2.0-blue">
    <img alt="Static Badge" alt="Version number 0.1.0 badge" src="https://img.shields.io/badge/version-0.1.0-blue">
    <img alt="Static Badge" alt="Open to PRs badge" src="https://img.shields.io/badge/open_to_PRs-green">
</p>

**🔗 Try it: https://knowledge-mapping.heig-vd.ch/**

## Introduction

The Knowledge Mapping (KMap) web tool aims to provide an experimental tool to get access to a large and extending scientific knowledge from a specific domain.

By using a range of machine learning algorithms - topic modelling, text mining and entity recognition to name a few - the platform proposes an innovative way to explore a very large and curated [Zotero](https://www.zotero.org/) collection of academic papers.

## Installation

### Requirements

- [Git](https://git-scm.com/)
- [Docker](https://www.docker.com/) + [Docker Compose](https://docs.docker.com/compose/install/)

- [A Zotero Collection](https://www.zotero.org/groups/)

  ***Disclaimer**: As the processing of documents is based mainly on the content of titles, abstracts and authors entered in the Zotero collection, it is important to ensure that these have been filled in for every document.*

    ***Note**: those requirements are not enough to run python scripts since they need multiple dependencies. It is recommended to run the docker container (see below) in order to use them.*
### Clone the repository

Clone the repository

```bash
git clone git@gitlab.com:mediacomem/knowledge-mapping.git
```

Move to the created repo

```bash
cd knowledge-mapping
```

### Adapt the environment variables

1. Create the `.env` file in `./front` (then fill it with the correct values)

```bash
cp ./front/.env.example ./front/.env
```
2. Create the `.env` file in `./python` (then fill it with your Zotero API key and the Zotero Group ID)
You'll find a [quick guide to get them here](./doc/zotero/README.md).

```bash
cp ./python/.env.example ./python/.env
```
### Setup, build and run the docker containers
1. Make sure Docker daemon is running

```bash
docker
```

2.  Build the docker image

```bash
docker compose build
```

3. Run the docker-compose

```bash
docker compose up -d
```

*Note: you can run the docker-compose in the background by adding the `-d` (detached) flag*

2. Run a bash shell in the container

```bash
docker exec -it kmap bash
```
3. Once in the container, run the following commands:
```bash
php artisan key:generate
```
*Note: will generate a new encryption key for the application*
```bash
php artisan migrate
```
You can safely prompt ```yes``` and bypass the warning.

*Note: will create the dayestabase tables*
```bash
php artisan db:seed
```
Again, you can safely prompt ```yes``` and bypass the warning.

*Note: will seed the database with the default data*
4. Run machine learning scripts (needed at least once)

```bash
php artisan zotero:sync
```
5. Wait for the machine learning scripts to finish (it can take a while). You can check the status with the following command:

```bash
php artisan zotero:status
```
### Access the web app
1.  The script execution ran successfully ? You should now be able to access it through http://localhost:8000
2.  Admin view is available at http://localhost:8000/login
3.  Enjoy 🎉

## First admin credentials

A first admin user is created in order to acces the admin dashboard for the first time. The credentials are set in the `.env` file with the following environment variables:
- `ADMIN_USER`
- `ADMIN_EMAIL`
- `ADMIN_PASSWORD`

*Note: we recommend changing the password after the first login.*

## Stack

- [Docker](https://www.docker.com/) / [Docker Compose](https://docs.docker.com/compose/)
- [Laravel 10](https://laravel.com/)
- [Livewire 3](https://livewire.laravel.com/)
- [SQLite](https://www.sqlite.org/index.html)
- [Python 3.8](https://www.python.org/)

## Sources and credits 🙏

The ML part of the project is based on the LDA for author-topic modeling of the [Gensim Python library](https://github.com/piskvorky/gensim/tree/develop) : https://github.com/piskvorky/gensim/blob/develop/docs/notebooks/atmodel_tutorial.ipynb.

Initiated in 2018 by [Prof. Laurent Rivier](https://www.rivier-consulting.com/), [designer Laurent Bolli](https://www.odoma.ch/about), and [data scientist Giovanni Colavizza](https://www.odoma.ch/about) the current version of the platform is its first stage of development by the [Media engineering Institute](https://www.heig-vd.ch/ingenierie-medias) at the [University of Applied Sciences Western Switzerland](https://www.heig-vd.ch/).

## Contributing
Many people have been involved in the development of this prototype. Here is a list of the main contributors:
- Jorge Stamatio
- Maksym Nevinchanyy
- Gaël Paccard
- Stéphane Lecorney
- Jonathan Favre-Lamarine
- Robin Zweifel

## License
This project is licensed under the Mozilla Public License 2.0 - see the [LICENSE.md file]((./LICENSE.md)) for details.