Yii 2 Basic Gulp Sass Project Template
============================

Yii 2 Basic Gulp Sass Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application that adds support for Gulp and Sass.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[![Latest Stable Version](https://poser.pugx.org/jacmoe/yii2-mdpages-module/v/stable)](https://packagist.org/packages/jacmoe/yii2-app-basic-gulp-sass)
[![Total Downloads](https://poser.pugx.org/jacmoe/yii2-mdpages-module/downloads)](https://packagist.org/packages/jacmoe/yii2-mdpages-module)
[![Latest Unstable Version](https://poser.pugx.org/jacmoe/yii2-mdpages-module/v/unstable)](https://packagist.org/packages/jacmoe/yii2-mdpages-module)
[![License](https://poser.pugx.org/jacmoe/yii2-mdpages-module/license)](https://packagist.org/packages/jacmoe/yii2-mdpages-module)


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------
## Prerequisites
Before you start, make sure you have installed [composer](https://getcomposer.org/) and [Node.js](http://nodejs.org/).
If you are on Debian or Ubuntu you might also want to install the [libnotify-bin](https://packages.debian.org/jessie/libnotify-bin) package, which is used by Gulp to inform you about its status.

### Gulp
install gulp globally if you haven't done so before

```
npm install -g gulp
```
### Browsersync
install browsersync globally if you haven't done so before

```
npm install -g browser-sync
```

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
php composer.phar global require "fxp/composer-asset-plugin:~1.1.1"
php composer.phar create-project --prefer-dist --stability=dev jacmoe/yii2-app-basic-gulp-sass basic
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/basic/web/
~~~


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.
