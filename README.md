Basecamp API PHP Wrapper
=========================

A PHP library for easy intergration with the [Basecamp Classic Api](https://github.com/37signals/basecamp-classic-api).

Getting Started
-----------------

Include the library into your project and instantiate the Basecamp class.
Make sure to pass it either your api key as a string, or an array of
authentication credentials.

Example PHP Usage:

```php
    // YOUR_ACCOUNT: Only the slug name is needed. I.e: http://your_account.basecamphq.com
    // YOUR_API_KEY: The API key given to you from within Basecamp
    // YOUR_USERNAME: you log into your account with
    // YOUR PASSWORD: The password you log into your account with

    $auth = array(
        "account"  => "YOUR_ACCOUNT",
        "api_key"  => "YOUR_API_KEY",
	      "user"     => "YOUR_USERNAME",
	      "password" => "YOUR-PASSWORD"
    );

    $basecamp = new Basecamp($auth);
    $basecamp->getProjects(); // Returns all of the user's projects
```