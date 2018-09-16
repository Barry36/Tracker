// This file contains all the js code dealing with Bar template pages
$('document').ready(function(){
	// The "change" event is fired whenever a change is made in the editor.
	// editor.on( 'change', function( evt ) {
	//     // getData() returns CKEditor's HTML content.
	//     evt.editor.getData()
	// });


    $('#input_upload_img').change(function(){
        var uploadFile = document.getElementById("input_upload_img").files[0];
        var uploadFileName = uploadFile.name;
        $("#label_upload_img").html(uploadFileName);
    });

    $(document).on('click', '#btn_upload_img', function(e){
        e.preventDefault();
        var templateName = $("#currentTemplate").val();

        // Upload File details
        var uploadFile = document.getElementById("input_upload_img").files[0];
        var uploadFileName = uploadFile.name;
        var uploadFileExtension = uploadFileName.split('.').pop().toLowerCase();
        var uploadFileSize = uploadFile.size;

        var promo_code = $('#templatePromoCode').val();

        var form_data = new FormData();
        form_data.append("file",uploadFile);
        form_data.append("promo_code",promo_code);

        $.ajax({
            url: "upload.php",
            method:"POST",
            data: form_data,
            contentType:false,
            cache:false,
            processData:false,
            success:function(data){
                alert("Uploaded Successfully!");
                var newLocation = "http://" + location.hostname + location.pathname + "?templates&name=" + templateName;
                location.href = newLocation;
            }
        });
    });

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            
            reader.onload = function (e) {
                $('#img-upload').attr('src', e.target.result);
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }



    $("#previewTemplate").click(function(){
        var templateContent = $("#editor").val();
        var templateTitle = $("#templateTitle").val();
        
        var originalName = $("#originalName").html();
        var templateName = $("#currentTemplate").val();
        var templateBtnContent = $("#templateBtnContent").val();
        var templateMdlHeader = $("#templateMdlHeader").val();
        var templateMdlDesc = $("#templateMdlDesc").val();
        var templatePromoCode = $("#templatePromoCode").val();
        var templateParmaTracker = templatePromoCode + "&userid={$USERID}";
        var templatePromo_url = "https://nurseachieve.com/register?discount="+ templateParmaTracker + "%20nurseachieve.com";

        var newLocation = "http://" + location.hostname + "/qbank/customized-admin-panel/Plugin-admin/pages/templates/bar_template/template_preview_view.php";
        // Send an AJAX post request to save the template
        $.post('template_save.php', {template: originalName, title:templateTitle, btnContent:templateBtnContent,
            mdlHeader:templateMdlHeader, mdlDesc:templateMdlDesc, promoCode:templatePromoCode, parmaTracker:templateParmaTracker,
            promo_url:templatePromo_url, content: templateContent, newName: templateName}, function(data) {
            $.ajax({
                  type     : "POST",
                  url      : "template_preview.php", 
                  data     : {templateName:templateName},
                  success: function(data){
                    console.log(data);
                    window.open(newLocation);
                  }
            });
        });
    });

    $("#view_param_tracker").click(function(){
        var promo_code = $("#templatePromoCode").val();
        var param_tracker = promo_code + "&userid={$USERID}";
        var url1 = "https://nurseachieve.com/register?discount=";
        var url2 = "%20nurseachieve.com";
        $("#td_promo_code").html(promo_code);
        $("#td_param_tracker").html(param_tracker);
        $("#td_example_tracker_url").html(url1 + param_tracker + url2);
    });
    
	$("#newTemplate").submit(function(event) {
        // Stop the form from submitting normally
        event.preventDefault();

        // Get the template name
        var templateName = $(this).find("input[name='newTemplate']").val();
        
        // Send an AJAX post request to create the template
        $.post('template_new.php', {template: templateName}, function(data) {
            var newLocation = "http://" + location.hostname + location.pathname + "?templates&name=" + templateName;
            location.href = newLocation;
        });
    }); 

    $("#deleteTemplate").click(function() {
        // Get the template name
        var templateName = $("#originalName").html();	
        // Send an AJAX post request to delete the template
        $.post('template_delete.php', {template: templateName}, function(data) {
            var newLocation = "http://" + location.hostname + location.pathname + "?templates";
            location.href = newLocation;
        });
    });

    $("#commitCode").click(function() {
        var templateContent = $("#editor").val();
        var originalName = $("#originalName").html();
        var templateName = $("#currentTemplate").val();
        $.post('template_save.php', {dev_original_name: originalName, dev_content: templateContent}, function(data) {
            var newLocation = "http://" + location.hostname + location.pathname + "?templates&name=" + templateName;
            location.href = newLocation;    
        });
    });
    $("#saveTemplate").click(function() {
        // Get the template name and content
        // var templateContent = editor.getData();
        var originalName = $("#originalName").html();
        var templateName = $("#currentTemplate").val();

        var templateTitle = $("#templateTitle").val();
        var templateBtnContent = $("#templateBtnContent").val();
        var templateMdlHeader = $("#templateMdlHeader").val();
        var templateMdlDesc = $("#templateMdlDesc").val();

        var templatePromoCode = $("#templatePromoCode").val();
        var templateParmaTracker = templatePromoCode + "&userid={$USERID}";
        var templatePromo_url = "https://nurseachieve.com/register?discount="+ templateParmaTracker + "%20nurseachieve.com";

        var templateContent = $("#editor").val();

        // User need to fill out all the blanks to proceed saving template
        checkValidInput(templateName,templateTitle,templateBtnContent,templateMdlHeader,templateMdlDesc,templatePromoCode,templateContent);
        // Send an AJAX post request to save the template
        $.post('template_save.php', {template: originalName, title:templateTitle, btnContent:templateBtnContent,
            mdlHeader:templateMdlHeader, mdlDesc:templateMdlDesc, promoCode:templatePromoCode, parmaTracker:templateParmaTracker,
            promo_url:templatePromo_url, content: templateContent, newName: templateName}, function(data) {
            // console.log(data);
            var newLocation = "http://" + location.hostname + location.pathname + "?templates&name=" + templateName;
            location.href = newLocation;    
        });
    });


    function checkValidInput(templateName,templateTitle,templateBtnContent,templateMdlHeader,templateMdlDesc,templatePromoCode,templateContent) {
        if (templateName === "") {
            $("#template_name_label").append('<p class="fill_field_reminder">*This Field is Mandatory</p>');
            $("#currentTemplate").focus();
        }
    }
});
