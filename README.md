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

## Step 3: Reverse proxy with apache (static configuration)

Since we run the program on windows, we had to modify this file located at `c:\Windows\System32\Drivers\etc\hosts`, we set 127.0.0.1 to demo.res.ch. We can access our static website at this address http://demo.res.ch:8080/ and our lorem ipsum generator at this one http://demo.res.ch:8080/api/lorem/

#### configuration of reverse proxy server

To configure our reverse proxy, we added two configuration files in the following folder `apache-revers-proxy/conf/sites-available`. These configurations make use of the `proxy` and `proxy-http` modules part of `apache2`. These configs perform the URL routing described above.

```xml
<VirtualHost *:80>
    ServerName demo.res.ch

    #ErrorLog ${APACHE_LOG_DIR}/error.log
    #CustomLog ${APACHE_LOG_DIR}/access.log combined

    ProxyPass "/api/lorem/" "http://172.17.0.3:3000/"
    ProxyPassReverse "/api/lorem/" "http://172.17.0.3:3000/"

    ProxyPass "/" "http://172.17.0.2:80/"
    ProxyPassReverse "/" "http://172.17.0.2:80/"    
</VirtualHost>
```

Here is the content of 001-reverse-proxy.conf and below the content of 00-default.conf

```xml
<VirtualHost *:80>
</VirtualHost>
```

Below is the Dockerfile.

```dockerfile
FROM php:7.2-apache

COPY conf/ /etc/apache2

RUN a2enmod proxy proxy_http
RUN a2ensite 000-* 001-*
```

## Step 4: AJAX requests with JQuery



## Step 5: Dynamic reverse proxy configuration

