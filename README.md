# Spaz Web APIs #

These are web APIs written in PHP for use with Spaz clients. They may be useful to others as well.

The code here is really just the `custom` directory from a [FRAPI](http://getfrapi.com/) install. FRAPI is a PHP-based application for building RESTful APIs quickly. It's really awesome.

There is also a `tests` directory, where functional tests are done with SimpleTest. Coverage is about -10% atm.

## Requirements ##

 * [Anything FRAPI requires](http://frapi.github.com/installing/index.html)
    * Currently, we're just using APC for caching. We may move to Memcached at some point, but not sure.
 * [pecl_http](http://us.php.net/http)
 * [libcurl extension](http://us.php.net/manual/en/book.curl.php)
    * This may go away as we migrate everything pecl_http

## Installing ##

 * [Install FRAPI](http://frapi.github.com/installing/index.html)
 * Overwrite the existing `FRAPI_PATH/src/frapi/custom` with our `custom` directory
 * In theory, it's now working.

## Participating ##

 * [Learn more about helping with Spaz](http://getspaz.com/helpus)

## Credits ##

Special thanks to [David Coallier and echolibre](http://echolibre.com/) for doing most of the work getting the Spaz Web APIs going.