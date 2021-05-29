# RES-HTTP-infra

Repo for the HTTP infra lab 2021

## Step 1 - Static HTTP server with apache httpd

Here is the GitHub repository we use for this labo https://github.com/gab-thr/RES-HTTP-infra 

In the first part, we have built a static website served using apache which runs in a docker container. For the static website, we used the Grayscale bootstrap starter (found here https://startbootstrap.com/theme/grayscale)

#### configuration of apache server

We are using the official php image which contains apache, php:7.2-apache (https://hub.docker.com/_/php/)

## Step 2: Dynamic HTTP server with express.js

For this step, we created a lorem ipsum generator using node, chance and express. We are using `Chance` to generate random words, sentences and paragraphs and `Express` for listening and answering to http requests. Like the first step, this application also runs within a docker container.

#### configuration of node image

We are using the following version of `node js` for our docker image. node:14.17.
