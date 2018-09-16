// This files contains all the js code about additino, deletion and modification on Email Templates
$('document').ready(function(){
	// The "change" event is fired whenever a change is made in the editor.
	// editor.on( 'change', function( evt ) {
	//     // getData() returns CKEditor's HTML content.
	//     evt.editor.getData()
	// });

    $("#view_param_tracker").click(function(){
        var promo_code = $("#templatePromoCode").val();
        var param_tracker = promo_code + "&userid={$USERID}";
        var url1 = "https://nurseachieve.com/register?discount=";
        var url2 = "%20nurseachieve.com";
        $("#td_promo_code").html(promo_code);
        $("#td_param_tracker").html(param_tracker);
        $("#td_example_tracker_url").html(url1 + param_tracker + url2);
    });
    
	$("#createNewTemplate").click(function(event) {
        // Stop the form from submitting normally
        // console.log("yoooo");
        // event.preventDefault();

        // Get the template name
         var templateName = $("#inputTemplateName").val();
        
        // console.log("yoooo");
        // Send an AJAX post request to create the template
        $.post('email_template_new.php', {template: templateName}, function(data) {
            var newLocation = "http://" + location.hostname + location.pathname + "?templates&name=" + templateName;
            location.href = newLocation;
        });
    }); 

    $("#deleteTemplate").click(function() {
        // Get the template name
        var templateName = $("#originalName").html();	
        // Send an AJAX post request to delete the template
        $.post('email_template_delete.php', {template: templateName}, function(data) {
            var newLocation = "http://" + location.hostname + location.pathname + "?templates";
            location.href = newLocation;
        });
    });

    $("#saveTemplate").click(function() {
        // Get the template name and content
        // var templateContent = editor.getData();
        var originalName = $("#originalName").html();
        var templateName = $("#currentTemplate").val();

        var templateSubject = $("#templateSubject").val();

        var templatePromoCode = $("#templatePromoCode").val();
        var templateParmaTracker = templatePromoCode + "&userid={$USERID}";
        var templatePromo_url = "https://nurseachieve.com/register?discount="+ templateParmaTracker + "%20nurseachieve.com";

        var templateBody = $("#emailBodyEditor").val();

        // User need to fill out all the blanks to proceed saving template
     // checkValidInput(templateName,templateTitle,templateBtnContent,templateMdlHeader,templateMdlDesc,templatePromoCode,templateContent);
        // Send an AJAX post request to save the template
        $.post('email_template_save.php', {template: originalName, newName: templateName, subject: templateSubject, 
        	promoCode: templatePromoCode, parmaTracker: templateParmaTracker, promo_url: templatePromo_url, body: templateBody}, function(data) {
            console.log(data);
            var newLocation = "http://" + location.hostname + location.pathname + "?templates&name=" + templateName;
            location.href = newLocation;    
        });
    });


    // function checkValidInput(templateName,templateTitle,templateBtnContent,templateMdlHeader,templateMdlDesc,templatePromoCode,templateContent) {
    //     if (templateName === "") {
    //         $("#template_name_label").append('<p class="fill_field_reminder">*This Field is Mandatory</p>');
    //         $("#currentTemplate").focus();
    //     }
    // }
});
