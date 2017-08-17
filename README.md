# Video Game Inventory

It's a responsive web application that reports on and manages my video game catalog primarily using:
- [Slim](http://www.slimframework.com)
- [RedBean](http://www.redbeanphp.com/),
- [Backbone.js](http://backbonejs.org), and
- [DataTables](https://www.datatables.net/)

Shout-out to [Giant Bomb](http://www.giantbomb.com/api) for generously providing a robust and comprehensive API free of charge.  Please review their _Terms of Use_.

## Easy to use

[DEMO](https://video-game-inventory.herokuapp.com/)

## Easy to build & host

#### Requirements

- PHP 5.6
- MySQL 5.6
- Ruby 2.0
- Sass 3.4

#### Create a local clone of this repo

> `git clone https://github.com/mykisscool/video-game-inventory`

#### Create an `.env` file with the following variables (or create environment variables)

- `APP_PATH`
- `DEBUG`
- `DBHOST_MIGRATIONS`
- `DBHOST_WEB`
- `DBNAME`
- `DBPORT`
- `DBUSER`
- `DBPASS`
- `GIANTBOMB_API_KEY`

Please note that I've created two similar `DBHOST` variables.  That's because I developed this application on a [Vagrant Homestead box](https://laravel.com/docs/5.4/homestead) and it's a known issue.  If you aren't using Vagrant, you can probably use the same host for both variables.

#### Database setup and dependencies

If you don't have Sass installed, you'll need to run the following command:

> `bundle install`

Create an empty database called `video_game_inventory` (or whatever the value for `DBNAME` is) and run:

> `npm install`

> `composer install`

This will create and seed your database as well as gather all project dependencies.

Don't forget to sign up for your [Giant Bomb API key](https://auth.giantbomb.com/signup).  Once you have, run the following test to make sure it's working:

> `./vendor/bin/phpunit --filter GiantBombApiResponseTest`

#### Apache and nginx configuration

The REST API was built using [Slim](http://www.slimframework.com).  This application won't work right out of the box- there are some basic configurations required for [nginx and Apache](https://www.slimframework.com/docs/start/web-servers.html).  The configurations will be altered slightly if you are installing this application in a subdirectory.

You may (or may not) have issues with the fonts used as well.  If you are encountering 404 errors with the fonts or they won't render properly- please refer to [this wiki](https://github.com/fontello/fontello/wiki/How-to-setup-server-to-serve-fonts).

## Roadmap

1. Integrate features that could streamline sharing amongst users who host this application (if anyone).
2. Incorporate social media to facilitate sharing and trading.
3. Add a little bit more dashboard reporting.
4. Lock it down with some user authentication if this web application is ever public-facing.
5. ~~Tests.~~

## Screenshots

> *Homepage- charts and stuff*

![Screenshot 1](/src/img/screenshot-1.png?raw=true "Homepage- charts and stuff")

> *Interactive catalog*

![Screenshot 2](/src/img/screenshot-2.png?raw=true "Interactive catalog")

> *Adding new games is easy*

![Screenshot 3](/src/img/screenshot-3.png?raw=true "Adding new games is easy")

## License

Everything in this repo is MIT-licensed unless otherwise specified.
