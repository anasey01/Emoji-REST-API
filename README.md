
## Emoji-REST-API
A Restful API using Slim framework v3 for a NaijaEmoji Service that implement JWT access token for Authentication.

[![Coverage Status](https://coveralls.io/repos/github/andela-tolotin/Emoji-REST-API/badge.svg?branch=master)](https://coveralls.io/github/andela-tolotin/Emoji-REST-API?branch=master) [![Build Status](https://travis-ci.org/andela-tolotin/Emoji-REST-API.svg?branch=master)](https://travis-ci.org/andela-tolotin/Emoji-REST-API) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/andela-tolotin/Emoji-REST-API/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/andela-tolotin/Emoji-REST-API/?branch=master)

## How to use this package

Composer installation is required before using this package. To install a composer, try running this command on your terminal.

    $ curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin

## Installation
PHP 5.5+ and Composer are required.

### Via composer

    $ composer require Laztopaz\EmojiRestfulAPI
### Install

    $ composer install 
After you have installed this package,  the next line of  action is to consume the Emoji API.

### Database Configuration
You need set your environment variables to define your database parameters or rename .env.example file in project to .env and change the below to your local configuration.

    DRIVER   = mysql
    HOSTNAME = 127.0.0.1
    USERNAME = username
    PASSWORD = password
    DBNAME   = YourDatabase
    PORT     = port

Finally, boot-up the API service with PHP's Built-in web server:

    php -S localhost:8000 -t public

All examples are shown in POSTMAN.

To this point we have several endpoints both the ones that requires authentication and non-athenticated.

## Naija Emoji Endpoints

- POST /auth/login                                           Logs a user in
- GET /auth/logout                                           Logs a user out
- GET /emojis                                                List all the created emojis.
- GET /emojis/{id}                                           Gets a single emoji 
- POST /emojis                                               Create a new emoji
- PUT /emojis/{id}                                           Updates an emoji
- PATCH /emojis/{id}                                         Partially updates an emoji
- DELETE /emojis/{id}                                        Deletes a single emoji

## Endpoints with access token

- GET /auth/logout                                           Logs a user out
- POST /emojis                                               Create a new emoji
- PUT /emojis/{id}                                           Updates an emoji
- PATCH /emojis/{id}                                         Partially updates an emoji
- DELETE /emojis/{id}                                        Deletes a single emoji

## Endpoints without  access token

- POST /auth/login                                           Logs a user in
- GET /emojis                                                List all the created emojis.
- GET /emojis/{id}                                           Gets a single emoji 


For a user to be able to access some endpoints, a login access is required and after  a successful login, access token will be generated for the user. The user can now include the token in the header of the incoming POST request to be sent to the API.

![user login ](https://github.com/andela-tolotin/Emoji-REST-API/blob/master/screenshots/login.png)

To create an Emoji, you will need to send a POST request to the API along side with the token in order to create an Emoji.

![create an emoji](https://github.com/andela-tolotin/Emoji-REST-API/blob/master/screenshots/createemoji.png)

To get all emojis, you will need to send a GET request to the API

![get all emojis](https://github.com/andela-tolotin/Emoji-REST-API/blob/master/screenshots/getallemojis.png)

To get a single emoji, you will also need to a send a GET request and the id of the emoji to be retrieved.

![get single emoji](https://github.com/andela-tolotin/Emoji-REST-API/blob/master/screenshots/getsingleemoji.png)

To update multiple fields of an Emoji, you will need to send a PUT request 
To update a single field of an Emoji, you will need to send a PATCH request and set the form encoding to x-www-form-url-encoded along side with access token as header along side with access token.
![put update an emoji](https://github.com/andela-tolotin/Emoji-REST-API/blob/master/screenshots/putupdateeemoji.png)

To update a single field of an Emoji, you will need to send a PATCH request and set the form encoding to x-www-form-url-encoded along side with access token as header.
![patch update an emoji](https://github.com/andela-tolotin/Emoji-REST-API/blob/master/screenshots/patchupdateeemoji.png)


To delete an Emoji, you will need to send a DELETE request along side with access token as header.
![delete an emoji](https://github.com/andela-tolotin/Emoji-REST-API/blob/master/screenshots/deleteemoji.png)







