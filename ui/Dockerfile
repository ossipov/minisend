ARG ui
FROM node:15-alpine

WORKDIR /usr/src/app

#VOLUME ./ui /usr/src/app
COPY package.json yarn.lock ./
#COPY package.json ./
RUN yarn install --no-cache --frozen-lockfile;
#RUN yarn install;
