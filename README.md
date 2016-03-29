
# Emoji-REST-API
A Restful API using Slim framework v3 for a NaijaEmoji Service that implement JWT access token for Authentication.

[![Coverage Status](https://coveralls.io/repos/github/andela-tolotin/Emoji-REST-API/badge.svg?branch=master)](https://coveralls.io/github/andela-tolotin/Emoji-REST-API?branch=master) [![Build Status](https://travis-ci.org/andela-tolotin/Emoji-REST-API.svg?branch=master)](https://travis-ci.org/andela-tolotin/Emoji-REST-API) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/andela-tolotin/Emoji-REST-API/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/andela-tolotin/Emoji-REST-API/?branch=master)

# How to use this package

Composer installation is required before using this package. To install a composer, try running this command on your terminal.

    $ curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin

# Installation
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

# Naija Emoji Endpoints

- POST /auth/login                                           Logs a user in
- GET /auth/logout                                           Logs a user out
- GET /emojis                                                List all the created emojis.
- GET /emojis/{id}                                           Gets a single emoji 
- POST /emojis                                               Create a new emoji
- PUT /emojis/{id}                                           Updates an emoji
- PATCH /emojis/{id}                                         Partially updates an emoji
- DELETE /emojis/{id}                                        Deletes a single emoji

# Endpoints with access token

- GET /auth/logout                                           Logs a user out
- POST /emojis                                               Create a new emoji
- PUT /emojis/{id}                                           Updates an emoji
- PATCH /emojis/{id}                                         Partially updates an emoji
- DELETE /emojis/{id}                                        Deletes a single emoji

# Endpoints without  access token

- POST /auth/login                                           Logs a user in
- GET /emojis                                                List all the created emojis.
- GET /emojis/{id}                                           Gets a single emoji 


To login a user and get access token




