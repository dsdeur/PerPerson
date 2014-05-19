<section id="adminCounter">
	<h1><?php 
	// Get the total api calls
	echo getApiStats(); ?></h1>
	<p>Total API calls</p>
</section>

<section id="dashFeed">
	<?php 
	// Get the feed
	getWorldbankFeed(5); ?>
</section>

<section id="dashFlickr">
	<h1>Flickr photos tagged "world" or "data"</h1>
	<?php 
	// Load the flickr photos
	getFlickr();?>
</section>

<section id="testrapport">
	<h1>Testrapport</h1>
	<h2>Valide</h2>
	<p>Alle pagina's zijn valide HTML5.</p>
	<h2>Beveiliging</h2>
	<ul>
		<li>De passwoorden zijn encrypted met bcrypt, een zeer zware versleuteling.</li>
		<li>Het email adres bij zowel de login als signup wordt met regular expressions getest</li>
		<li>Je kunt 5 keer proberen in te loggen, daarna moet je 30 seconden wachten, dit om brute force attacks te voorkomen.</li>
		<li>Alle input via post of get wordt gestript van html en speciale karakters, dit voorkomt html injection</li>
		<li>Alle invoer in de database gaat via prepared queries, dit betekent dat mysql injection niet mogelijk is.</li>
		<li>Via de config die op bijna iedere pagina geinclude is, wordt gechecked of er ingelogd is. Default moet dit, tenzij met een variabele anders aangegeven.</li>
		<li>Het deleten van een key, gaat aan de hand van de ingelogde user, alleen de admin kan keys van andere deleten</li>
	</ul>

	<h2>Error messages</h2>
	<p>Dit gebeurt iets minder, als de admin een verkeerde file upload gebeurd er niks, er gaat niks mis maar er komt geen boodschap.</p>
	<p>Bij de gebruiker en de login gebeurt dit wel</p>
</section>