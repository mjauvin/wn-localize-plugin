Allow admins to override Plugin localization files from the backend UI.

New winter:lang console command that can generate a lang file for a target locale (adding missing translation strings) for core (backend, cms, system) language files.

The generated language file will be written to the App's lang folder.

Usage example:

```
php artisan winter:lang backend lang.php de
```

Note: if no argument is provided, they can be provided interactively.

```
Description:
  Add missing language translation keys for target locale

Usage:
  winter:lang [<module> [<langFile> [<targetLocale>]]]

Arguments:
  module                The module for the language file
  langFile              The language file to work on
  targetLocale          The target locale for which to add missing keys

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
```
