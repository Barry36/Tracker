<?php
// Email Template View
?>
<div class="bar-property">
	<!-- Bar Name Block -->
	<div class="bar-property-block">
		<div class="bar-property-desc" id="template_name_label">
			<p class="bar-property-desc-content">Template Name</p>
		</div>
		<div class="bar-property-block-content form-group">
		    <input type="text" id="currentTemplate" class="form-control bar-property-block-input-field" aria-label="Large" aria-describedby="inputGroup-sizing-lg" value="<?php echo $selectedTemplate ?>">
		    <p id="originalName" hidden><?php echo $selectedTemplate ?></p>
		</div>
	</div>

	<!-- Email Subject Block -->
	<div class="bar-property-block">
		<div class="bar-property-desc">
			<p class="bar-property-desc-content">Email Subject</p>
		</div>
		<div class="bar-property-block-content form-group">
		    <input type="text" id="templateSubject" class="form-control bar-property-block-input-field" aria-label="Large" aria-describedby="inputGroup-sizing-lg" value="<?php echo $selectedTemplateSubject ?>">
		</div>
	</div>

	<!-- Promotion Code Block -->
	<div class="bar-property-block" style="padding-bottom: 20px;">
		<div class="bar-property-desc">
			<p class="bar-property-desc-content">Promotion Code </p>
		</div>
			<!-- Basic dropdown -->
		<div class="bar-property-block-content bar-property-dropdowns-block">
			<div class="bar-property-dropdowns-select-block" style="flex: 1;">
			    <input type="text" id="templatePromoCode" class="form-control bar-property-block-input-field" aria-label="Large" aria-describedby="inputGroup-sizing-lg" value="<?php echo $selectedTemplatePromoCode ?>">
			</div>
		    <div class="bar-property-modal-btn-block" id="dropdown_mdl_btn">
				<!-- Button trigger modal -->
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#promo_code_view_mdl" id="view_param_tracker">
				  View Parameter
				</button>
			</div>
		</div>
	</div>



	<!-- Email Body Block -->
	<div class="bar-property-block">
		<div class="bar-property-desc">
			<p class="bar-property-desc-content">Email Body</p>
		</div>
		<div class="bar-property-block-content form-group">
		    <form class="p-5" id="newTemplate" style="margin-bottom: 0px; padding: 20px 10px 0px 10px !important;">
               	<div class="card bar-content-editor">
				  <textarea id="emailBodyEditor" rows="20"><?php 
				  foreach ($templates as $template) {
				  if ($template['Template_name'] == $selectedTemplate){
				  	if ($selectedTemplateBody) {echo $selectedTemplateBody; }
				  }
				}?></textarea>
				</div>
	        </form>
		</div>
	</div>
	
</div>



<div class="pull-right" style="width: 100%;margin-top: 20px;">
    <button class="btn btn-danger" data-toggle="modal" data-target="#delete-template" style="float: right;">Delete Email Template</button>
    <button class="btn btn-success" id="saveTemplate" style="float: right;">Save Email Template</button>
    

</div>
