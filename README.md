# Simple Api Rest using Symfony 5

## Install dependencies

```bash
  composer install
```

## Launch the php server and the mysql and phpmyadmin containers

```bash
  symfony serve -d --no-tls
  docker-compose up -d
```

**Note: -d for mode detach, -d --no-tls for disable TLS encryption**

Check the project at url: http://127.0.0.1:8000

## Configure and setup database

Create file ".env.local" and inform the DATABASE_URL (check the docker-compose file)

**Example:** DATABASE_URL=mysql://root:**db_password**@127.0.0.1:6603/**db_name**

Create the database and lauch the migrations

```bash
  php bin/console doctrine:database:create
  php bin/console doctrine:migrations:migrate
```

## Load the fake data 

```bash
  php bin/console doctrine:fixtures:load
```

## Configure JWT

Create folder jwt in config folder

```bash
  mkdir config/jwt
```

Generate the private and public key and enter the passphrase

```bash
  openssl genrsa -out config/jwt/private.pem -aes256 4096
  openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

**Note: Install open-ssl if necessary**

Update the .env.local file with lexit configuration and enter your passphrase

```bash
  ###> lexik/jwt-authentication-bundle ###
  JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
  JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
  JWT_PASSPHRASE=MY_PASSPHRASE
  ###< lexik/jwt-authentication-bundle ###
```

## Create user with console commands

```bash
  php bin/console user:create "my_email@gmail.com" my_username my_password
```

## Authenticate with postman

**Note: Import the collection from the postman software located in the postman folder**

Create POST request on endpoint 127.0.0.1:8000/api/login

```json
{
  "email": "my-email",
  "password": "my-password"
}
```

The response returns a jwt that you can use on the protected resources and collections
