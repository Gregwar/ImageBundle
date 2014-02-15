Gregwar's ImageBundle
=====================

`GregwarImageBundle` provides easy Image manipulation and API for Symfony2 and Twig

Installation
============

### Step 1: Download the GregwarImageBundle

***Using the vendors script***

Add the following lines to your `deps` file:

```
    [GregwarImageBundle]
        git=http://github.com/Gregwar/ImageBundle.git
        target=/bundles/Gregwar/ImageBundle
```

Now, run the vendors script to download the bundle:

``` bash
$ php bin/vendors install
```

***Using submodules***

If you prefer instead to use git submodules, then run the following:

``` bash
$ git submodule add git://github.com/Gregwar/ImageBundle.git vendor/bundles/Gregwar/ImageBundle
$ git submodule update --init
```

***Using Composer***

Add the following to the "require" section of your `composer.json` file:

```
    "gregwar/image-bundle": "dev-master"
```

You can also choose a version number, (tag, commit ...)

And update your dependencies

```
    php composer.phar update
```

### Step 2: Configure the Autoloader

If you use composer, you can skip this step.

Add it to your `autoload.pp` :

```php
<?php
...
'Gregwar' => __DIR__.'/../vendor/bundles',
```

### Step 3: Enable the bundle

Registers the bundle in your `app/AppKernel.php`:

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

### Step 4: Configure the bundle and set up the directories

Adds the following configuration to your `app/config/config.yml`:

    gregwar_image: ~

If you want to customize the cache directory name, you can specify it:

    gregwar_image:
        cache_dir:  my_cache_dir

Creates the cache directory and change the permissions so the web server can write
in it:

    mkdir web/cache
    chmod 777 web/cache

You can also enable the exception thrown if the given file does not exist:

    gregwar_image:
        throw_exception: true

If you don't throw an exception, you can set the `fallback_image`, to set the
image that should be rendered in this case:

    gregwar_image:
        fallback_image: /path/to/your/fallback.jpg

If you have to change directories hierarchy or Web's name (e.g. web => public_html), 
you can set the `web_dir` to your new Web path:
    
    gregwar_image:
        web_dir: %kernel.root_dir%/../../public_html
        

Usage
=====

Basics
------

This bundle is based on the [Gregwar's Image](http://github.com/Gregwar/Image) class and
provides simple but powerful Twig extension. You can for instance use it this way:

```html
<img src="{{ image('linux.jpg').resize(100,100).negate }}" />
```

And that's all ! The helper will automatically create the cached file on-the-fly if it
doesn't exists yet.

The available methods are the same as the [Gregwar's Image](http://github.com/Gregwar/Image).

You can also use the logical file names for bundle resources :

```html
<img src="{{ image('@AcmeDemoBundle/Resources/images/linux.jpg').resize(100,100).negate }}" />
```

If you use `web_image()` helper, the image file path will be prefixed by the `web/` absolute
directory of your application:

```html
<!-- The image some/image.jpg will be prefixed by web directory prefix -->
<img src="{{ web_image('some/image.jpg').resize('10%') }}" />
```

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
