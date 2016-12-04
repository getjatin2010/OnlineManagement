/*
 *  @param : obj : handlebarObject
 *	@function compileHandlebar
 *  Helper function to compile handlebar template
 */
var compileHandlebar = function(obj){
	var templateScript = obj.templateScript,// templateScript : id of handlebars-template script <script id="navbar-template" type="text/x-handlebars-template">
		context = obj.context, 				// context : data that needs to be added to template
		compiledID = obj.compiledID,		// compiledID : id of div where compiled script needs to be added <div id="navbarDiv"></div>
		
		theTemplateScript = $(templateScript).html(), // Grab the template script
		theTemplate = Handlebars.compile(theTemplateScript), // Compile the template
		theCompiledHtml = theTemplate(context); // Pass our data to the template
		
		$(compiledID).html(theCompiledHtml); // Add the compiled html to the page
};

/*
 *	@function checkDesktopDevice
 *  Helper function to check if device is desktop or not
 *  return true - if desktop
 *  return false - if not desktop
 */
var checkDesktopDevice = function(){
	//var width = (window.innerWidth > 0) ? window.innerWidth : screen.width,
	var width = screen.width,
		deviceDesktop = width > 768 ? true : false;
	return deviceDesktop;
};