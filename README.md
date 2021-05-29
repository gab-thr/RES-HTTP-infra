# RES-HTTP-infra

Repo for the HTTP infra lab 2021



## Step 1 - Static HTTP server with apache httpd

- You have a GitHub repo with everything needed to build the Docker image. 
  - https://github.com/gab-thr/RES-HTTP-infra 
- You can do a demo, where you build the image, run a container and access content from a browser. 

```bash
$ docker build -t res/apache-php . # build docker image
$ docker run -p 9090:80 res/apache-php # start apache server
$ docker exec -it container_name bash # start bash session on server
```

- You have used a nice looking web template, different from the one shown in the webcast.
  - using this bootstrap template called Grayscale (https://startbootstrap.com/theme/grayscale)

- You are able to explain what you do in the Dockerfile.

- You are able to show where the apache config files are located (in a running container).

- You have documented your configuration in your report.



### configuration of apache server

We are using the official php, php:7.2-apache (https://hub.docker.com/_/php/)

