# PackageLoader

just another package manager for `laravel` nothing more and nothing less

but if you want to use it you are welcome

## Use case
use this loader if:
* you want a system to manage migrations parallel to the laravel one
* you cannot use the laravel system to mount service providers from composer
  * for example your packages can be enabled or disabled dynamically by the user

## Pros
* you can write your own package loading strategies
* you can write your own parallel migration strategies (although I recommend to use the one that is already built-in)
* you can write your own package recovery mode


## TODO
* automatic translation management
* publish to composer packages
* Write doc
