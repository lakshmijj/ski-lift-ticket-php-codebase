# LAMP boilerplate

### local setup

run the below command to build the image, create and start the containers

```
docker-compose up --build
```

if image is already built use the following to start the containers

```
docker-compose up
```

## Docker services

### web-server

A php image built using the Docker official image from [DockerHub](https://hub.docker.com/_/php). Defined in the php.Dockerfile. Can be accessed from http://localhost:8080

### mysql-server

a mysql image built from DockerHub.

- username = root

### phpmyadmin

a phpmyadmin image built from DockerHub. Can be accessed from http://localhost:5000

### .env

your mysql root password must be set using a .env file.

```
MYSQL_ROOT_PASSWORD = "your root password"
```
