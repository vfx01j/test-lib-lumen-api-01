# Guide to Backend test: PHP Web Service

##Docker image required:
https://hub.docker.com/r/kennethpega2017/alpine-lumen-nginx/
###Basic steps to boot up docker container:
$ docker run -d -p 80:80 -p 443:443 -v [path to Host's target folder]:/var/www/application/public/lumen-api kennethpega2017/alpine-lumen-nginx
###Sample commands:
$ docker run -d -p 80:80 -p 443:443 -v /Users/xyz/Projects/www/application/public/lumen-api:/var/www/application/public/lumen-api  kennethpega2017/alpine-lumen-nginx
$ docker exec -it $(docker ps -a | grep kennethpega2017/alpine-lumen-nginx | grep "Up " | grep -o "^[0-9a-z]*") /bin/sh -l

##Web Service API calls:

##GET requests:
http://localhost/api/library/{id}
###Sample URL:
http://localhost/api/library/10123

##GET requests:
http://localhost/api/findSmallestLeaf?tree={json}
###Sample URL:
http://localhost/api/findSmallestLeaf?tree=%5B%7B%20%22root%22%3A%201%2C%20%22left%22%3A%20%7B%20%22root%22%3A%207%2C%20%22left%22%3A%20%7B%20%22root%22%3A%202%20%7D%2C%20%22right%22%3A%20%7B%20%22root%22%3A%206%20%7D%20%7D%2C%20%22right%22%3A%20%7B%20%22root%22%3A%205%2C%20%22left%22%3A%20%7B%20%22root%22%3A%209%20%7D%20%7D%20%7D%5D

##POST requests:
http://localhost/api/library
###Sample header:
X-VALID-USER:Happy New Year
###Sample parameter:
library:
[{ "id": 10124, "code": "ARC100", "name": "Architecture / Music Library", "abbr": "Arch Music", "url": "http://www.library.uq.edu.au/locations/architecture-music-library" }]

##Framework usage:
Lumen (Please see the section below for Lumen PHP Framework)

##Build tools required:
Docker, Composer



# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Unstable Version](https://poser.pugx.org/laravel/lumen-framework/v/unstable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](http://lumen.laravel.com/docs).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
# test-lib-lumen-api-01
