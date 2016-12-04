var homeHandlebarObject = {
		templateScript : "#home-template",
		context : {	// Define data object
			sliderContent: [],
			sponsorHomeContent: [],
			titleSponsorHomeContent: []
		},
		compiledID : "#mainContentDiv"
	},
	galleryDir = "img/sliderHome/",
	sponsorDir = "img/sponsorHomeFix/",
	titleSponsorDir = "img/titleSponsorsFix/",
	fileextension = ".JPG",
	fileExtensionSponsor = ".png",

	homeView = Backbone.View.extend({
		el: "#body",
		events: {
		},
		initialize: function(){	// first function that is called when object of navbarView is made
			this.render();
		},
		render : function(){
			this.galleryAjax();
			this.sponsorAjax();
			this.titleSponsorAjax();
			this.compileHandlebarFunction();
		},
		compileHandlebarFunction : function(){
			compileHandlebar(homeHandlebarObject);
			var height = $(window).height();
				$(".bgimg-1").css("height",height);
				$('#sponsorHomeDiv').scrollbox({
			      direction: 'h',
			      switchItems: 1,
			      distance: 140
			    });

				initTimerHome = new timerHome();

				this.delegateEvents();
				$(".sponsorHomeLi").click(function(){
					initHomeView.displaySponsorPage();
				});

				$(".smooth-scroll").click(this.smoothScroll);
				// $(document).bind('click .sponsorHomeLi', this.displaySponsorPage);
		},
		galleryAjax: function(){
			var filename;
			for (var index = 2; index < 12; index++) { 
			    filename = galleryDir + index + fileextension;
	            homeHandlebarObject.context.sliderContent[index-2] = {};
	            homeHandlebarObject.context.sliderContent[index-2].src = filename;
	            homeHandlebarObject.context.sliderContent[index-2].dataSlideTo = index;
	            if(index == 2){
	            	homeHandlebarObject.context.sliderContent[index-2].active = "active";
	            }
			}
		},
		sponsorAjax: function(){
			var filename;
			for (var index = 0; index < 34; index++) { 
			    filename = sponsorDir + (index+1) + fileExtensionSponsor;
	            homeHandlebarObject.context.sponsorHomeContent[index] = {};
	            homeHandlebarObject.context.sponsorHomeContent[index].src = filename;
			}
		},
		titleSponsorAjax : function(){
			var filename;
			for (var index = 0; index < 7; index++) { 
			    filename = titleSponsorDir + (index+1) + fileExtensionSponsor;
	            homeHandlebarObject.context.titleSponsorHomeContent[index] = {};
	            homeHandlebarObject.context.titleSponsorHomeContent[index].src = filename;
			}
		},
		smoothScroll: function(event){
			if (this.hash !== "") {
				// Prevent default anchor click behavior
				event.preventDefault();

				// Store hash
				var hash = this.hash;

				// Using jQuery's animate() method to add smooth page scroll
				// The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
				$('html, body').animate({
				scrollTop: $(hash).offset().top
				}, 800, function(){
					// Add hash (#) to URL when done scrolling (default click behavior)
					window.location.hash = hash;
				});
    		}
		},
		displaySponsorPage : function(){
			console.log("dbsjhsdj");
			var displaySponsorView = initSponsorView ? initSponsorView.compileHandlebarFunction() : new sponsorView();
			window.scrollTo(0,0);
		}
	});

var initHomeView = new homeView();
