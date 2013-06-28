Basecamp Classic API Wrapper for PHP
======================================

A PHP library for easy intergration with the [Basecamp Classic Api](https://github.com/37signals/basecamp-classic-api).

Getting Started
-----------------

Include the library into your project and instantiate the Basecamp class.
Make sure to pass it either your api key as a string, or an array of
authentication credentials.

Example PHP Usage:

```php
include "path/to/basecamp.php";

// YOUR_ACCOUNT:  Only the slug is needed. (i.e: http://your_account.basecamphq.com)
// YOUR_API_KEY:  The API key given to you from within Basecamp
// YOUR_USERNAME: The username you log into your account with
// YOUR PASSWORD: The password you log into your account with

$auth = array(
    "account"  => "YOUR_ACCOUNT",
    "api_key"  => "YOUR_API_KEY",
    "user"     => "YOUR_USERNAME",
    "password" => "YOUR-PASSWORD"
);

// Instantiate the object and pass in the credentials.

$basecamp = new Basecamp($auth);
```

For developer convenience the constructor method accepts either authentication elements via an array, or a simple string containing only the basecamp api key.

If using the api key only, you'll need to use the authentication
setter methods after you have instantiated the class object.

Example:

```php
$basecamp = new Basecamp(YOUR_API_KEY);

$basecamp -> setAccount(YOUR_ACCOUNT);
$basecamp -> setUser(YOUR_USERNAME);
$basecamp -> setPassword(YOUR_PASSWORD);

These methods are also chainable:

$basecamp->setAccount()->setUser()->setPassword();
```

Using the Wrapper
---------------

Once the wrapper has been instantiated, query calls are pretty straight forward:

```php
$basecamp->getCompanies(); // Returns all companies
$basecamp->getFiles(PROJECT_ID); // Returns all the files from project with matched ID
$basecamp->getUsers(); // Returns all of the user's projects
$basecamp->getProjects(); // Returns all of the user's projects
$basecamp->getProject(PROJECT_ID); // Returns a single project with matched ID
$basecamp->getTodoItems(TODO_LIST_ID); // Returns all items for a given todo list
$basecamp->getTodoLists(); // Returns all todo lists the user has access to
```

Loop through all of the user's projects:

```php
foreach($basecamp->getProjects() as $project):
	echo $project->name;
	echo $project->id;
	echo $project->status;
endforeach;
```