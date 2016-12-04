var campusAmbassadorHandlebarObject = {
	templateScript : "#campusAmbassador-template",
	context : {	// Define data object
		campusAmbassadorForm: [
			{input:"input", label:"Name", id:"name", type:"text", placeholder:"Enter Name", pattern:"^[a-zA-Z][a-zA-Z][a-zA-Z][a-zA-Z ]*$", required:"required"},
			{input:"input", label:"Contact", id:"contact", type:"tel", placeholder:"Enter contact number", maxlength:"10", pattern:"^[987]\\d{9}$", required:"required"},
			{input:"input", label:"College", id:"college", type:"college", placeholder:"Enter college name", pattern:"^[a-zA-Z](?=.{2,})[^;:+=_*^%$#@!]*$", required:"required"},
			{input:"input", label:"Email", id:"email", type:"email", placeholder:"Enter email", pattern:"^[a-zA-Z0-9_]+([\\.-]?[a-zA-Z0-9_]+)*@[a-zA-Z]+([\\.-]?[a-zA-Z]+)*(\\.[a-zA-Z]{2,3})+$", required:"required"},
			{label:"Motivation for the role", id:"motivation", placeholder:"Enter motivation (within 200 words)", required:"required"},
			{label:"Experience  (if any)", id:"experience", placeholder:"Enter experience"}
		]
	},
	compiledID : "#campusAmbassador-form"
};

var campusAmbassadorModal = Backbone.View.extend({
	el : ".modal-body",
	events : {
		//	'submit #campusAmbassador-form' : 'formSubmit'
	},
	initialize : function(){
		compileHandlebar(campusAmbassadorHandlebarObject);
	},
	formSubmit : function(event){
		event.preventDefault();
		$(".modal-body").html("Thank you ! Our team will contact you soon.");
    	$(".modal-footer").html("<button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>");
	}
});

var initCampusAmbassadorModal = new campusAmbassadorModal();