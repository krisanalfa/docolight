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
![docolight](http://s4.postimg.org/6tgpuicp9/sekretariat_ahu_local_gears_rkakl.png)

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

> **NOTE** The argument passed to `setBody` on JsonResponse must implements `Docolight\Http\Contract\Arrayable`. Meanwhile, `Docolight\Support\Fluent`, `Docolight\Support\Collection`, and `Docolight\Support\ActiveRecordWrapper` implements this interface.

---

## Additional Helper Functions

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

## Additional Helper Classes

### Fluent

This class can wrap your array to an object, make it more safe to access and maintain it's attributes. You can try to fill the attribute with your Model Attributes or a bunch of collection of models.

```php
// This is your array:
$array = array(
     'foo' => 'bar',
     'baz' => 'qux' );

// Wrap your array with this class:
$myCoolArray = new \Docolight\Support\Fluent($array);

// After that you can do something like this:
echo $myCoolArray->foo;    // bar  // equals with: echo $myCoolArray['foo'];
echo $myCoolArray->baz;    // qux  // equals with: echo $myCoolArray['baz'];
echo $myCoolArray->blah;   // null // equals with: echo $myCoolArray['blah'];

$myCoolArray->blah = 'bruh'; // equals with $myCoolArray['blah'] = 'bruh';

echo $myCoolArray->blah;   // bruh
echo $myCoolArray['blah']; // bruh

// To get single attribute
$foo = $myCoolArray->get('foo');

// To get specific attributes:
$fooBaz = $myCoolArray->only(array('foo', 'bar'));

// To get all atributes except some attributes:
$fooBlah = $myCoolArray->except(array('baz'));

// To get all attributes:
$attributes = $myCoolArray->get();

// To remove single attribute:
$this->remove('foo');

// To remove specific attributes:
$this->clear(array('foo', 'baz'));

// To clear all attributes:
$myCoolArray->nuke();

// To convert all atributes to normal array:
$myArray = $myCoolArray->toArray();

// Oh, once more, you can also echoing the object, and convert them to JSON automagically!
echo $myCoolArray; // Output is a JSON // or equal with: echo $myCoolArray->toJson();

// In PHP >= 5.4, you can convert this object to json by:
$myJSON = json_encode($myCollArray); // Equals with: $myJSON = json_encode($myCoolArray->toArray());
```

### Collection

Collection contains a lot of handy methods that will make your work so much easier.

```php
$results = json_decode($github->request('users/krisanalfa/repos'));

// Wrap them in a collection.
$collection = new \Docolight\Support\Collection($results);

// Sort descending by stars.
$collection->sortByDesc('stargazers_count');

// Get top 5 repositories.
$topFiveRepo = $leaderboard->take(5);

// This method will return every value of a given key. The following example returns every user's email address, indexed by their user id.
$collection->lists('email', 'id');

// This will run a filter function over each of the items. If the callback returns true, it will be present in the resulting collection.
$collection->filter(function($user) { if ($user->isNearby($me)) return true; });

// Another great thing about collections is that they can easily be converted to json.
echo $collection->toJson();

// When you cast a collection to a string, it will actually call the toJson method
echo $collection;

// Something that's quite obvious is the count method, which just returns you how many items there are in the collection.
$collection->count();

// Works just like the query, takes the first or last number of items.
$fiveFirst = $collection->take(5);
$fiveLast = $collection->take(-5);

// The sum method will return the sum based on the key or a callback function:
$collection->sum('points');

// You can use sortBy or sortByDesc to sort the collection based on a key or a callback function:
$collection->sortBy('name');

// Sort descending by rating.
$collection->sortByDesc(function($item) { return $item->rating; });

// You can paginate the collection too!
$collection->forPage(1, 20); // For page 1, each page has 20 items in it
```

## Docoflow

Docoflow is a workflow generator. You can maintain your own workflow by this library. To use Docoflow, you may see this example:

### Example to Create A New Workflow

```php
use Carbon\Carbon;
use Docoflow\Docoflow;
use Docoflow\Entity\Step;
use Docoflow\Entity\Group;
use Docoflow\Entity\Verificator;

$docoflow = new Docoflow('Verifikasi Foo Bar');

// Make some steps for your workflow
$step = Step::make([
    [
        '$id' => 1,
        'name' => 'Step pertama dari verifikasi Foo Bar',
        'expired_at' => Carbon::now()->addDay(), // Expired besok
    ],
    [
        '$id' => 2,
        'name' => 'Step kedua dari verifikasi Foo Bar',
        'expired_at' => Carbon::now()->addDays(2), // Expired lusa
    ]
]);

// Make some groups for your workflow
$group = Group::make([
    [
        '$id' => 1,
        '$step' => 1,
        'name' => 'Group Chidori untuk verifikasi Foo Bar. Digunakan untuk step pertama.',
        'expired_at' => Carbon::now()->addDay(), // Expired besok
    ],
    [
        '$id' => 2,
        '$step' => 2,
        'name' => 'Group Raikiri untuk verifikasi Foo Bar. Digunakan untuk step ke dua.',
        'expired_at' => Carbon::now()->addDay(2), // Expired lusa
    ]
]);

// Add some verificators for your workflow
$verificator = Verificator::make([
    ['$group' => 1, 'user_id' => 1],
    ['$group' => 1, 'user_id' => 2],
    ['$group' => 2, 'user_id' => 3],
    ['$group' => 2, 'user_id' => 4],
]);

// Digunakan untuk keperluan transaksional data, gunakan koneksi yang sesuai dengan project kamu
container()->bind('docoflow.connection', function () {
    return Yii::app()->myConnection;
});

$workflow = $docoflow
    ->withStep($step)                      // Tambahkan step ke dalam workflow
    ->withGroup($group)                    // Buat group ke dalam workflow
    ->withVerificator($verificator)        // Tambahkan user verificator ke dalam group
    ->validuntil(Carbon::now()->addDay(2)) // Valid sampai lusa
    ->save();                              // Save ke dalam database
```

### Example to Fetch Your Workflow

`Flo` is a fluent class that able to manage your workflow in a very simple method. You can bulk accept a workflow, many steps, and even many verificators.

```php
use User;
use Docoflow\Flo;
use Docoflow\Models\WorkflowVerificator;

// Fetch by workflow id stored in database
$myWorkFlow = Flo::fetch(1);

dd($myWorkFlow->validUntil()); // Get workflow date validity

dd($myWorkFlow->valid()); // Determine if workflow is still valid to be verified

dd($myWorkFlow->steps()); // Get all stepp

dd($myWorkFlow->step(1)); // Get the first step
dd($myWorkFlow->step(3)); // Get the third step

$myWorkFlow->step(1)->reject()->save();  // Reject the first step
$myWorkFlow->step(1)->approve()->save(); // Approve the first step
$myWorkFlow->step(1)->reset()->save();   // Reset verification status in the first step

$myWorkFlow->step(1)->rejectIf()->save();  // Reject the first step if the verification progess is not expired yet
$myWorkFlow->step(1)->approveIf()->save(); // Approve the first step if the verification progess is not expired yet
$myWorkFlow->step(1)->resetIf()->save();   // Reset verification status in the first step if the verification progess is not expired yet

dd($myWorkFlow->step(1)->valid()); // Determine if first step is still valid to be verified

dd($myWorkFlow->groups()); // Get all groups

dd($myWorkFlow->groupsInStep(1)); // Get all groups in first step only
dd($myWorkFlow->groupsInStep(4)); // Get all groups in fourth step only

// You can also reject all verificators in certain group by
$myWorkFlow->groupsInStep(4)->reject()->save(); // Also works for rejectIf, approve, approveIf, reset, and resetIf

dd($myWorkFlow->verificators()); // Get all verificators

// You can also bulk approve all verificators
$myWorkFlow->verificators()->approve()->save();

// To reset verification status from verificators in the second step only
$myWorkFlow->verificatorsInStep(2)->reset()->save();

// Set a mutator. You can change the behavior of Workflow verificator when it's going to fetch user information
// by called this static method. The first argument of your callback is WorkflowVerificator active record.
WorkflowVerificator::mutate('user', function ($model) {
    $model = User::model()->findByPk($model->user_id);

    return ($model) ? fluent(array_except($model->attributes, [
        'id',
        'created_date',
        'created_by',
        'updated_date',
        'updated_by',
    ])) : null;
});

// Call mutator
dd($myWorkFlow->verificatorsInStep(1)->first()->getUser()); // Get user information

// If you want to approve a user determine by it's user_id
$idEqualsOne = $workflow->verificatorsInStep(1)->first(function($key, $value) {
    return (int) $value->getAttribute('user_id') === 1;
});

// Approving
$idEqualsOne->approve()->save();

// Send your workflow thru a json response
response('json', 200, $myWorkFlow->toArray(true))->send();
```
