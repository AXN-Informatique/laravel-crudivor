# Laravel Crudivor

This package helps you to get simple CRUD pages with Laravel 5 without having to generate any file. It's especially useful to generate parameters tables.

- **Author:** AXN Informatique
- **Website:** [http://www.axn-informatique.com/](http://www.axn-informatique.com/)
- **Version:** 1.0.0 (Not updated anymore)
- **License:** MIT license (see the license file)
_____________________________________________________________________________________

* [Installation](#installation)
* [Usage](#usage)

## Installation

To install Laravel Crudivor as a Composer package to be used with Laravel 5, simply run:

```
composer require axn/laravel-crudivor
```

Once it's installed, you can register the service provider in `config/app.php`:

```
'Axn\Crudivor\ServiceProvider',
```

Add the alias of the facade in the aliases table in `config/app.php`:

```
'Crudivor' => 'Axn\Crudivor\Facade',
```

## Usage

See the file `samples/crudivor.php` (who has been copied in `app` if you used the command : `artisan vendor:publish`).

Use the method `Crudivor::register()` to add a CRUD section, by adding in parameter the slug to identify it, and the class name of the model. 

Example:

```php
Crudivor::register('profiles', 'App\Models\Profile');
```

After that it's possible to chain methods to configure the section: 

- **creatable**($bool = true, array $options = []) : Indicate if the section is allowed to add records. 
- **editable**($bool = true, array $options = []) : Indicate if the section is allowed to modify records. 
- **creatableAndEditable**($bool = true, array $options = []) : Alias of creatable() + editable().
- **contentEditable**($bool = true, $field = '', array $options = []) : Indicate if the section is allowed to edit on the fly the wording of a record.
- **activatable**($bool = true, $field = '') : Indicate if the section is allowed to activate / deactivate a record. 
- **sortable**($bool = true, $field = '') : Indicate if the section is allowed to manually order records.
- **destroyable**($bool = true) : Indicate if the section is allowed to delete records.
- **routesOptions**(array $options = []) : Options to apply to all the routes of the section.

The parameter `$options` of `creatable()`, `editable()` and `creatableAndEditable()` methods is an array containing following informations:

- **filters** (array) : filter rules to apply on each field (see `axn/laravel-request-filters` package).
- **rules** (array or Closure) : validation rule for each field.
- **messages** (array or Closure) : validation error message for each field / rule.
- **data** (Closure) : data to insert, modify with the model.

The parameter `$options` of `contentEditable()` method, contains same informations. 
But in this case it's not necessary to specify the field on which apply filters, validation rules, or error messages, because the field is unique and defined in the `$field` parameter.

WARNING : For the `sortable()` method, if you specify `$bool` to FALSE, but `$field` is not empty, records won't be able to be manually sorted, but the list will still be sorted thanks to the `$field` parameter.

To conclude, it's also possible to specify default informations for all sections thanks to `Crudivor::getDefault()` instance.
The usage is the same than `Crudivor::register()` to chain methods.
