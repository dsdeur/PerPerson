<?php 
	// Login not required
	$loginRequired = false;
	require_once("../inc/config.php");

	// Set error var
	$error = "";

	// Update the api call counter
	updateApiStats();

	// Check if the format is provided
	if(isset($_GET["format"])) {
		// Test the input and lower the string
		$format = strtolower(test_input($_GET["format"]));
			
		// Check if the format is xml or json, else add an error
		if($format != "xml" && $format != "json") {
			$error .= "Verkeerd format moet xml of json zijn <br>";
		}
	} else {
		// If no format is provided add an error
		$error .= "Geen format moet xml of json zijn <br>";
	}

	// Check if an api keys i provided
	if(isset($_GET["key"])) {
		// Test the key
		$key = test_input($_GET["key"]);

		// Check if the key is valid else add error
		if(!$db->checkKey($key)) {
			$error .= "Key is niet correct <br>";
		} 
	} else {
		// Add error if no key is provided
		$error .= "Er moet een api key meegegeven worden<br>";	
	}

	/** 
	 * Create xml
	 * @param array $countries, array of country data
	 *
	 * @return xml of countries
	 */	
	function createXML($countries) {
		$xml = new SimpleXMLElement('<countries></countries>');
		foreach($countries as $countryobj) {
			$country = $xml->addChild('country');	
			
			foreach($countryobj as $key=>$value) {
				
				if(is_array($value)){
					$datasets = $country->addChild('datasets');
					foreach($value as $dataentry) {
						$data = $datasets->addChild('data');

						foreach($dataentry as $datakey=>$datavalue){
							$data->addChild($datakey, $datavalue);	
						}
					}	

				} else {
					$country->addChild($key, $value);
				} 
			}
		}
		 
		return $xml->asXML();
	}

	// Check if there are no errors
	if($error == "") {
		// Get the data fromt he db
		$data = $db->getData();		

		// Echo the formatted data
		if($format == "xml") {
			header('Content-type: text/xml');
			echo createXML($data);
		} else {
			header('Content-Type: application/json');
			echo json_encode($data);	
		}
	} else {
		// If there's an error echo it with an example
		echo $error;
		echo "Voorbeeld: api.php?key=570938f00c1f30c59530b15fc930dd0f&format=json<br><br>";
		?>

		<h1>API documentatie</h1>

		<p>Als eerste moet je een api key hebben. Deze kun je aanvragen door je eerst <a href="../admin/login.php">hier</a> te
		registreren, en daarna een nieuwe key aan te maken.</p>

		<p>De parameters voor de api zijn:</p>
		<ul>
		<li>key: je api key</li> 
		<li>format: het formaat dat je als output wilt, xml of json</li>
		</ul>
		<p>Beide parameters moeten meegegeven worden om resultaat te krijgen.</p> 
		<p>Een voorbeeld url is:</p>
		<p>/api.php?key=570938f00c1f30c59530b15fc930dd0f&format=json</p>
		<p>Een land uitvoer hiervan is:</p>
		<pre><code>
[{
	"country_code": 4,
	"name": "Afghanistan",
	"alpha_2": "AF",
	"alpha_3": "AFG",
	"data": {
		"16": {
			"name": "Land Area",
			"value": 652230,
			"unit": "m2"
		},
		"89": {
			"name": "Motorvehicles",
			"value": 27,
			"unit": "per 1000 people"
		},
		"90": {
			"name": "Electric power consumtion",
			"value": 0,
			"unit": "kWh per capita"
		},
		"94": {
			"name": "Population total",
			"value": 29824536,
			"unit": "people"
		},
		"96": {
			"name": "Water withdrawals total",
			"value": 20,
			"unit": "Billion cubicmeters"
		},
		"102": {
			"name": "Water withdrawals domestic",
			"value": 0,
			"unit": "perc of total"
		}
	}
},
....
....
....
}]	

		</code></pre>

		<p>Een voorbeeld met xml is is:</p>
		<p>/api.php?key=570938f00c1f30c59530b15fc930dd0f&format=xml</p>
		<p>Een land uitvoer hiervan is:</p>

		<pre><code>
 &lt;countries&gt;
	 &lt;country&gt;
		 &lt;country_code&gt;4 &lt;/country_code&gt;
		 &lt;name&gt;Afghanistan &lt;/name&gt;
		 &lt;alpha_2&gt;AF &lt;/alpha_2&gt;
		 &lt;alpha_3&gt;AFG &lt;/alpha_3&gt;
		 &lt;datasets&gt;
			 &lt;data&gt;
				 &lt;name&gt;Land Area &lt;/name&gt;
				 &lt;value&gt;652230 &lt;/value&gt;
				 &lt;unit&gt;m2 &lt;/unit&gt;
			 &lt;/data&gt;
			 &lt;data&gt;
				 &lt;name&gt;Motorvehicles &lt;/name&gt;
				 &lt;value&gt;27 &lt;/value&gt;
				 &lt;unit&gt;per 1000 people &lt;/unit&gt;
			 &lt;/data&gt;
			 &lt;data&gt;
				 &lt;name&gt;Electric power consumtion &lt;/name&gt;
				 &lt;value&gt;0 &lt;/value&gt;
				 &lt;unit&gt;kWh per capita &lt;/unit&gt;
			 &lt;/data&gt;
			 &lt;data&gt;
				 &lt;name&gt;Population total &lt;/name&gt;
				 &lt;value&gt;29824536 &lt;/value&gt;
				 &lt;unit&gt;people &lt;/unit&gt;
			 &lt;/data&gt;
			 &lt;data&gt;
				 &lt;name&gt;Water withdrawals total &lt;/name&gt;
				 &lt;value&gt;20 &lt;/value&gt;
				 &lt;unit&gt;Billion cubicmeters &lt;/unit&gt;
			 &lt;/data&gt;
			 &lt;data&gt;
				 &lt;name&gt;Water withdrawals domestic &lt;/name&gt;
				 &lt;value&gt;0 &lt;/value&gt;
				 &lt;unit&gt;perc of total &lt;/unit&gt;
			 &lt;/data&gt;
		 &lt;/datasets&gt;
	 &lt;/country&gt;	
 &lt;/countries&gt;		
		</code></pre>
		<?php
	}
?>