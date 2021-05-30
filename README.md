# RES-HTTP-infra

Repo for the HTTP infra lab 2021

Authors: Melvin Merk & Gabrielle Thurnherr

Date: 30.05.2021

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

For this step, we have written the AJAX code and use of JQuery in our source files, rather than in the running container from the webcast. We first load JQuery in the `head` of `index.html`, then in `content/js/scripts.js`, we define a function `loadDynamicContent`, which is responsible for loading some data from our Express service, and placing it in the DOM. We use `getJSON`, `each` and the selector functionality of JQuery to achieve this. We then call this repeatedly using `setInterval`.

We use the same config as for the original static website from step 1.

## Step 5: Dynamic reverse proxy configuration

We followed the webcast for this step. We use PHP to build a config file for our apache server  reading from environment variables to get the services' ip addresses at run time. We wrote these two files like recommended in the webcast `config-template.php` and `apache2-foreground`.

## Bonus steps

### Load balancing: multiple server nodes

This was achieved using `mod_proxy_balancer`  and `mod_lbmethod_byrequests`, which allow the specification of a load balance protocol, and "members" of this balance cluster as below:

```xml
<Proxy "balancer://dynamic_app">
    BalancerMember 'http://<?php print "$DYNAMIC_APP_0" ?>'
    BalancerMember 'http://<?php print "$DYNAMIC_APP_1" ?>'
</Proxy>

ProxyPass '/api/lorem/' 'balancer://dynamic_app/'
ProxyPassReverse '/api/lorem/' 'balancer://dynamic_app/'
```

This way, requests are automatically sent to one of the backend servers, chosen by our reverse proxy.

### Load balancing: round-robin vs sticky sessions

Using `mod_headers`, it is possible to add headers on responses from our reverse proxy. We can thus enable sticky sessions (as outlined here: https://httpd.apache.org/docs/2.4/en/mod/mod_proxy_balancer.html , c.f. "Examples of a balancer configuration") so that requests to the static servers are "sticky", i.e. that a user's session always connects to the same instance of the static server.

We can do this with the following:

```xml
Header add Set-Cookie 'ROUTEID=.%{BALANCER_WORKER_ROUTE}e; path=/' env=BALANCER_ROUTE_CHANGED

# balance config for static site
<Proxy "balancer://static_app">
	BalancerMember 'http://<?php print "$STATIC_APP_0" ?>' route=1
	BalancerMember 'http://<?php print "$STATIC_APP_1" ?>' route=2
	ProxySet stickysession=ROUTEID
</Proxy>

ProxyPass '/' 'balancer://static_app/'
ProxyPassReverse '/' 'balancer://static_app/'
```

### Dynamic cluster management

We did not have time to build this feature, though we did find a suitable-looking module to achieve this: mod_cluster, found here: https://docs.modcluster.io/ . This is able to perform automatic service discovery and dynamic management of services.

### Management UI

For a minimal management UI, we made use of the balancer manager built into apache, with `mod_status`. This is very basic, it allows us to manage balance factors between instances, and allows us to take down instances too. We did not add the capability to bring down docker containers or manage them directly.

This is enabled like so:

```
# config for load balance manager
<Location '/balancer-manager'>
	SetHandler balancer-manager
</Location>

ProxyPass '/balancer-manager' !
```

We have to include the extra `ProxyPass` in order that `/balancer-manager` be exempt from proxying.
