#PHP Input Validator

This is a really-small PHP validator which is intended to offer a easy-to-use
interface and some mechanisms to integrate with other tools. Here is how it
works:

  1. Create an associative array of values.
  2. Create a validator for each of the values in that array. The prefered way
	 of doing this is by grouping all your validators in a ValidatiorSet.
  3. Apply the ValidatorSet to the array. A ValidationError will tel you that
	 one or more fields in the array are not valid according to the rules you
	 had defined.
  4. Obtain the set of erros associated to their corresponging field by calling
	 ValidationError::getErrors() on the exception instance you catched.

Please, see the tests to have some hints on how to make this in work in code.
I plan to add some parctical examples to this docs in future commits.

The desing of this validator imitates what other frameworks do. I created it to
have a simple validation toolbelt for small projects and for fun.


##Testing

You will require PHPUnit to run the test. It is included in the `composer.json`
as a developent dep. You can run the tests as follows:

    ./vendor/bin/phpunit
