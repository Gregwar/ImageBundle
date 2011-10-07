Gregwar's ImageBundle
=====================

`GregwarImageBundle` provides easy Image manipulation and API for Symfony2 and Twig

Installation
============

To install `GregwarImageBundle`, first adds it to your deps and clone it in your
vendor directory, then add the namespace to your `app/autoload.php` file:

```
<?php
...
'Gregwar' => __DIR__.'/../vendor/gregwar-image/bundle/',
```

And registers the bundle in your `app/AppKernel.php`:

```php
<?php
...
public function registerBundles()
{
    $bundles = array(
        ...
        new Gregwar\ImageBundle\GregwarImageBundle(),
        ...
    );
...
```

Adds the following configuration to your `app/config/config.yml`:

    gregwar_image: ~

If you want to customize the cache directory name, you can specify it:

    gregwar_image:
        cache_dir:  my_cache_dir

Creates the cache directory and change the permissions so the web server can write 
in it:

    mkdir web/cache
    chmod 777 web/cache

Usage
=====

Basics
------

This bundle is based on the [Gregwar's Image](http://github.com/Gregwar/Image) class and
provides simple but powerful Twig extension. You can for instance use it this way:

    <img src="{{ image('linux.jpg').resize(100,100).negate }}" />

And that's all ! The helper will automatically create the cached file on-the-fly if it 
doesn't exists yet.

The available methods are the same as the [Gregwar's Image](http://github.com/Gregwar/Image).

Using Image API
---------------

The image instance provides also a simple API, you can call some methods to get informations
about the handled image:

    Image width: {{ image('linux.jpg').width }}px

Manipulating Image in Controllers
---------------------------------

The Image Handler is accessible via a service called image.handling. So you can do in your
controllers:

```php
<?php
...
$this->get('image.handling')->open('linux.jpg')
    ->grayscale()
    ->rotate(12)
    ->save('out.jpg')
```

Requirements
============

`GregwarImageBundle` needs [GD](http://php.net/gd)
and [exif](http://php.net/exif) extension for PHP to be installed on the web server

License
=======

This bundle is under MIT license
