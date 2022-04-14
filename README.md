# Hypnos

## Introduction

Hypnos is a Study project made by Patrick Barros.

## How to install ?

1) Copy the file `.env` to `.env.local`.
2) Edit the `.env.local` file, and change the following values : 

- `DATABASE_URL` : enter the full string to allow database connection, e.g. : `DATABASE_URL="mysql://root:root@127.0.0.1:3306/hypnos?serverVersion=5.7&charset=utf8mb4"`
- `MAILER_DSN` : enter the full string to allow mail sending, e.g. : `MAILER_DSN=gmail://username:password@default`

3) Run the following commands : 

```bash
# Install PHP dependencies
$ composer install

# Create database
$ bin/console doctrine:database:create

# Create database schema
$ bin/console doctrine:schema:update --force

# Load fixtures
$ bin/console doctrine:fixtures:load
```
