# Laravel Dump to Server Helpers

[![Latest Stable Version](https://poser.pugx.org/reedware/laravel-dump-to-server/v)](//packagist.org/packages/reedware/laravel-dump-to-server)
[![Total Downloads](https://poser.pugx.org/reedware/laravel-dump-to-server/downloads)](//packagist.org/packages/reedware/laravel-dump-to-server)

Adds the ability to dump only when the dump server is online.

## Introduction

This package leverages the [beyondcode/laravel-dump-server](https://github.com/beyondcode/laravel-dump-server) package, but extends it by adding some globally available functions. While having a dump server is great, there are times where I've wanted to change the content of dump if it's going to the dump server versus the browser. Additionally, I've also had use-cases where I didn't want to dump at all unless I knew it was going to the dump server.

## Installation

#### Using Composer

```
composer require reedware/laravel-dump-to-server
```

This package is intentially light-weight, and only requires the [beyondcode/laravel-dump-server](https://github.com/beyondcode/laravel-dump-server) package as a dev dependency. This means that you can install the package on a production environment without including dump server (assuming you have required dump server as a dev dependency). The helpers introduced by this package are aware of when the dump server package is not present.

This package uses an auto-discovered service provider.

#### Versioning

This package supports Laravel 5.6 and onward.

## Usage

Here are the two helper functions that this package introduces:

```php
isDumpServerOnline() // Returns true/false
dumpToServer() // Performs a typical dump, but only when the dump server is online
```

You can use them however you like.
