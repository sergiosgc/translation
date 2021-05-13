# PHP translation

This is a composer package providing translation support for PHP applications. It is similar to gettext, in usage, with these differences: 
* Strings and translations are stored in a database
* Translation cache in a keystore is supported (memcache or Redis usage is expected)
* The __() function (similar to gettext's \_()) supports \sergiosgc\sprintf style calls if sergiosgc-sprintf is present; e.g. `__('Operator name set to %<name>', $operator)`

## Installation

Install via composer. In you composer.json:

    {
        "repositories": [
            ...
            {
                "type": "vcs",
                "url": "https://github.com/sergiosgc/translation"
            }
        ],
        "require": {
            ...
            "sergiosgc/translation": "*"
        }
    }

## Database schema

Two tables, string and translation, in schema localization:

     Column  |  Type   | Collation | Nullable |                     Default                     
    ---------+---------+-----------+----------+-------------------------------------------------
     id      | integer |           | not null | nextval('localization.string_id_seq'::regclass)
     value   | text    |           | not null | 
     context | text    |           | not null | 
     in_use  | boolean |           | not null | true
    Indexes:
        "string_pkey" PRIMARY KEY, btree (id)

and

                  Table "localization.translation"
          Column      |  Type   | Collation | Nullable | Default 
    ------------------+---------+-----------+----------+---------
     string           | integer |           | not null | 
     locale_language  | text    |           | not null | 
     locale_territory | text    |           | not null | 
     value            | text    |           |          | 
    Indexes:
        "translation_pkey" PRIMARY KEY, btree (string, locale_language, locale_territory)
        
## Application setup

You need to setup the package when the application starts:
1. Give it a callback to obtain the database connection (a PDO object)
2. Provide a cache adapter object


       // Pass in a callable that returns a PDO connection
       \sergiosgc\translation\Translation::singleton()->setGetDatabase([ '\app\App', 'getDatabase' ]);
       // Pass in a \sergiosgc\translation\ICache
       \sergiosgc\translation\Translation::singleton()->setCache( new \sergiosgc\translation\RedisCache('localhost', 6379 ));

Then, at some point set the locale:

        \sergiosgc\translation\Translation::singleton()->setLocale('pt_PT');

## Usage

Usage is similar to gettext. Use `__()`:

    print(__('This will be translated'));

If \sergiosgc\sprintf is installed, you may use `__()` with the `\sergiosgc\sprintf()` signature:

    print(__('This will be processed by %<package> after translation', [ 'package' => '\sergiosgc\sprintf' ]));
        
The `print(__())` pattern can be condensed using `__p()`:

    __p('This will be processed by %<package> after translation', [ 'package' => '\sergiosgc\sprintf' ]);
