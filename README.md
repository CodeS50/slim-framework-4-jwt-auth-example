# Slim Framework 4 JWT Auth Example

## Requires
* php: >=7.2
* codes50/validation
* firebase/php-jwt
* tuupola/slim-jwt-auth

## Install

Install latest version using [composer](https://getcomposer.org/).

```bash
$ composer create-project codes50/slim-framework-4-jwt-auth-example [my-app-name]
```

Replace `[my-app-name]` with the desired directory name for your new application. You'll want to:

* Point your virtual host document root to your new application's `public/` directory.
* Ensure `logs/` is web writable.

To run the application in development, you can run these commands 

```bash
cd [my-app-name]
composer start
```

Or you can use `docker-compose` to run the app with `docker`, so you can run these commands:
```bash
cd [my-app-name]
docker-compose up -d
```
After that, open `http://localhost:8080` in your browser.

Run this command in the application directory to run the test suite

```bash
composer test
```

That's it! Now go build something cool.
