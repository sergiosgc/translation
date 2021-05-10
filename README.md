# PHP translation

This is a composer package providing translation support for PHP applications. It is similar to gettext, in usage, with these differences: 
* Strings and translations are stored in a database
* Translation cache in a keystore is supported (memcache or Redis usage is expected)
* The __() function (similar to gettext's \_()) supports \sergiosgc\sprintf style calls; e.g. `__('Operator name set to %<name>', $operator)`
