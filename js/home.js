var homeHandlebarObject = {
		templateScript : "#home-template",
		context : {	// Define data object
			sliderContent: [],
			sponsorHomeContent: [],
			titleSponsorHomeContent: []
		},
		compiledID : "#mainContentDiv"
	},
	getImagePhpDirectory = '../php/getImage.php', 
	galleryFolderName = 'sliderHome',
	sponsorFolderName = 'sponsorHomeFix',
	titleSponsorFolder = 'titleSponsorsFix',
	pngExtension = 'png',
	JPGExtension = 'JPG',

	homeView = Backbone.View.extend({
		el: "#body",
		events: {
		},
		initialize: function(){	// first function that is called when object of navbarView is made
			this.render();
		},
		render : function(){
			var self = this;
			$.when(this.galleryAjax(), this.sponsorAjax(),this.titleSponsorAjax()).done(function(){
				self.compileHandlebarFunction();
			});
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
			return $.ajax({
			    //This will retrieve the contents of the folder if the folder is configured as 'browsable'
			    url: getImagePhpDirectory,
			    type: 'POST',
			    data: {
			    	'directory' : galleryFolderName,
			    	'extension' : JPGExtension
			    },
			    success: function (data) {
			    	// data returns array of image source
			    	var data = JSON.parse(data);
			        $.each(data, function(index, src){
			        	src = src.replace("../","");	// convert "../img/sliderHome/1.JPG to img/sliderHome/2.JPG"
			            homeHandlebarObject.context.sliderContent[index] = {};	
			            homeHandlebarObject.context.sliderContent[index].src = src;
			            homeHandlebarObject.context.sliderContent[index].dataSlideTo = index;
			            if(index == 0){
			            	homeHandlebarObject.context.sliderContent[index].active = "active";
			            }
			        });
			    },
			    error: function(xhr, status, error) {
			  		var err = eval("(" + xhr.responseText + ")");
			  		console.log(err.Message);
				}
			});
		},
		sponsorAjax: function(){
			return $.ajax({
			    //This will retrieve the contents of the folder if the folder is configured as 'browsable'
			    url: getImagePhpDirectory,
			    type: 'POST',
			    data: {
			    	'directory' : sponsorFolderName,
			    	'extension' : pngExtension
			    },
			    success: function (data) {
			    	var data = JSON.parse(data);
			        $.each(data, function(index, src){
			        	src = src.replace("../","");
			            homeHandlebarObject.context.sponsorHomeContent[index] = {};	
			            homeHandlebarObject.context.sponsorHomeContent[index].src = src;	
			        });
			    },
			    error: function(xhr, status, error) {
			  		var err = eval("(" + xhr.responseText + ")");
			  		console.log(err.Message);
				}
			});
		},
		titleSponsorAjax : function(){
			return $.ajax({
			    //This will retrieve the contents of the folder if the folder is configured as 'browsable'
			    url: getImagePhpDirectory,
			    type: 'POST',
			    data: {
			    	'directory' : titleSponsorFolder,
			    	'extension' : pngExtension
			    },
			    success: function (data) {
			    	var data = JSON.parse(data);
			        $.each(data, function(index, src){
			        	src = src.replace("../","");
			            homeHandlebarObject.context.titleSponsorHomeContent[index] = {};
			            homeHandlebarObject.context.titleSponsorHomeContent[index].src = src;
			        });
			    },
			    error: function(xhr, status, error) {
			  		var err = eval("(" + xhr.responseText + ")");
			  		console.log(err.Message);
				}
			});
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
			var displaySponsorView = initSponsorView ? initSponsorView.compileHandlebarFunction() : new sponsorView();
			window.scrollTo(0,0);
		}
	});

var initHomeView = new homeView();
