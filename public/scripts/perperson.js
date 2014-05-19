var world;

$(document).ready(function(){
	var numberOfItems = 0;

	var select = $("<select/>").addClass("countrySelect");
	var headerContent = '<div class="countryTitle"><h1>CountryName</h1><div class="populationTotal"></div><div class="iconPerson"></div></div>';

	getCountries(function(countries){
		world = new World(countries);

		countries = world.countryArray();


		for (var index in countries) {
			option = $("<option/>").attr("value", countries[index].countryCode).text(countries[index].name);
			select.append(option);
		}

		var options = select.children('option');
		options.sort(function (optionA, optionB) {
			return optionA.text.localeCompare(optionB.text);
		});
		select.empty().append(options);

		var option = $("<option/>").text("Select country...");
		select.prepend(option);
	});

	$('.data').each(function(){
		$(this).find('.iconContainer:last').css({
			float:"right"
		});
	});

	$("#new").click(function(event){
		event.preventDefault();

		numberOfItems++;


		$.when($("#main").animate({
				width: numberOfItems * 680 + 192
		})).then(function(){
			$(".content, .headers").css({
				width: numberOfItems * 680 + 96
			});
			addItem(select, headerContent);
		});
	});

	$("#main").on("resize", function(){
		var height = $(this).height();
		var width = $(this).width();

		$("#backgrounds").css({
			height:height,
			width:width
		});

		$(".itembackground").css({
			height:height
		});
	});

/*	$("body").mousewheel(function(event, delta) {
		this.scrollLeft -= (delta * 30);
		event.preventDefault();
	});*/
});