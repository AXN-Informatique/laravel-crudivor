***abandonned***

# Laravel Crudivor

Ce package permet d'obtenir des pages CRUD simples avec le framework Laravel 5
sans avoir à générer de fichiers. Utile notamment pour gérer les tables de paramètres !

## Installation

Inclure le package avec Composer :

```
composer require axn/laravel-crudivor
```

Ajouter le service provider au tableau des providers dans `config/app.php` :

```
'Axn\Crudivor\ServiceProvider',
```

Ajouter l'alias de la façade au tableau des alias dans `config/app.php` :

```
'Crudivor' => 'Axn\Crudivor\Facade',
```

## Utilisation

Voir fichier `samples/crudivor.php` (qui a été copié dans `app` si vous avez
utilisé la commande `artisan vendor:publish`).

Utiliser la méthode `Crudivor::register()` pour ajouter une section CRUD, en lui
passant en paramètre le slug permettant de l'identifier et le nom de classe du
modèle.

Exemple :

```php
Crudivor::register('profiles', 'App\Models\Profile');
```

Il est ensuite possible de chainer des méthodes pour configurer la section :

- **creatable**($bool = true, array $options = []) : Indique si la section permet d'ajouter des enregistrements.
- **editable**($bool = true, array $options = []) : Indique si la section permet de modifier des enregistrements.
- **creatableAndEditable**($bool = true, array $options = []) : Alias à creatable() + editable().
- **contentEditable**($bool = true, $field = '', array $options = []) : Indique si la section permet l'édition à la volée du libellé d'un enregistrement.
- **activatable**($bool = true, $field = '') : Indique si la section permet d'activer/désactiver un enregistrement.
- **sortable**($bool = true, $field = '') : Indique si la section permet d'ordonner manuellement les enregistrements.
- **destroyable**($bool = true) : Indique si la section permet de supprimer des enregistrements.
- **routesOptions**(array $options = []) : Options à appliquer à l'ensemble des routes de la section.

Le paramètre `$options` des méthodes `creatable()`, `editable()` et `creatableAndEditable()`
est un tableau contenant les informations suivantes :

- **filters** (array) : Règles de filtrage à appliquer sur chaque champ (voir package `axn/laravel-request-filters`).
- **rules** (array ou Closure) : Règles de validation pour chaque champ.
- **messages** (array ou Closure) : Messages d'erreur de validation pour chaque champ/règle.
- **data** (Closure) : données à insérer/modifier via le modèle.

Le paramètre `$options` de la méthode `contentEditable()` contient les mêmes informations
à la différence qu'il n'y a pas besoin de spécifier le champ sur lequel appliquer
les filtres, les règles de validation ou les messages d'erreur vu que le champ est
unique et spécifié via le paramètre `$field`.

ATTENTION ! Concernant la méthode `sortable()`, si vous spécifiez `$bool` à FALSE
mais que vous renseigner `$field`, les enregistrements ne pourront pas être triés
manuellement mais la liste sera quand même ordonnée selon `$field`.

Enfin, il est possible de spécifier les informations par défaut pour l'ensemble
des sections grâce à l'instance `Crudivor::getDefault()`, s'utilise exactement
de la même manière que `Crudivor::register()` pour le chainage des méthodes.
