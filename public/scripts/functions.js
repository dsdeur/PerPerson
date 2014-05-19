function getCountries(callback) {
	var request = $.getJSON("../php/api.php?key=570938f00c1f30c59530b15fc930dd0f&format=json")
	//var request = $.getJSON("http://localhost/perperson/php/api.php")
	.done(function(data){
		var countriesArray = [];

		$.each(data, function(i, item){
			var name = item["name"];
			var countryCode = item["country_code"];
			var alpha2 = item["alpha_2"];
			var alpha3 = item["alpha_3"];
			var countryData = item["data"];

			if(Object.keys(countryData).length > 5) {
				var country = new Country(name, alpha2, alpha3, countryCode);
				
				$.each(countryData, function(j, dataItem) {
					var name = dataItem["name"];
					var value = parseFloat(dataItem["value"]);
					var unit = dataItem["unit"];

					var dataObj = new Data(name, value, unit);

					country.addData(dataObj);
				});

				countriesArray[countryCode] = country;
			}
		});

		callback(countriesArray);
	})
	.fail(function(){
		callback(false);
	});
}

function onSelectChange(self) {
	var index = self.parent().index();

	$.each($(".data"), function(){
		$(".content .item", this).eq(index).empty();
	});

	var countryCode = parseInt(self.find("option:selected").attr("value"), 10);
	var country = world.countries[countryCode];

	// Changing select in title ----------------------------
	var title = self.parent().find(".countryTitle");
	var img = '<img src="images/country-flags/'+ country.alpha2.toLowerCase() +'.png" height="30" />';
	title.find("h1").html(img + country.name);
	title.find(".populationTotal").empty();

	self.hide(200, function(){
		counter(country.data[3].value, title.find(".populationTotal"), 2000);
		title.fadeIn(500, "easeOutElastic");
	});

	makeGraph(
		country.getPopulation(),
		country.data[0].value * 1000,
		$(".dataSquareMeter .content .item").eq(index),
		"squareMeter"
	);
	makeGraph(country.getPopulation(),
		(country.data[1].value /1000)* country.getPopulation(),
		$(".dataCar .content .item").eq(index),
		"car"
	);
	makeGraph(country.getPopulation(),
		(country.data[2].value / 100) * country.getPopulation(),
		$(".dataLightbulb .content .item").eq(index),
		"lightbulb"
	);
	makeGraph(country.getPopulation(),
		country.data[5].value * country.getPopulation(),
		$(".dataWater .content .item").eq(index),
		"water"
	);
}

function easeOutQuint(t, b, c, d) {
	return c*((t=t/d-1)*t*t*t*t + 1) + b;
}

function splitNumbers(nr) {
	var digits = ("" + nr).split("");
	var digitsHtml = "";

	for(var i in digits) {
		digitsHtml += "<span>" + digits[i] + "</span>";
	}

	return digitsHtml;
}

function counter(nr, destinationObj, duration, dec, type, callback) {
	var count = 0;
	var div = $("<div/>").addClass("digits");

	destinationObj.append(div);

	var text = "<div class='text'><div class='texttext'>";

	switch(type) {
		case "squareMeter":
			text += "1000 Square meter land area";
			break;
		case "car":
			text += "Motorized vehicles";
			break;
		case "lightbulb":
			text += "100 kWh Electricity per year";
			break;
		case "water":
			text += "Billion cubic meters water per year";
			break;
	}

	text += " / </div><div class='iconPerson'></div></div>";

	var textDiv = $(text);

	var startTime;
	function updateCount(currentTime) {
		if(count < nr) {
			if(dec) {
				count = Math.round(easeOutQuint(currentTime - startTime, 0, nr, duration)* 100) / 100;
			} else {
				count = Math.round(easeOutQuint(currentTime - startTime, 0, nr, duration));			
			}
			digitsHtml = splitNumbers(count);

			div.empty();

			if(dec) {
				div.append(digitsHtml, textDiv);
			} else {
				div.append(digitsHtml);
			}

			return true;
		} else {
			digitsHtml = splitNumbers(nr);
			div.empty();

			if(dec) {
				div.append(digitsHtml, textDiv);
			} else {
				div.append(digitsHtml);
			}

			if(callback !== undefined) {
				callback();
			}

			return false;
		}
	}

	startTime = (new Date()).getTime();
	animation.addAnimation(updateCount);
}

function makeGraph(totalPopulation, value, parent, type) {
	var pp = value / totalPopulation;
	var remainder = pp % 1;
	var nrOfIcons = pp - remainder;
	var lastPerc = Math.round(remainder * 100);

	counter(Math.round(pp * 100) / 100, parent, 5000, true, type);
	
	var timeout = 5000/nrOfIcons;

	for(var i = 0; i < nrOfIcons; i++) {
		setTimeout(function(){
			var icon = new Icon(100, type, parent);
		}, (i * timeout));
	}

	setTimeout(function(){
		if(lastPerc > 0 || nrOfIcons === 0) {
			icon = new Icon(lastPerc, type, parent);
		}
	}, (nrOfIcons * timeout));
}


function addItem(select, headerContent) {
	var background = $("<div/>").addClass("itembackground");
	background.hide();
	$("#backgrounds").append(background);
	background.fadeIn(500, 'easeOutQuint');

	var header = $("<header/>");
	header.html(headerContent);
	var newSelect = select.clone();

	header.find(".countryTitle").click(function(event){
		$(this).fadeOut(200, function(){
			$(this).parent().find(".countrySelect").show(200).trigger("click");
		});
	});

	newSelect.change(function(event){
		onSelectChange($(this));
	});
	
	header.append(newSelect);

	$(".headers").append(header);

	$(".content").each(function(){
		var item = $("<div/>").addClass("item");
		$(this).append(item);
	});
}