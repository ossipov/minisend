# 💌 miniSend
Mail sending app based on Laravel v8 & Nuxt v2. 

## Requirements
* Docker
* Coffee
* Good mood

## What's inside?
* NUXT for UI (implements SPA/SSR) - Available at <http://127.0.0.1>
* Laravel as API - Available at <http://127.0.0.1/api>
* CSRF cookie Authentication / Authorization
* Mailhog (to test sent mail) - Available at <http://127.0.0.1:8025>
* NGINX
* MariaDB
* Redis

## Starting the app
```sh
# Clone the repo
git clone https://github.com/ossipov/minisend 
cd minisend
chmod +x run
```
App will try to run on port :80 
Please stop any service that is occupying that port before build.

```sh
# Getting dependencies and spinning up the project for the first time
./run build

# Seed DataBase with fake data (step can be skipped)
./run art db:seed

# Turning on queue worker daemon to process mails
./run art queue:work
```
UI Available at <http://127.0.0.1>

## Upgrading the app
If you are upgrading from a previous version
```sh
git pull
./run rebuild
```

## Stopping and running server
```sh
# Shutting down all services
./run down

# Bringing services up
./run up
```