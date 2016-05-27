po-translation
=====================
Translation .po (gettext) files via Command Line Interface (CLI) with Yandex Translator API

Requirements
------------
* PHP 5.4+

Dependencies
------------
* [gettext/gettext](https://github.com/oscarotero/Gettext) - PHP library to collect and manipulate gettext (.po, .mo, .php, .json, etc)
* [yandex/translate-api](https://github.com/yandex-php/translate-api) - Client for Yandex.Translate API
* [wp-cli/php-cli-tools](https://github.com/wp-cli/php-cli-tools) - A collection of tools to help with PHP command line utilities

## Usage

Run `php trans` via CLI interface from the package dir.

### Help 

```
php trans -h
```

### Options

```
$ php trans -h

PO Translator (using Yandex Translator https://translate.yandex.com )
Flags
  --help, -h  Show this help screen

Options
  --api-key   Yandex API key
  --src-po    source .po-file path
  --src-lang  source language [default: en]

```

Example
-----

```
php trans --api-key trnsl.1.1.20162d0a.34add747dc2e2e2f --src-po /home/user/public_html/wp-content/themes/some-theme/languages/uk_UA.po
```

Copyright
---------
antongorodezkiy, 2016

License
-------
MIT
