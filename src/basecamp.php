<?php

/**
 * Basecamp Class API Wrapper for PHP
 *
 * Simple library of methods to interact with the Basecamp Classic API.
 * https://github.com/37signals/basecamp-classic-api
 *
 * @category   Library
 * @package    Basecamp API Wrapper
 * @author     Purple Rock Scissors <http://www.prpl.rs>
 * @author     Justin Gillespie <justin@prpl.rs>
 * @link       https://github.com/prplrs/basecamp-php
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    0.1
 *
 * Getting Started:
 *
 * Include the library into your project and instantiate the Basecamp class.
 * Make sure to pass it either your api key as a string, or an array of
 * authentication credentials.
 *
 * Example PHP Usage:
 *
 * $auth = array(
 *     "account"  => "YOUR_ACCOUNT",
 *     "api_key"  => "YOUR_API_KEY",
 *     "user"     => "YOUR_USERNAME",
 *     "password" => "YOUR-PASSWORD"
 * );
 * 
 * $basecamp = new Basecamp($auth);
 * $basecamp->getProjects(); // Returns all of the user's projects
 *
 */

class Basecamp {

	private $api_key;
	private $user;
	private $password;
	private $account;	
	private $url;

	/**
	 * Class constructor method sets authentication credentials.
	 *
	 * @access  public
	 * @param   array/string
	 * @return  null
	 */

	public function __construct($settings) {

		// For developer convenience the constructor method accepts either 
		// authentication elements via an array, or a simple string containing 
		// only the basecamp api key.
		//
		// If using the api key only, you'll need to use the authentication
		// setter methods after you have instantiated the class object.
		//
		// Example:
		//
		// $basecamp = new Basecamp(YOUR_API_KEY);
		// $basecamp -> setAccount(YOUR_ACCOUNT);
		// $basecamp -> setUser(YOUR_USERNAME);
		// $basecamp -> setPassword(YOUR_PASSWORD);
		//
		// These methods are also chainable:
		//
		// $basecamp->setAccount()->setUser()->setPassword();

		if( is_array($settings) ) {
			$this->setApiKey($settings['api_key']);
			$this->setAccount($settings['account']);
			$this->setUser($settings['user']);
			$this->setPassword($settings['password']);
		} else {
			$this->setApiKey($settings);
		}

	}

	/**
	 * Setter method for basecamp API key (located on the user settings page).
	 *
	 * @access  public
	 * @param	Basecamp API key (string)
	 * @return  class object (chainable)
	 */

	public function setApiKey($api_key) {
		$this->api_key = $api_key;
		return $this;
	}

	/**
	 * Setter method for basecamp account slug.
	 *
	 * @access  public
	 * @param	Basecamp account slug (string)
	 * @return  class object (chainable)
	 */

	public function setAccount($account) {
		$this->account = $account;
		$this->setURL($account);
		return $this;
	}

	/**
	 * Setter method for basecamp profile username.
	 *
	 * @access  public
	 * @param	Basecamp profile username (string)
	 * @return  class object (chainable)
	 */

	public function setUser($user) {
		$this->user = $user;
		return $this;
	}

	/**
	 * Setter method for basecamp profile password.
	 *
	 * @access  public
	 * @param	Basecamp profile password (string)
	 * @return  class object (chainable)
	 */

	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}

	/**
	 * Setter method for API URL.
	 *
	 * @access  public
	 * @param	Basecamp account slug (string) (optional)
	 * @return  class object (chainable)
	 */

	public function setURL($account = null) {
		$account = isset($account) ? $account : $this->account;
		$this->url = 'https://' . $account . '.basecamphq.com/';
		return $this;
	}
	
	/**
	 * Getter method for API URL.
	 *
	 * @access  public
	 * @return  string
	 */

	public function getURL() {
		return $this->url;
	}

	/**
	 * Gets projects for currently authenticated user
	 *
	 * @access  public
	 * @param	Basecamp Project ID (Integer)
	 * @param	Pagination Offset (Integer) (optional)
	 * @return  object
	 */

	public function getFiles($project_id, $offset = null) {
		return $this->request('projects/' . $project_id . '/attachments.xml?n=' . $offset);
	}

	/**
	 * Gets projects for currently authenticated user
	 *
	 * @access  public
	 * @return  object
	 */

	public function getProjects() {
		return $this->request('projects.xml');
	}

	/**
	 * Gets a single project by ID
	 *
	 * @access  public
	 * @param	Basecamp Project ID (Integer)
	 * @return  object
	 */

	public function getProject($id) {
		return $this->request('projects/' . $id . '.xml');
	}

	/**
	 * Returns all people visible to (and including) the requesting user.
	 *
	 * @access  public
	 * @param	Basecamp Project ID (Integer)
	 * @return  object
	 */

	public function getUsers() {
		return $this->request('people.xml');
	}

	/**
	 * Checks for authentication credentials.
	 *
	 * @access  private
	 * @return  boolean
	 */

	private function isAuthenticated() {
		$result = ($this->account == null || $this->user == null || $this->password == null) ? false : true;
		return $result;
	}

	/**
	 * Sets up and sends the GET request to a basecamp xml file. 
	 *
	 * @access  private
	 * @return  object
	 */

	private function request($path) {

		// Set up and send a cURL request.
		// For more information: http://php.net/manual/en/book.curl.php

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $this->url . $path);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($curl, CURLOPT_HTTPGET, 1);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  
		curl_setopt($curl, CURLOPT_USERPWD, $this->user . ":" . $this->password);
 	
 		// Error Handling
		// Check for the nessessary authentication credientials.
 		
 		// No authentication data.
 		
 		if(!$this->isAuthenticated()) throw new Exception('Authentication Failed');

		// cURL connection or formatting error.

		if(curl_error($curl)) throw new Exception(curl_error($curl));

 		// Convert response to XML and close the connection
 		// TODO: Seperate the Curl Request and Simple XML Object creation.

		$xml = new SimpleXMLElement(curl_exec($curl));
		
 		// Close the cURL connection
		
		curl_close($curl);
		return $xml;
	}

} // end Basecamp Class

?>