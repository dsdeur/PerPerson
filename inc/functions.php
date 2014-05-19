<?php
	/** 
	 * Test input for tags slashes and special chars, prevent html injection
	 * @param string $data, the string to test
	 *
	 * @return string, the clean string
	 */	
	function test_input($data) {
		$data = strip_tags($data);
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}

	/** 
	 * Update countries, insert or update countries
	 * @param array $alpha2, json array from alpha2 json
	 * @param array $alpha3, json array from alpha3 json
	 */	
	function updateCountries($alpha2, $alpha3) {
		// Get the database object
		global $db;

		$countries = $alpha2;

		// Loop through all countries in alpha2 array
		foreach($countries as $key=>$value) {
			// Get the country_code (id)
			$country_code = $value["country-code"];

			// Get the alpha3 code for the current country
			foreach($alpha3 as $country) {
				if($country_code == $country["country-code"]){
					// Add the alpha 3 to the countries array
					$countries[$key]["alpha-3"] = $country["alpha-3"];
				}
			}

			// convert the country code to int
			$countries[$key]["country-code"] = intval($country_code);
		}

		// Inset the country array in the database
		$db->insertCountries($countries);
	}

	/** 
	 * Insert a csv dataset
	 * @param string $path, the path to the csv file
	 * @param string $name, the name of the new dataset
	 * @param string $unit, the unit for the data
	 */	
	function insertDataset($path, $name, $unit) {
		global $db;

		// Create the dataset and get the id
		$datasetID = $db->createDataset($name, $unit);

		// Create the array for the data
		$data = array();

		// Open the file and skip the headerrows
		$handle = fopen($path, "r");
		$firstRowd = fgetcsv($handle, 0, ",");
		$firstRowd = fgetcsv($handle, 0, ",");
		$firstRowd = fgetcsv($handle, 0, ",");

		// Loop through csv rows
		while($row = fgetcsv($handle, 0, ",")) {
			$entry = array();

			// Add the country code for each data row
			$entry["country-code"] = $db->getCountryCode($row[1]);

			// Reverse the row to get the newest data (latest year)
			$reverseRow = array_reverse($row);

			// Set the value to ""
			$valueRaw = '';

			// Loop through the data to find a value
			// This prevent empty data
			foreach($reverseRow as $value) {
				if($value != ''){
					$valueRaw = $value;
					$entry["value"] = intval($value);

					// If value is found break out of the loop
					break;
				}
			}	

			// A country code and a value is provided add it to the data array
			if($entry["country-code"] != null && $valueRaw != '') {
				$data[] = $entry;	
			}
		}

		// Insert the data in the database
		$db->insertData($data, $datasetID);
	}	

	/** 
	 * Validate an email adres with regex
	 * @param string $email, email adress to check
	 *
	 * @return boolean true if email
	 */	
	function validate_email($email) {
		$pattern = "#^[.0-9a-z-]+@[.0-9a-z-]+\.[a-z]+#";

		if(preg_match($pattern, $email)) {
			return true;
		} else {
			return false;
		}
	}


	function getApiStats() {
		// Set $counter en $filepath variabelen
		$filepath = "../" . COUNTER_PATH;
		$count = 0;

		// Check of het bestand al bestaat
		if(file_exists($filepath)) {
		    // Open het bestand in read modus als het bestaat.
		    $file = fopen($filepath, "r");

		    // Haal de inhoud van het bestand op.
		    $count = intval(fread($file, filesize($filepath)));
		    // Verhoog de waarde met 1

		    // Sluit de file
		    fclose($file);
		} else {
		    // Als het bestand niet bestaat zet de counter op 1
		    $count = 0;
		}

		return $count;
	}

	/** 
	 * Update the API stats file
	 * Adds one to the call count in the txt file
	 */	
	function updateApiStats() {
		$filepath = "../" . COUNTER_PATH;
		$count = 0;

		// Check of het bestand al bestaat
		if(file_exists($filepath)) {
		    $count = getApiStats() + 1;

		} else {
		    // Als het bestand niet bestaat zet de counter op 1
		    $count = 1;
		}

		// Open de file in write modus
		$file = fopen($filepath, "w");
		// Write de nieuwe waarde naar de file
		fwrite($file, $count);
		// Close de file
		fclose($file);
	}

	/** 
	 * Get the wordbank rss feed
	 * @param int $nr, number of entries
	 *
	 * @output the rss html
	 */	
	function getWorldbankFeed($nr) {
		$xml=simplexml_load_file("http://wbws.worldbank.org/feeds/xml/eca_all.xml");

		if($xml === false) {
			echo "Geen feed gevonden...";
		} else {
			echo "<h1>" . $xml->subtitle . "</h1>" ;
			//echo "Pubdate: " . date('d-m-Y H:i', strtotime($xml->children('wbfeed', true)->date));

			$count = 0;
			foreach($xml->entry as $key=>$item) {
				$count++;
				if($count > $nr) {
					break;
				}

				echo "<article>";
				echo "<h2>" . "<a href='" . $item->link["href"] . "'>". $item->title  ."</a>" . "</h2>";
				echo "<small> Publicatiedatum: " . date('d-m-Y H:i', strtotime($item->updated)) . "</small>";
				echo "<p>" . substr(test_input($item->summary), 0, 200) . "...</p>";
				echo "</article>\n\n";
			}


		}
	}

	/** 
	 * Get flickr photos
	 * 
	 */
	function getFlickr() {
		$key = "62028f27f080285ae6f5c4ebabd4bf46";
		$secret = "6111c9bb4c9dd548";
		$tags = "world%2Cdata&has_geo=1";

		$apiPath = "http://api.flickr.com/services/rest/?method=flickr.photos.search&api_key=$key&tags=$tags&format=json";
		$flickrContents = file_get_contents($apiPath);

		$flickrContents = str_replace( 'jsonFlickrApi(', '', $flickrContents );
		$flickrContents = substr( $flickrContents, 0, strlen( $flickrContents ) - 1 ); //strip out last paren

		$flickrJson = json_decode($flickrContents);

		foreach($flickrJson->photos->photo as $photo) {
			$server = $photo->server;
			$farm = $photo->farm;
			$secret = $photo->secret;
			$id = $photo->id;

			echo "<img src='http://farm". $farm .".staticflickr.com/". $server ."/". $id ."_". $secret ."_s.jpg' />";
		}
	}
?>