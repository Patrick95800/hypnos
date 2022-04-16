# Hypnos

## Introduction

Hypnos is a Studi project to evaluate our dev front/ back end abilties.

[![Hypnos website preview](https://dummyimage.com/600x400/00b2a9/fff.png&text=Hypnos)](https://patrick-hypnos.herokuapp.com/)


## Prerequisite

You must have <a href="https://symfony.com/download" rel="nofollow"> Symfony</a> installed.

## How to install ?

1) Clone the repo : `git clone https://github.com/Patrick95800/hypnos.git`
2) Copy the file `.env` to `.env.local`.
3) Edit the `.env.local` file, and change the following values : 

- `DATABASE_URL` : enter the full string to allow database connection, e.g. : `DATABASE_URL="mysql://root:root@127.0.0.1:3306/hypnos?serverVersion=5.7&charset=utf8mb4"`
- `MAILER_DSN` : enter the full string to allow mail sending, e.g. : `MAILER_DSN=gmail://username:password@default`

4) Run the following commands : 

```bash
# Install PHP dependencies
$ composer install

# Create database
$ bin/console doctrine:database:create

# Create database schema
$ bin/console doctrine:schema:update --force

# Load fixtures
$ bin/console doctrine:fixtures:load

# Start local server
$ symfony server:start
```