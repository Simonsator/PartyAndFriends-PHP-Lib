# PHP API for Party and Friends

A PHP composer library for Party And
Friends ([Free for Bungeecord](https://www.spigotmc.org/resources/party-and-friends-for-bungeecord-supports-1-7-x-to-1-19-x.9531/)
, [Extended For Bungeecord/Velocity](https://www.spigotmc.org/resources/party-and-friends-extended-edition-for-bungeecord-velocity-supports-1-7-1-19.10123/)
, [Extended For Spigot](https://www.spigotmc.org/resources/party-and-friends-extended-for-spigot-supports-1-7-1-19.11633/) (
you need to use MySQL as a data source),
or [Free For Velocity](https://forums.papermc.io/threads/party-and-friends-for-velocity-version-1-0-89.317/)), which
allows developers to access Party And Friends data in PHP.

## Requirements

* PHP 7.4 or higher
* [Composer](https://getcomposer.org/)
* A MySQL server containing the Party And
  Friends ([Free for Bungeecord](https://www.spigotmc.org/resources/party-and-friends-for-bungeecord-supports-1-7-x-to-1-19-x.9531/)
  , [Extended For Bungeecord/Velocity](https://www.spigotmc.org/resources/party-and-friends-extended-edition-for-bungeecord-velocity-supports-1-7-1-19.10123/)
  , [Extended For Spigot](https://www.spigotmc.org/resources/party-and-friends-extended-for-spigot-supports-1-7-1-19.11633/) (
  you need to use MySQL as a data source),
  or [Free For Velocity](https://forums.papermc.io/threads/party-and-friends-for-velocity-version-1-0-89.317/)) data
  which should be accessed

## Installation

Add the following lines to your composer file:

```json
{
  "require": {
    "simonsator/party-and-friends-php-lib": "dev-master"
  }
}
```

## Where to start

A demo project can be found [here](https://github.com/Simonsator/PartyAndFriends-PHP-Lib-Demo). To get started, you
should call the constructor of the class `Simonsator\PartyAndFriends\PAFPlayerManager`. You need to provide the table
prefix used by Party And Friends and a PDO object with an active connection to the MySQL database which is used by Party
And Friends. Using the methods of the `PAFPlayerManager` you can `PAFPlayer` objects, with which you can access data of
players.