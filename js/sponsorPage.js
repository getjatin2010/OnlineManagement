var sponsorHandlebarObject = {
		templateScript : "#sponsor-template",
		context : {	// Define data object
			sponsorPageContent: []
		},
		compiledID : "#mainContentDiv"
	},
	sponsorView = Backbone.View.extend({
		el: "#body",
		events: {
		},
		initialize: function(){	// first function that is called when object of navbarView is made
			this.sponsorPageAjax();
		},
		compileHandlebarFunction : function(){
			compileHandlebar(sponsorHandlebarObject);
		},
		sponsorPageAjax : function(){
			var sponsor = homeHandlebarObject.context.sponsorHomeContent;
			if(sponsor){
				sponsorHandlebarObject.context.sponsorPageContent = sponsor;
			}
			compileHandlebar(sponsorHandlebarObject);
		}
	}),

	initSponsorView;

function toggle_visibility(id) {
   var e = document.getElementById(id);
   if(e.style.display == 'block')
	  e.style.display = 'none';
   else
	  e.style.display = 'block';
}

function hide_visibility(id) {
   var e = document.getElementById(id);
   if(e.style.display == 'block')
	  e.style.display = 'none';
   else
	  e.style.display = 'block';
}
