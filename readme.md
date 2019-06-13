[![CircleCI](https://circleci.com/gh/michael-basweti/library_lumen.svg?style=svg)](https://circleci.com/gh/michael-basweti/library_lumen)
[![Maintainability](https://api.codeclimate.com/v1/badges/8a587619dc0f7ff21a9e/maintainability)](https://codeclimate.com/github/michael-basweti/library_lumen/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/8a587619dc0f7ff21a9e/test_coverage)](https://codeclimate.com/github/michael-basweti/library_lumen/test_coverage)

# Lumen Library Api
* This is a simple Api that shows books in a library. It organises the books according to authors, title etc
## URLs
* The following are the urls one may use to interact with the API
#### POST::https://mysterious-lake-67681.herokuapp.com/api/v1/users
```
{
    "name":"Michael",
    "email":"mike@gmail.com",
    "password":"hello_password"
}
```
#### POST::https://mysterious-lake-67681.herokuapp.com/api/v1/login
```
{
    "email":"mike@gmail.com",
    "password":"hello_password"
}
```
#### GET::https://mysterious-lake-67681.herokuapp.com/api/v1/users
* here you should pass Authorization token got from login. Should be in the format:
```
bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXN0ZXJpb3VzLWxha2UtNjc2ODEuaGVyb2t1YXBwLmNvbVwvYXBpXC92MVwvbG9naW4iLCJpYXQiOjE1NjA0NTI3NjAsImV4cCI6MTU2MDQ1NjM2MCwibmJmIjoxNTYwNDUyNzYwLCJqdGkiOiJXMkNrY0dMSWRzNUxMQm45Iiwic3ViIjoxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.R2KvA1MSS3WaaWD_ZBbtFpCghKF_C4bqQbQNdcxg5yA
```
#### GET::https://mysterious-lake-67681.herokuapp.com/api/v1/users/{}
Pass id of the user you want to see, dont forget to pass authorization token

#### PUT::https://mysterious-lake-67681.herokuapp.com/api/v1/users/{}
* Pass authorization token and id together with the body you want to update e.g
```
{
    "name":"Michael Basweti",
    "email":"mike@gmail.com",
}
```
#### DELETE::https://mysterious-lake-67681.herokuapp.com/api/v1/users/{}
* Pass id of the user you want to delete, dont forget to pass authorization token
