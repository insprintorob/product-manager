MongoDB powered Product Management Application
----

Requirements
----
- PHP 7.2
- The latest version of composer
- A local MongoDB server running on port 27017. This is the only non-php dependency

Standards
----
- SOLID
- TDD
- MVC (custom built, not natively used by Slim as it only provides a router)
- PSR

Framework
----
I've used Slim framework for simplicity and speed. This allowed me to structure the Controllers in a way that is unit testable as I have easy control of the routing and dependency injection process.

Installation
----
- Check out the repo then run `composer install`.
- Start the app using PHP's built in web server by running `composer run-script start`.
- To run the tests, run `composer run-script test`.