# microservice-votes-participation-laravel
PHP project for participation in votes. it's microservice and connected to `microservice-auth-java` for authentication and `microservice-votes-php-ci` for votes data

# Technologies
- PHP
- Laravel

#Configuration file
you can change configuration in .env.prod
```
$ OAUTH_HOST= authentication microservice host example: http://localhost
$ OAUTH_PORT= authentication microservice port
$ OAUTH_CLIENT_ID= authentication microservice client_id
$ OAUTH_CLIENT_SECRET= authentication microservice client_secret
$ OAUTH_RESOURCE_ID= authentication microservice resource_id

$ VOTE_SERVICE_ROUTE=http://microservice_vote_ip:microservice_vote_port
```
# How to install?
```
$ docker-compose up -d
```

