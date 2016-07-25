utils-viewdumper
================

[![Build Status](https://travis-ci.org/photon/utils-viewdumper.svg?branch=master)](https://travis-ci.org/photon/utils-viewdumper)

Save a view on the disk with all assets linked in the view (CSS, JS, IMG).

Quick start
-----------

1) Add the module in your project

    composer require "photon/utils-viewdumper:dev-master"

or for a specific version

    composer require "photon/utils-viewdumper:1.0.0"

2) Declare views in your photon project

3) Enjoy !

Dump from a view name
---------------------

    use \photon\utils\viewDumper;
    viewDumper::fromView('/tmp/view.html', 'myview');

Dump from an URL
----------------

    use \photon\utils\viewDumper;
    viewDumper::fromView('/tmp/url.html', '/best/page/ever');

