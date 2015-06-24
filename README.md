# Doco[L]ight

We'll enlighten your path! Welcome to DocoLight. DocoLight is a simple yet powerfull library for your Yii 1.1.x application.
Enjoy your development! Suck less, code more! I believe your code can be so beauty and your development will be faster,
because DocoLight include these powerfull packages:

- Container (A powerfull Inversion of Control container)
- HTTP Response (Abstraction)
- Carbon (Date Time Helper)
- Dumper (Based on Symfony Dumper with more readable color schema)
- Helper
- And many more

---

## Installation

Just clone this repository to your project:

```sh
cd {YOUR_PROJECT_ROOT_PATH}
git clone https://github.com/krisanalfa/docolight protected/libs
```

> **Notes:** After this step, you may see that there's a `libs` folder inside your `protected` folder.

This module should load before application run. So, you may change your configuration in `preload` section:

```php
'preload' => array('docolight')
```

And make sure you have include `docolight` in your `components`:

```php
'components' => array(
    'docolight' => array('class' => 'application.libs.docolight.Docolight.Docolight'),
),
```

To test this package is installed successfuly, you may do this in one of your **`Controller`**:

```php
dd(container('response')->produce());
```

If you find a page with something like this, DocoLight has enlighten your life.
![docolight](http://s12.postimg.org/qea787eil/sekretariat_ahu_local_surat_Tugas.png)

---

## Using a Container

Inversion of control container is a powerful tool for managing class dependencies.
Dependency injection is a method of removing hard-coded class dependencies.
Instead, the dependencies are injected at run-time, allowing for greater flexibility as dependency implementations may be swapped easily.

```php
// Instantiate new container
$container = new Docolight\Container\Container;

// Bind a container
$container->bind('foo', function () {
    return new Foo;
});

// Resolve type from container:
$container->make('foo'); // Foo implementation
```

Container also has ability to resolve the dependency by itself.

```php
interface FooContract
{
    public function foo();
}

class Foo implements FooContract
{
    public function foo()
    {
        // Do some magic
    }
}

class FooFactory
{
    protected $foo = null;

    public function __construct(FooContract $foo)
    {
        $this->foo = $foo;
    }
}

$container = new Docolight\Container\Container;

$container->bind('FooContract', 'Foo');

$fooFactory = $container->make('FooFactory');
// equals with: $fooFactory = new FooFactory(new Foo());
```

You can also access `container` function:

```php
container()->bind('FooContract', 'Foo');

$fooFactory = container('FooFactory');
```
Sometimes, you may wish to bind something into the container that should only be resolved once, and the same instance should be returned on subsequent calls into the container:

```php
$container->singleton('FooBar', function($container) {
    return new FooBar($container->make('SomethingElse'));
});
```

You may also bind an existing object instance into the container using the `instance` method. The given instance will always be returned on subsequent calls into the container:

```php
$fooBar = new FooBar(new SomethingElse);

$container->instance('FooBar', $fooBar);
```
---

## Make a Response

In Yii 1.1.x, we can't utilize application response programatically.
This class is a simple abstraction over top an HTTP response.
This provides methods to set the HTTP status, the HTTP headers, and the HTTP body.

```php
$response = new Docolight\Http\Response(new Docolight\Http\Headers());

$header = array(
    'Content-Description' => 'File Transfer',
    'Content-Type' => 'application/octet-stream',
    'Content-Disposition' => 'attachment; filename=Foo Bar.xml',
    'Expires' => '0',
    'Content-Type' => 'text/xml'
);

$response->headers->set($header);
$response->setStatus(200);
$response->setBody($xml);

$response->send();
```

Response also have a shorter way to utilize itself via `container`

```php
$response = container('response')->produce();
$response->headers->set($header);
$response->setStatus(200);
$response->setBody($xml);

$response->send()
```

You can also access them in a chain method:

```php
$response = container('response')->produce();
$response->headers->set($header)
         ->setStatus(200)
         ->setBody($xml)
         ->send();
```

Or the sortest way via `response` function:

```php
response('base', 200, $header, $xml)->send();

// Or
response('base', 200, $header, $xml, true);
```

### JSON Response

In so many case, you may need a faster implementation to send your JSON representation as a response.

```php
$response = new Docolight\Http\JsonResponse(new Docolight\Http\Headers());

$response->headers->set('My-Header', 'My header value');

$response->setStatus(200);

$data = array(
    'my_json_index' => 'my_json_value',
    'another_index' => 'another_value',
);

$response->setBody(new Docolight\Support\Fluent($data));

$response->send();
```

Or in shorter way:

```php
$response = container('response')->produce('json');

$data = array(
    'my_json_index' => 'my_json_value',
    'another_index' => 'another_value',
);

$response->headers->set('My-Header', 'My header value')
         ->setStatus(200)
         ->setBody(new Docolight\Support\Fluent($data))
         ->send();
```

Or via `response` function:

```php
response('json', 200, new Docolight\Support\Fluent($data), array('My-Header' => 'My header value'))->send();
```

> **NOTE** The argument passed to `setBody` on JsonResponse must implements `Docolight\Http\Contract\Arrayable`. Meanwhile, `Docolight\Support\Fluent`, `Docolight\Support\Collection`, and `Docolight\Support\ActiveRecordWrappert` implements this interface.

---

## Additional Helper Class

Below is some of helpers available in DocoLight:

### `array_add`

The `array_add` function adds a given key / value pair to the array if the given key doesn't already exist in the array.

```php
$array = ['foo' => 'bar'];

$array = array_add($array, 'key', 'value');
```

### `array_divide`

The `array_divide` function returns two arrays, one containing the keys, and the other containing the values of the original array.

```php
$array = ['foo' => 'bar'];

list($keys, $values) = array_divide($array);
```

### `array_except`

The `array_except` method removes the given key / value pairs from the array.

```php
$array = array_except($array, ['keys', 'to', 'remove']);
```

### `array_only`

The `array_only` method will return only the specified key / value pairs from the array.

```php
$array = ['name' => 'Joe', 'age' => 27, 'votes' => 1];

$array = array_only($array, ['name', 'votes']);
```

### `array_sort`

The `array_sort` function sorts the array by the results of the given `Closure`.

```php
$array = [
    ['name' => 'Dharma'],
    ['name' => 'Alfa']
];

$array = array_values(array_sort($array, function($value) {
    return $value['name'];
}));
```

### `array_where`

Filter the array using the given `Closure`.

```php
$array = [100, '200', 300, '400', 500];

$stringOnly = array_where($array, function($key, $value) {
    return is_string($value);
});

// Array ( [1] => 200 [3] => 400 )
```

### `camel_case`

Convert the given string to `camelCase`.

```php
$camel = camel_case('foo_bar');

// fooBar
```

### `snake_case`

Convert the given string to snake_case.

```php
$snake = snake_case('fooBar');

// foo_bar
```

### `studly_case`

Convert the given string to StudlyCase.

```php
$value = studly_case('foo_bar');

// FooBar
```

### `str_slug`

Generate a URL friendly "slug" from a given string.

```php
$title = str_slug("Foo Bar Baz", "-");

// foo-bar-baz
```

### `str_random`

Generate a random string of the given length.

```php
$string = str_random(40);
```

### `str_contains`

Determine if the given haystack contains the given needle.

```php
$value = str_contains('This is my name', 'my'); // true
$anotherValue = str_contains('This is my name', 'you'); // false
```

