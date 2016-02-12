Oplogger
========

Offers a convenient and fast way to create a user operation log for any Laravel 5 application. It supports parameters and writes log to database by default but can also be used with a custom repository.

Installation
------------

Require this package with composer using the following command:

```shell
composer require protechstudio/oplogger
```

After updating composer, add the service provider to the `providers` array in `config/app.php`

```php
Protechstudio\Oplogger\OploggerProvider::class,
```

You may also add the Facade in the `aliases` array in `config/app.php`

```php
'Oplogger' => Protechstudio\Oplogger\Facades\Oplogger::class,
```

Finally publish the configuration and migration files using the artisan command

```shell
php artisan vendor:publish --provider="Protechstudio\Oplogger\OploggerProvider"
```

You may also publish **only** the configuration file or the migration using the associated `config` and `migrations` tags

```shell
php artisan vendor:publish --provider="Protechstudio\Oplogger\OploggerProvider" --tag="config"
```

```shell
php artisan vendor:publish --provider="Protechstudio\Oplogger\OploggerProvider" --tag="migrations"
```

Run the migration ( _only needed if you intend to use the internal repository_ )

```shell
php artisan migrate
```

Configuration
-------------

Open the published configuration file at `config/oplogger.php`:

```php
return [

    'types' => [
        'test' => 'Basic operation for testing purposes.'
    ],

    'repository' => Protechstudio\Oplogger\Repositories\LogRepository::class
];
```

Then populate the `types` array with all operation types with their specific message.
If you intend to use a custom repository you must edit the `repository` element.

### Using a custom repository
The custom repository must implement the `LogRepositoryContract` as in the example below:

```php
...
use Protechstudio\Oplogger\Repositories\LogRepositoryContract;

class CustomRepository implements LogRepositoryContract
{
    //your custom repository implementation
}
```

Usage
-----

You may use the Oplogger service in two ways:
### Using the dependency or method injection

```php
...
use Protechstudio\Oplogger\Oplogger;

class FooController extends Controller
{
    private $oplogger;
    
    public function __construct(Oplogger $oplogger)
    {
        $this->oplogger = $oplogger;
    }
    
    public function bar()
    {
        $this->oplogger->write('test');
    }
}
```
### Using the Facade

```php
...
use Oplogger;

public function bar()
{
    Oplogger::write('test');
}
```

### Adding parameters to the operation string
The `write` method uses the `vsprintf` function internally so you may easily add any parameter you need to the type operation string in the `config/oplogger.php`
 
```php
'types' => [
        'test' => 'Basic operation for testing purposes.',
        'myoperation' => 'Has made %d operations using %s',
    ],
```

Then you can pass an array of parameters as the second argument of the `write` method
 
```php
Oplogger::write('myoperation',[4,'parameters']);

// Result will be: 'Has made 4 operations using parameters'
```

For advance use of parameters please check the [vsprintf](http://php.net/manual/en/function.vsprintf.php) documentation.

### Operation associated user
`Oplogger` automatically retrieves the logged in user using the Laravel `Auth` system. If you prefer you may also force to log the operation with a specific user passing the user id as the third argument

```php
$userid=5;
Oplogger::write('test',[],$userid);
```

The internal repository
-----------------------

If you are using the internal repository, running the published migration will create a `logs` table with `user_id`, `operation` and laravel timestamp fields.
**Please note that if you are not using the laravel default users table for users you should edit the migration accordingly or an exception will be thrown due to the user_id foreign key constraint.**

You may access the underlying `Log` model adding `use Protechstudio\Oplogger\Models\Log;` to the use statements.

Exceptions
----------

### Typing a wrong operation type key
If the operation key you type in the `write` method is not present in your configuration `types` array an `OploggerKeyNotFoundException` exception will be thrown.

### Not providing a user for the operation
If you don't provide a specific user for the operation and the user is not logged in an `OploggerUserNotLoggedException` exception will be thrown.