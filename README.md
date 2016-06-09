# Video Game Inventory

It's an application that primarily uses [Slim](http://www.slimframework.com), [RedBean](http://www.redbeanphp.com/), [Backbone.js](http://backbonejs.org), and [DataTables](https://www.datatables.net/) to report on and manage my video game inventory.  Shout-out to [Giant Bomb](http://www.giantbomb.com/api) for generously providing a robust and comprehensive API free of charge.

## Easy to use

[DEMO](http://www.mikepetruniak.com/projects/video-game-inventory/)

## Easy to build & host

##### Create a local clone of the repository

    git clone https://github.com/mykisscool/video-game-inventory

##### Update the RewriteBase directive in `/api/.htaccess`

    RewriteBase /relative/path/to/application

##### Use Composer to obtain the Slim Framework and the RedBean PHP ORM

    composer update

##### Use Bower to obtain JavaScript libraries

    bower install

##### Use Grunt to build production scripts as well as grabbing some Google fonts

    npm install  
    grunt default

##### MySQL setup

    mysql -u root -p -e "CREATE DATABASE video_game_inventory"
    mysql -u root -p video_game_inventory < src/sql/video-game-inventory.sql

And don't forget to sign up for your [Giant Bomb API key](https://auth.giantbomb.com/signup)!

## Roadmap

1. I'd love to integrate features that could streamline sharing amongst users who host this application (if anyone).
2. I'd really like to incorporate social media to facilitate sharing and trading.
3. I'd like to add a little bit more dashboard reporting.
4. I'll have to lock it down with some user authentication if this web application is ever public-facing.
5. Tests.

## License

Everything in this repo is MIT-licensed unless otherwise specified.
