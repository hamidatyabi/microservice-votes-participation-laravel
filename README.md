# microservice-votes-participation-laravel
PHP project for participation in votes. it's microservice and connected to `microservice-auth-java` for authentication and `microservice-votes-php-ci` for votes data

# Technologies
- PHP
- Laravel

# Configuration file
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

# Push vote for user
URL: http://host:port/api/v1/vote/push
Method: POST
## Header
You can push option of votes to this api and assign to user votes
```
Authorization: Bearer access_token
```
## Body:
```json
{
    "voteId": 1,
    "optionId": "1"
}
```
## if you voted this voteId before you get this response
```json
{
    "error_code": 406,
    "error": "You voted this option"
}
```
## if pushed your vote option
```json
{
    "user_id": 1,
    "vote_id": 1,
    "option_id": 2,
    "assign_time": "2019-12-16 11:12:34",
    "id": 12
}
```

# Check vote for user
URL: http://host:port/api/v1/vote/check
Method: POST
## Header
You can push option of votes to this api and assign to user votes
```
Authorization: Bearer access_token
```
## Body:
```json
{
    "voteId": 1
}
```
## if you voted to this voteId then you get "true" value else get "false" it's mean you can push vote
```json
{
    "voted": true // true || false
}
```

