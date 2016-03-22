
# PHP Input Validator

This is a really-small PHP validator which is intended to offer a easy-to-use
interface and some mechanisms to integrate with other tools. Here is how it
works:

  1. Create an associative array of values.

  2. Create a validator for each of the values in that array. The preferred way
  of doing this is by grouping all your validators in a ValidatiorSet.

  3. Apply the ValidatorSet to the array. A ValidationError will tel you that
  one or more fields in the array are not valid according to the rules you had
  defined.

  4. Obtain the set of errors associated to their corresponding field by
  calling ValidationError::getErrors() on the exception instance you catched.

Please, see the tests to get some hints on how to make this in work in code.
I plan to add some parctical examples to this docs in future commits.

The design of this validator imitates what other frameworks do. I created it to
have a simple validation tool-belt for small projects and for fun.

Simple example:

```php

<?php

require __DIR__ . '/../vendor/autoload.php';

use Phpiv\ValidatorSet;
use Phpiv\ValidationError;

$validateThis = array(
    'name' => 'John Doe',
    'rating' => '6',
    'comment' => 'This is too short.',
    'email_address' => 'not an email address'
);

$vs = new ValidatorSet();

$vs->add('string', 'name')
    ->required();
$vs->add('number', 'age')
    ->required();
$vs->add('number', 'rating')
    ->min(1)
    ->max(5);
$vs->add('string', 'comment')
    ->length(20, 100)
    ->required();
$vs->add('email', 'email_address')
    ->required();

try {
    $vs->check($validateThis);
}
catch(ValidationError $e) {
    print_r($e->getErrors());
}

/*
The output is:

Array
(
    [age] => Array
        (
            [0] => Es requerido
        )

    [rating] => Array
        (
            [0] => No puede ser mayor de 5
        )

    [comment] => Array
        (
            [0] => Debe tener 20 caracteres como mínimo
        )

    [email_address] => Array
        (
            [0] => No es un email válido
        )

)
*/
```

## Testing

You will require PHPUnit to run the test. It is included in the `composer.json`
as a development dep. You can run the tests as follows:

    ./vendor/bin/phpunit

## Code status

[![Build Status](https://travis-ci.org/seorc/phpiv.svg?branch=master)](https://travis-ci.org/seorc/phpiv)
