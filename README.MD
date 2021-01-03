# 📭 miniSend
Mail sending app based on Laravel & Nuxt

## Requirements
* Docker
* Coffee
* Good mood

## What's inside?
* NGINX
* MariaDB
* Redis
* NUXT for UI (implements SPA/SSR) - Available at <http://127.0.0.1>
* Laravel as API - Available at <http://127.0.0.1/api>
* Mailhog (to test sent mail) - Available at <http://127.0.0.1:8025>

## Starting the app
```sh
# Clone the repo
git clone https://github.com/ossipov/minisend 
cd minisend

# Getting dependencies and spinning up the project for the first time
./run build

# Seed DataBase with fake data (step can be skipped)
./run art db:seed

# Turning on queue worker daemon to process mails
./run art queue:work
```

UI Available at <http://127.0.0.1>

## Stopping and running server
```sh
# Shutting down all services
./run down

# Bringing services up
./run up
```