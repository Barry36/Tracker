<?php
// This is for Bar Template Edit
require('../../../navbar.php');
require('../../../lib.php');
$DB = mysqli_connect('localhost','root','wjq123','beanstalk_moodle');
?>

<head>

  <!-- Style  -->
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <!-- Bootstrap core CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.4/css/mdb.min.css" rel="stylesheet">
  <!-- "admin_panel_styles.css" is the customized style -->
  <link rel="stylesheet" type="text/css" href="../../../css/bar-template-style.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
</head>


<body>
<header>
  <?php getNavBar()?>
</header>

	<div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer">
	  <div class="mdl-layout__drawer ">
	    <span class="mdl-layout-title">Title</span>
	    <span class="mdl-layout-title" style="font-size: 27px;"><b>Template</b></span>
	    <nav class="mdl-navigation">
	    <?php
	    	isset($_GET['name']) ? $selectedTemplate = $_GET['name'] : $selectedTemplate = null;
            $templates = get_bar_templates();
            foreach ($templates as $template) {
            	if ($template['Bar_name'] == $selectedTemplate) {
                    // Save the template content
                    $selectedTemplateContent = $template['Bar_content'];
                    $selectedTemplateTitle = $template['Bar_title'];
                    $selectedTemplateBtnContent = $template['Bar_btn_content'];
                    $selectedTemplateMdlHeader = $template['Bar_mdl_header'];
                    $selectedTemplateMdlDesc = $template['Bar_mdl_description'];
                    $selectedTemplatePromoCode = $template['Bar_promo_code'];
                    $selectedTemplateParmaTracker = $template['Prama_tracker'];

                    echo "<a class=\"mdl-navigation__link template-tab selected-template\" href='?templates&name=".$template['Bar_name']."' style=\"font-size: 20px;\">".$template['Bar_name']."</a>";
                } else {
                    echo "<a class=\"mdl-navigation__link template-tab\" href='?templates&name=".$template['Bar_name']."' style=\"font-size: 20px;\">".$template['Bar_name']."</a>";
                }
            }

             
             
        ?>
	    	<a class="mdl-navigation__link template-tab create-new-template" id="Add_New_Bar_Template" href="#" data-toggle="modal" data-target="#addModal" style="font-size: 20px; padding-top: 100px;">Add Template  <i class="fa fa-plus fa-x"></i></a>
	    </nav>
	  </div>
	  <main class="mdl-layout__content">
	  	<!-- Use flex style to adjust the height -->
	  	<div style="display: flex;flex-direction: column;height: 100%;">
	  		<!-- This div only takes space but does nothing else -->
	  		<div style="flex: 0.065"></div>
	  		<!-- ============================================================== -->
	  		<!-- 					Content Starts Here                         -->
	  		<!-- ============================================================== -->
		    <div class="page-content customized-fluid-container">
		    	<?php 
			    	if (empty($_GET['name'])) {
	                    echo '<div><h2 class="page-header" style="margin-top:0px;">Bar Templates</h2>';
	                    if ($templates) {
	                        echo "<ul>";
	                        foreach ($templates as $template) {
	                            echo "<li><a class=\"template-list\" href='?templates&name=".$template['Bar_name']."' style=\"font-size: 20px;\">".$template['Bar_name']."</a>";
	                        }
	                        echo "</ul>
	                        	
	                        	</div>";
	                    }
	                }elseif ($selectedTemplate) {
		                   require (__DIR__.'/bar_template_view.php');
		                }

	            ?>

	         
		    </div>

		    <!-- ============================================================== -->
	  		<!-- 					Content Ends Here                         -->
	  		<!-- ============================================================== -->

		</div>

		

	  </main>
	</div>
	<!-- No header, and the drawer stays open on larger screens (fixed drawer). -->


	<!-- Create new Bar template Modal -->
	<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document" style="max-width: 700px;">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="createTemplateTitle">Creating a New Template</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        	<form class="p-5" id="newTemplate" style="margin-bottom: 0px; padding: 20px 10px 0px 10px !important;">
	               	<input type="text" class="form-control mb-4" name="newTemplate" id="inputTemplateName" placeholder="Template Name">
	               	<div class="modal-footer">
				        <button type="button" class="btn" data-dismiss="modal" style="background-color: #a5a5a5;">Close</button>
				        <button type="submit" class="btn btn-success">Save changes</button>
				    </div>
	            </form>
	      </div>
	    </div>
	  </div>
	</div>


	<!-- Delete Modal -->
    <div class="modal fade" id="delete-template" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document" style="max-width: 700px;">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="createTemplateTitle">Deleting <?php echo $selectedTemplate ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <h5>Are you sure that you wish to delete <?php echo $selectedTemplate ?>? This action cannot be undone.</h5>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn" data-dismiss="modal" style="background-color: #a5a5a5;">Cancel</button>
            <button type="button" class="btn btn-danger" id="deleteTemplate">Confirm Deletion</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Developer Mode Modal -->
	<div class="modal fade" id="devModeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document" style="max-width: 1000px;">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="createTemplateTitle">Creating a New Template</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        	<form class="p-5" id="newTemplate" style="margin-bottom: 0px; padding: 20px 10px 0px 10px !important;">
	               	<div class="card bar-content-editor">
					  <textarea id="editor" rows="20"><?php 
					  foreach ($templates as $template) {
					  if ($template['Bar_name'] == $selectedTemplate){
					  	if ($selectedTemplateContent) {echo $selectedTemplateContent; }
					  }
					}?></textarea>
					</div>
	               	<div class="modal-footer">
				        <button type="button" class="btn" data-dismiss="modal" style="background-color: #a5a5a5;">Close</button>
				        <button type="button" class="btn btn-success" data-dismiss="modal" id="commitCode">Commit Code</button>
				    </div>
	            </form>
	      </div>
	    </div>
	  </div>
	</div>
	<!-- Developer Mode Modal -->

    <?php require (__DIR__.'/bar_template_view_param.php');?>


  <script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>
  <!-- JQuery -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <!-- Bootstrap tooltips -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js"></script>
  <!-- Bootstrap core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0/js/bootstrap.min.js"></script>
  <!-- MDB core JavaScript -->
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.4/js/mdb.min.js"></script>

  
  <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-2.2.4.min.js"></script>

  <!-- Include ckeditor -->
  <script src="../../../ckeditor/ckeditor.js"></script>
  <script src="../../../ckeditor/adapters/jquery.js"></script>

  <!-- include custom js -->
  <script type="text/javascript" src="../../../js/bar-template-script.js"></script>  

  <?php 
	function get_bar_templates(){
		global $DB;
	    $sql = "SELECT * FROM mdl_trigger_bar ";
	    if (!$result = $DB->query($sql)) {
            die ("Could not get the Bar templates. MySQL Error [" . $DB->error . "]");
        }
        while ($row = $result->fetch_assoc()) {
            $templates[] = $row;
        }
        // $DB->close();
        return $templates;
	}
?>      

</body>


