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

### (To Be Defined Later)

---

## Make a Response

### (To Be Defined Later)

---

## Additional Helper Class

### (To Be Defined Later)

