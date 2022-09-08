# Candela Aluminium #

### Libraries/Technologies Used ###

* [Invisible reCAPTCHA](https://www.google.com/recaptcha)

### Finder indexer command for setting up a cron job

```bash
# 3.x
/usr/local/bin/php /home/USERNAME/public_html/cli/finder_indexer.php

# 4.x
/path/to/php /path/to/joomla/cli/joomla.php createsitemap:domain www.example.com
/path/to/php /path/to/joomla/cli/joomla.php finder:index
/path/to/php /path/to/joomla/cli/joomla.php akeeba:backup:take
```

### Features ###

## Notes regarding loading/unloading core scripts and styles

The `index.php` file has calls to the Web Asset Manager to disable core scripts and styles, but some of these will need to be disabled through template overrides or by disabling a plugin, etc. if it is not needed.

* Unpublish the `Content - Email Cloaking` plugin

### Overrides updated so as to not enable some core scripts/styles

#### Components

#### Modules

#### Layouts

* messages
