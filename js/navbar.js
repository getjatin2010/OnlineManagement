// All div and id used in following js
var selector = {
	"navbar": "#navbar",
	"navbarToggler": ".navbar-toggler",
	"navbarMinimal": ".navbar-minimal",
	"navbarMenu": ".navbar-menu",
	"navbarTemplate": "#navbar-template",
	"navbarDiv": "#navbarDiv",
	"homeTemplate": "#home-template",
	"firstTemplate": "#first-template",
	"secondTemplate": "#second-template",
	"mainContentDiv": "#mainContentDiv"
};

var navBarHandlebarObject = {
	templateScript : selector.navbarTemplate,
	context : {	// Define data object
		navbarContent: [
			{name:"Home", id:"homeNav", href:"home", img:"../img/navBar/homeNav.png"},
			{name:"Gallery", id:"galleryNav", href:"galleryHome", img:"../img/navBar/galleryNav.png"},
			//{name:"Events", id:"eventsNav", href:"", glyphiconIcon:"glyphicon-list-alt", dataToggle:"modal" , dataTarget: "#comingSoonModal"},
			{name:"Events", id:"eventsNav", href:"", img:"../img/navBar/eventsNav.png", dataToggle:"modal" , dataTarget: "#comingSoonModal"},
			{name:"Our Team", id:"ourTeamNav", href:"", img:"../img/navBar/ourTeamNav.png"},
			{name:"Sponsors", id:"sponsorsNav", href:"sponsorHome", img:"../img/navBar/sponsorsNav.png"}
		]
	},
	compiledID : selector.navbarDiv
};

// Testing handlebar for maincontent on click of navigation menu
var home = {
	templateScript : selector.homeTemplate,
	context : {	// Define data object
		id: "home"
	},
	compiledID : selector.mainContentDiv
};

// Testing handlebar for maincontent on click of navigation menu
var first = {
	templateScript : selector.firstTemplate,
	context : {	// Define data object
		id: "first"
	},
	compiledID : selector.mainContentDiv
};

// Testing handlebar for maincontent on click of navigation menu
var second = {
	templateScript : selector.secondTemplate,
	context : {	// Define data object
		id: "second"
	},
	compiledID : selector.mainContentDiv
};

// Navbar View
var navbarView = Backbone.View.extend({
	el: selector.navbar,
	events: {
		'click .navbar-toggler': 'toggleClassFunction',
		'mouseover .navbar-menu': 'slideLeftNavbarMenu',
		'mouseout .navbar-menu': 'slideRightNavbarMenu',
		'click #home, #first, #second' : 'changeMainContentHandle',
		'click #homeNav' : 'displayHome',
		'click #ourTeamNav' : 'displayOurTeam',
		'click #sponsorsNav' : 'displayHome',
		'click #galleryNav' : 'displayHome'
	},
	initialize: function(){	// first function that is called when object of navbarView is made
		if(checkDesktopDevice()){
			$(selector.navbar).addClass("open");
			$(selector.navbarToggler).remove();
			$(selector.navbarMinimal + " "+ selector.navbarMenu).css("position","initial").css("height","1080px");
			$(selector.navbarMinimal).css("left","-140px"); // new navBar
		}
	},
	toggleClassFunction :  function(event){
		event.preventDefault();
		$(selector.navbarMinimal).toggleClass('open');
	},
	slideLeftNavbarMenu : function(event){
		if(checkDesktopDevice()){
			event.preventDefault();
			$(selector.navbarMinimal).css("left","0px");
			//$("body").css("padding-left","200px");
		}
	},
	slideRightNavbarMenu : function(event){
		if(checkDesktopDevice()){
			event.preventDefault();
			$(selector.navbarMinimal).css("left","-140px");
			//$("body").css("padding-left","60px");
		}
	},
	changeMainContentHandlebar : function(event){
		event.preventDefault();
		var currentTarget = event.currentTarget,
			name = $(currentTarget).attr("name");
			if(name == "home"){
				compileHandlebar(home);
			}else if(name == "first"){
				compileHandlebar(first);
			}else{
				compileHandlebar(second);
			}
	},
	displayHome : function(){
		if(initHomeView){
			initHomeView.compileHandlebarFunction();
		}else{
			var displayHomeView = new homeView();
				initHomeView = displayHomeView;
		}
	},
	displayOurTeam : function(){
		var displayOurTeamView = initOurTeamView ? initOurTeamView.compileHandlebarFunction() : new ourTeamView();
	}
});

var navBarHandlebarInit = compileHandlebar(navBarHandlebarObject); // defined in commonHelper.js
var navbarViewInit = new navbarView();
