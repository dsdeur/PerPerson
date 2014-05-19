var Data = function(name, value, unit, valuePP) {
	this.name = name;
	this.value = value;
	this.unit = unit;
	this.valuePP = valuePP;
};


var Country = function(name, alpha2, alpha3, countryCode) {
	this.countryCode = countryCode;
	this.alpha2 = alpha2;
	this.alpha3 = alpha3;
	this.name = name;
	this.data = [];

	this.addData = function(data){
		this.data.push(data);
	};

	this.getPopulation = function() {
		return this.data[3].value;
	};
};


var World = function(countries) {
	this.countries = countries;

	this.countryArray = function() {
		return this.countries;
	};

	this.getCountryByName = function(countryName) {
		for(var country in this.countries) {
			if (country.name == countryData){
				return country;
			}
		}
	};
};


var Animation = function() {
	this.animations = [];
	this.animating = false;

	window.requestAnimFrame = (function(callback) {
		return window.requestAnimationFrame ||
					window.webkitRequestAnimationFrame ||
					window.mozRequestAnimationFrame ||
					window.oRequestAnimationFrame ||
					window.msRequestAnimationFrame ||
					function(callback) {
					window.setTimeout(callback, 1000 / 60);
				};
	})();

	this.looper = function() {
		var currentTime = (new Date()).getTime();
		this.animating = true;
		//console.log(this.animating);

		for (var i in this.animations) {
			var result = this.animations[i](currentTime);

			if(!result) {
				this.animations.splice(i, 1);
			}
		}
		
		if(this.animations.length > 0) {
			var self = this;

			requestAnimFrame(function() {
				self.looper();
			});
		} else {
			this.animating = false;
		}
	};

	this.addAnimation = function(animationFunction, startTime) {
		this.animations.push(animationFunction);

		if(!this.animating) {
			this.looper();
		}
	};
};

var Icon = function(perc, type, parent, duration) {
	var self = this;
	this.container = $("<div/>").addClass("icon").addClass(type);
	this.background = $("<div/>").addClass("iconBG").css({display:"none"});
	this.percLayer = $("<div/>").addClass("iconPerc");


	this.container.append(this.background, this.percLayer);
	parent.append(this.container);

	this.updatePerc = function(perc) {
		this.percLayer.animate({
			width: perc + "%"
		}, duration, 'easeOutQuint');
	};

	this.background.fadeIn(100, function(){
		self.updatePerc(perc);
	});
};

var animation = new Animation();
