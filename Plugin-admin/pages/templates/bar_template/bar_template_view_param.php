<!-- Modal -->
<div class="modal fade" id="promo_code_view_mdl" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document" style="max-width: 1200px;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Promotion Codes</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table table-striped">
          <thead>
            <tr>
              <th scope="col" style="width: 15%;">Promotion Code</th>
              <th scope="col" style="width: 30%;">Parameter(TRACKER)</th>
              <th scope="col">Example URL</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td id="td_promo_code"><?php if ($template['Bar_name'] == $selectedTemplate){ 
                echo $selectedTemplatePromoCode; } ?></td>
              <td id="td_param_tracker"><?php if ($template['Bar_name'] == $selectedTemplate){
                echo $selectedTemplateParmaTracker; }?></td>
              <td id="td_example_tracker_url">https://nurseachieve.com/component/dtregister/?Itemid=&eventId=197&controller=event&task=individualRegister&discount=<?php 
              if ($template['Bar_name'] == $selectedTemplate){ echo $selectedTemplateParmaTracker;} ?>%20nurseachieve.com</td>
            </tr>
          </tbody>
        </table>
        <div id="promo_code_desc">
          <p>Variable <b>{$USERID}</b> is the userid of current user when he/she login.</p>
          <p>Parameter <b>TRACKER</b> is the parameter used to track who successfully share the link and invite their friends register.</p>
          <p>Click <b>SAVE EMAIL TEMPLATE</b> below to save your changes</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn" data-dismiss="modal" style="background-color: #a5a5a5;">Close</button>
      </div>
    </div>
  </div>
</div>
