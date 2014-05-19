<?php

Class Database {
	private $result = array(); // Any results from a query will be stored here
	private $myQuery = "";// used for debugging process with SQL return
	private $numResults = "";// used for returning the number of rows
	private $mysqli = false;


  	/** 
     * Connect the database
   	 * @param array $config, the database connecting info 
	 *
	 * @return boolean, true if succes false if error
   	 */
	public function connect($config) {
		// Check if connection exists
		if(!$this->mysqli){
			$this->mysqli = new mysqli($config['db']['host'], 
							   $config['db']['username'], 
							   $config['db']['password'], 
							   $config['db']['dbname']);
			if(mysqli_connect_error()) {
				echo mysqli_connect_error();
				return false;
			} else {
				return true;
			}
		} else {
			// Connected no need to connect again
			return true;
		}
	}

	/** 
	 * Check login
	 * @param string $email, for checking if email in db
	 *
	 * @return array, user info including encrypted password for verifying
	 */	
	public function checkLogin($email) {
		$stmt = $this->mysqli->prepare("SELECT userid, password, admin FROM user WHERE email = ?");
		
		$stmt->bind_param('s', $email);

		if(!$stmt->execute()) {
			return false;
		} else {
			$stmt->store_result();
			$stmt->bind_result($userid, $password, $admin);
			$res = $stmt-> fetch();

			if ($stmt->num_rows > 0) {
				$stmt->close();

				$user = [];
				$user["password"] = $password;
				$user["admin"] = $admin;
				$user["id"] = $userid;
				
				return $user;                
			}
		}

		$stmt->close();

		return null;
	}

	/** 
	 * Check email
	 * @param string $email, for checking if email in db
	 *
	 * @return boolean 
	 */	
	public function checkEmailExists($email) {
		$stmt = $this->mysqli->prepare("SELECT userid, email FROM `user` WHERE `email` = ?");
		
		$stmt->bind_param('s', $email);

		if(!$stmt->execute()) {
			return false;
		} else {
			$stmt->store_result();
			$stmt->bind_result($userid, $email);
			$res = $stmt-> fetch();

			if ($stmt->num_rows > 0) {
				return false;     
			} else {
				return true;
			}
		}

		$stmt->close();

		return false;
	}

	/** 
	 * Add a user
	 * @param string $email, email
	 * @param string $password, encrypted password
	 */	
	public function addUser($email, $password) {
		$stmt = $this->mysqli->prepare("INSERT into user(`email`, `password`) VALUES(?,?)");
		
		$stmt->bind_param('ss', $email, $password);

		if(!$stmt->execute()) {
			error_log('DB ERROR: createDataset '.$stmt->error);
			$stmt->close();
		} else {		
			try {			
				return $stmt->insert_id;
			} catch(Exception $e) {
				echo $e;			
			}

			$stmt->close();
		}
	}

	/** 
	 * Get country code by providing alpha 3
	 * @param string $alpha3, the alpha3 code
	 *
	 * @return int country code, (primary key) 
	 */	
	public function getCountryCode($alpha3) {
		$stmt = $this->mysqli->prepare("SELECT country_code FROM country WHERE alpha_3 = ?");
		
		$stmt->bind_param('s', $alpha3);

		// Execute the query
		if(!$stmt->execute())
		{
			return false;
		}
		else {
			// Get the result
			$result = $stmt->get_result();

			// Create the user assoc array
			$country = $result->fetch_array(MYSQLI_ASSOC);
			$country_code = $country['country_code'];
			// Close the db
			$stmt->close();

			// Return the user array
			return $country_code;
		}
	}

	/** 
	 * Create a dataset
	 * @param string $name, name
	 * @param string $unit, unit
	 *
	 * @return int insert_id 
	 */	
	public function createDataset($name, $unit) {
		$stmt = $this->mysqli->prepare("INSERT INTO dataset (name,unit) VALUES(?,?) ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)");
	   
		$stmt->bind_param('ss', $name, $unit);
		
		if(!$stmt->execute()) {
			error_log('DB ERROR: createDataset '.$stmt->error);
			$stmt->close();
		} else {		
			try {			
				return $stmt->insert_id;
			} catch(Exception $e) {
				echo $e;			
			}

			$stmt->close();
		}
	}

	/** 
	 * Insert data
	 * @param array $data, the actual data per country
	 * @param array $datasetID, the id of the dataset
	 *
	 * @return boolean true if success
	 */	
	public function insertData($data, $datasetID) {
		$query = "REPLACE INTO `data` (`country_code`, `dataset_id`, `data`) VALUES";

		$format = " (%d, %d, %d),";

		// Go over each array item and append it to the SQL query
		foreach($data as $entry) {
			$query .= sprintf(
				$format,
				$entry['country-code'],
				$datasetID,
				$entry['value']
			);
		}
		// The last VALUES tuple has a trailing comma which will cause
		// problems, so let us remove it
		$query = rtrim($query, ',');

		$stmt = $this->mysqli->prepare($query);

		if(!$stmt->execute()) {
			error_log('DB ERROR: addItem '.$stmt->error);
			$stmt->close();
		} else {		
			$stmt->close();
			return true;
		}
	}

	/** 
	 * Insert countries
	 * @param array $countries, array of country data
	 *
	 * @return boolean true if success
	 */	
	public function insertCountries($countries) {
		$query = "REPLACE INTO `country` (`country_code`, `name`, `alpha_2`, `alpha_3`) VALUES";

		$format = " (%d, '%s', '%s', '%s'),";

		// Go over each array item and append it to the SQL query
		foreach($countries as $country) {
			$query .= sprintf(
				$format,
				$country['country-code'],
				$this->mysqli->escape_string($country['name']),
				$this->mysqli->escape_string($country['alpha-2']),
				$this->mysqli->escape_string($country['alpha-3'])
			);
		}
		// The last VALUES tuple has a trailing comma which will cause
		// problems, so let us remove it
		$query = rtrim($query, ',');

		$stmt = $this->mysqli->prepare($query);

		if(!$stmt->execute()) {
			error_log('DB ERROR: addItem '.$stmt->error);
			var_dump($stmt->error);
			$stmt->close();
		} else {		
			$stmt->close();
			return true;
		}
	}

	// Private function to check if table exists for use with queries
	private function tableExists($table){
		$stmt = $this->mysqli->prepare('SHOW TABLES FROM '. $config['db']['dbname'] .' LIKE "'. $table .'"');
		
		if(!$stmt->execute()){
			return $stmt->error;
		} else {
			$stmt->store_result();

			if($stmt->num_rows == 1) {
				return true;
			} else {
				return false;
			}
		}

		$stmt->close();
	}
	

	/** 
	 * Get all data, countries with dataset and data
	 *
	 * @return assoc array ready for json encoding
	 */	
	public function getData() {
		$query = "SELECT * FROM country";
		$stmt = $this->mysqli->prepare ($query);

		// Execute the query
		if(!$stmt->execute())
		{   
			// If fails return false;
			return false;
		}
		else {
			// Get the result
			$result = $stmt->get_result();

			// Create the base assoc array and create the top element
			$data = array();

			// Put all rows in array
			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				// Get the userinfo by user id, and put it in row array
				$row["data"] = $this->getDataByCountryCode($row["country_code"]);

				// Add row to array
				array_push($data, $row);
			}

			// Close db
			$stmt->close();

			// Return the reactions array
			return $data;
		}
	}

	/** 
	 * Get data by country
	 * @param int $country_code, id of country
	 *
	 * @return array of datasets with data
	 */	
	public function getDataByCountryCode($country_code) {
		$query = "SELECT data, name, id, unit FROM data, dataset WHERE data.dataset_id = dataset.id AND country_code = ?";
		$stmt = $this->mysqli->prepare ($query);
		
		$stmt->bind_param("i", $country_code);

		// Execute the query
		if(!$stmt->execute())
		{   
			// If fails return false;
			return false;
		}
		else {
			// Get the result
			$result = $stmt->get_result();

			// Create the base assoc array and create the top element
			$data = array();

			// Put all rows in array
			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				$id = $row['id'];
				$data["$id"] = array();
				$data["$id"]["name"] = $row["name"];
				$data["$id"]["value"] = $row["data"];
				$data["$id"]["unit"] = $row["unit"];
			}

			// Close db
			$stmt->close();

			// Return the reactions array
			return $data;
		}
	}


	/** 
	 * Get api keys
	 * @param int $userid, userid of requester, default false if admin gets all keys
	 *
	 * @return array of keys, if userid provided only from user, else all keys
	 */	
	public function getKeys($userid = false) {
		if($userid !== false) {
			$query = "SELECT * FROM apikey WHERE userid = ?";
			$stmt = $this->mysqli->prepare ($query);
			$stmt->bind_param("d", $userid);
		} else {
			$query = "SELECT * FROM apikey";
			$stmt = $this->mysqli->prepare ($query);
		}

		// Execute the query
		if(!$stmt->execute())
		{   
			// If fails return false;
			return false;
		}
		else {
			// Get the result
			$result = $stmt->get_result();

			// Create the base assoc array and create the top element
			$keys = array();

			// Put all rows in array
			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				array_push($keys, $row);
			}

			// Close db
			$stmt->close();

			// Return the reactions array
			return $keys;
		}
	}

	/** 
	 * Create a new api key
	 * @param int $userid, id of owner
	 * @param string $url, url or name provided by user
	 * @param string $key, the actual key
	 *
	 * @return int insert_id
	 */	
	public function newKey($userid, $url, $key) {
		$stmt = $this->mysqli->prepare("INSERT into `apikey`(`url`, `userid`, `key`) VALUES(?, ?, ?)");
		$stmt->bind_param('sds', $url, $userid, $key);

		if(!$stmt->execute()) {
			error_log('DB ERROR: createDataset '.$stmt->error);
			$stmt->close();
		} else {		
			try {			
				return $stmt->insert_id;
			} catch(Exception $e) {
				echo $e;			
			}

			$stmt->close();
		}
	}

	/** 
	 * Delete an api key
	 * @param int $userid, owner id for checking if the key belongs to deleter
	 * @param int $keyid, the id of the key
	 * @param boolean $is_admin, admins can delete every key
	 *
	 * @return boolean true if success
	 */	
	public function deleteKey($userid, $keyid, $isadmin=false) {
		if($isadmin) {
			$stmt = $this->mysqli->prepare("DELETE FROM `apikey` WHERE `keyid` = ?");
			$stmt->bind_param('d', $keyid);			
		} else {
			$stmt = $this->mysqli->prepare("DELETE FROM `apikey` WHERE `keyid` = ? AND `userid` = ?");
			$stmt->bind_param('dd', $keyid, $userid);			
		}

		if(!$stmt->execute()) {
			error_log('DB ERROR: createDataset '.$stmt->error);
			$stmt->close();
		} else {					
			return true;

			$stmt->close();
		}
	}

	/** 
	 * Check if key exists
	 * @param string $key, the key
	 *
	 * @return boolean, true if success
	 */	
	public function checkKey($key) {
		$stmt = $this->mysqli->prepare("SELECT `key` FROM `apikey` WHERE `key` = ?");
		$stmt->bind_param('s', $key);

		if(!$stmt->execute()) {
			return false;
		} else {
			$stmt->store_result();
			$stmt->bind_result($key);
			$res = $stmt-> fetch();

			if ($stmt->num_rows > 0) {
				return true;     
			} else {
				return false;
			}
		}

		$stmt->close();

		return false;
	}

	/** 
	 * Get from db, collect all data from provided table
	 * @param string $table, name of table
	 * @param string $extraQuery, for providing a WHERE
	 * @param string $extaType, the type of the extra parameter
	 * @param string $extaParam, for providing a extra parameter
	 *
	 * @return array of data
	 */	
	function getFromDB($table, $extraQuery="", $extraParamType="", $extraPara=null){
		$query = "SELECT * FROM $table " . $extraQuery;

		$stmt = $this->mysqli->prepare ($query);

		if($extraPara != null) {
			$stmt->bind_param($extraParamType, $extraPara);
		}

		// Execute the query
		if(!$stmt->execute())
		{   
			// If fails return false;
			return false;
		}
		else {
			// Get the result
			$result = $stmt->get_result();

			// Create the base assoc array and create the top element
			$data = array();

			// Put all rows in array
			while($row = $result->fetch_array(MYSQLI_ASSOC)) {
				array_push($data, $row);
			}

			// Close db
			$stmt->close();

			// Return the reactions array
			return $data;
		}
	}

	/** 
	 * Delete data
	 * @param string $table, the table name to delete from
	 * @param string $params, the WHERE
	 *
	 * @return boolean true if success
	 */	
	function delete($table, $params) {
		$query = "DELETE FROM $table WHERE $params";
		$stmt = $this->mysqli->prepare($query);
		if(!$stmt->execute()) {
			error_log('DB ERROR: createDataset '.$stmt->error);
			$stmt->close();
		} else {					
			return true;

			$stmt->close();
		}
	}

/*	function deleteUnparented($tablename) {

	}*/
}	

?>