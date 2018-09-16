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


	<!-- Bar Title Block -->
	<div class="bar-property-block-container">
		<div class="bar-property-block">
			<div class="bar-property-desc">
				<p class="bar-property-desc-content">Bar Title</p>
			</div>
			<div class="bar-property-block-content form-group">
			    <input type="text" id="templateTitle" class="form-control bar-property-block-input-field" aria-label="Large" aria-describedby="inputGroup-sizing-lg" value="<?php echo $selectedTemplateTitle ?>">
			</div>
		</div>

		<!-- Bar Btn Block -->
		<div class="bar-property-block">
			<div class="bar-property-desc">
				<p class="bar-property-desc-content">Button Text</p>
			</div>
			<div class="bar-property-block-content form-group">
			    <input type="text" id="templateBtnContent" class="form-control bar-property-block-input-field" aria-label="Large" aria-describedby="inputGroup-sizing-lg" value="<?php echo $selectedTemplateBtnContent ?>">
			</div>
		</div>

		<!-- Modal Header Block -->
		<div class="bar-property-block">
			<div class="bar-property-desc">
				<p class="bar-property-desc-content">Modal Header</p>
			</div>
			<div class="bar-property-block-content form-group">
			    <input type="text" id="templateMdlHeader" class="form-control bar-property-block-input-field" aria-label="Large" aria-describedby="inputGroup-sizing-lg" value="<?php echo $selectedTemplateMdlHeader ?>">
			</div>
		</div>


		<!-- Modal Description Block -->
		<div class="bar-property-block">
			<div class="bar-property-desc">
				<p class="bar-property-desc-content">Modal Description</p>
			</div>
			<div class="bar-property-block-content form-group">
			    <input type="text" id="templateMdlDesc" class="form-control bar-property-block-input-field" aria-label="Large" aria-describedby="inputGroup-sizing-lg" value="<?php echo $selectedTemplateMdlDesc ?>">
			</div>
		</div>


		<!-- Promotion Code Block -->
		<div class="bar-property-block" style="padding-bottom: 20px;">
			<div class="bar-property-desc">
				<p class="bar-property-desc-content">Promotion Code</p>
			</div>
				<!-- Basic dropdown -->
			<div class="bar-property-block-content bar-property-block-flex">
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




		<!-- Promotion Code Block -->
		<div class="bar-property-block" style="padding-bottom: 20px;">
			<div class="bar-property-desc">
				<p class="bar-property-desc-content">Upload Share Image</p>
			</div>
				<!-- Basic dropdown -->
			<div class="bar-property-block-content bar-property-block-flex">
				<div class="bar-property-dropdowns-select-block" style="flex: 1;">
				    <!-- <input type="file" name="file" class="form-control bar-property-block-input-field" id="upload_img"> -->
				    <div class="custom-file">
					    <input type="file" class="custom-file-input" id="input_upload_img" aria-describedby="inputGroupFileAddon01">
					    <label class="custom-file-label" for="inputGroupFile01" id="label_upload_img">Choose file</label>
					</div>
				</div>
			    <div class="bar-property-modal-btn-block" id="dropdown_mdl_btn">
					<!-- Button trigger modal -->
					<button type="button" class="btn btn-primary" id="btn_upload_img">Upload</button>
				</div>
			</div>
		</div>
	</div>
	
</div>



<div class="pull-right" style="width: 100%;margin-top: 20px;">
	<button type="button" class="btn btn-info" data-toggle="modal" data-target="#devModeModal" style="float: left;">Developer Mode</button>
	<button class="btn btn-primary" id="previewTemplate" style="float: left;">Preview Template</button>
    <button class="btn btn-danger" data-toggle="modal" data-target="#delete-template" style="float: right;">Delete Bar Template</button>
    <button class="btn btn-success" id="saveTemplate" style="float: right;">Save Bar Template</button>
    

</div>
