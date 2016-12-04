var ourTeamHandlebarObject = {
		templateScript : "#ourteam-template",
		context : {	// Define data object
			sliderContent: []
		},
		compiledID : "#mainContentDiv"
	},

	ourTeamView = Backbone.View.extend({
		el: "#body",
		events: {
			
		},
		initialize: function(){	// first function that is called when object of navbarView is made
			this.compileHandlebarFunction();
		},
		compileHandlebarFunction : function(){
			compileHandlebar(ourTeamHandlebarObject);
			window.scrollTo(0,0);
		}
	}),

	initOurTeamView;