<?php
require('navbar.php');
require('lib.php');
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
  <link rel="stylesheet" type="text/css" href="css/admin_panel_styles.css">

  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
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
  <script type="text/javascript" src="main.js"></script>
</head>

<!--Main Navigation-->
  <header>
    <?php getNavBar()?>
  </header>
<!--Main Navigation-->

  <div class="mdl-layout mdl-js-layout mdl-layout--fixed-drawer">

    <div class="mdl-layout__drawer">
      <span class="mdl-layout-title">Title</span>
      <span class="mdl-layout-title" style="font-size: 27px;"><b>Template</b></span>
      <nav class="mdl-navigation">
        <a class="mdl-navigation__link template-tab" href="/qbank/customized-admin-panel/Plugin-admin" style="font-size: 20px;">Dashboard</a>
        <a class="mdl-navigation__link template-tab" href="http://localhost/qbank/customized-admin-panel/Plugin-admin/pages/templates/bar_template/bar_template.php?templates" style="font-size: 20px;">Bar Template</a>
        <a class="mdl-navigation__link template-tab" href="email_template.php" style="font-size: 20px;">Email Template</a>
        <a class="mdl-navigation__link template-tab" href="#" style="font-size: 20px;">Opintons</a>


      </nav>
    </div>
    <main class="mdl-layout__content">
      <!-- Use flex style to adjust the height -->
      <div style="display: flex;flex-direction: column;height: 100%;">

        <!-- This div only takes space but does nothing else -->
        <!-- ============================================================== -->
        <!--          Content Starts Here                         -->
        <!-- ============================================================== -->
        <div class="page-content customized-fluid-container">
              <!-- Barry -->
              <div class="card trigger-table" style="padding: 0% 3%;">
                <h3>You can assign messages to the Users on this Dashboard</h3>
                <ul>
                  <li>Click<span class="glyphicon glyphicon-plus"><a class="text-success"><i class="fa fa-plus fa-2x"></i></a></span>to Add and <span class="glyphicon glyphicon-remove"><a class="text-success"><i class="fa fa-remove fa-2x" style="color: red;"></i></a></span>to Delete triggers</li> 
                  <li>Choose from <strong>Message Type</strong> to send either Bars or Emails</li>
                  <li>Set conditions for target users in the <strong>Condition</strong> column</li>
                </ul>
                
                <!-- Below is Trigger Table -->

                    <?php echo getInfo(); ?>  
                <!-- <button type='button' id="test-ajax">test</button> -->
                </tbody>
                </form>


                    <!-- ===================================== -->
                    <!--       This is trigger row line        -->
                    <?php echo getNewRow()?>
                     <!-- ===================================== -->
                     
                    

                    <!-- ===================================== --> 
                    <!--       This is condition row line      -->
                    <!-- ===================================== -->
                    <tr class="table-inner hide" style="background:white;">
                        <td style="text-align: center;padding-right: 0px;"><span class="condition-icon condition-minus glyphicon glyphicon-minus "><a class="text-success"><i class="fa fa-minus"></i></a></span>
                        </td>
                        <td><select class="findParamInCondition">
                              <option value="userid">user_id</option>
                              <option value="courseid">courseid</option>
                              <option value="days_to_be_expired">days_to_be_expired</option>
                              <option value="time_used_quiz">time_used_quiz</option>
                            </select>
                            <span class="displaySpan displaySpan-param hide"></span>
                        </td>
                        <td><select name="Operator" class="findOperatorInCondition">
                              <option value="Equal">Equal</option>
                              <option value="Less_than">Less than</option>
                              <option value="Greater_than">Greater than</option>
                            </select>
                            <span class="displaySpan displaySpan-operator hide"></span>
                        </td>
                        <td><input type="text" name="Condition_Value" class="findValueInCondition" style="width: 75%;">
                            <span class="displaySpan displaySpan-paramValue hide"></span>
                        </td>
                        <td class="condt_id hide"></td>
                    </tr>
                    <!-- ===================================== --> 
                    <!--      This is condition table line     -->
                    <!-- ===================================== -->

                  </table>
                </div>
             </div>
              <!-- Barry and Bomin -->
        </div>

        <!-- ============================================================== -->
        <!--          Content Ends Here                         -->
        <!-- ============================================================== -->

      </div>
    </main>
  </div>


  <!-- Modal -->

  <?php echo getCourseidModal();  ?>
  
              
            



<!-- ===================================== -->
<!--         Content starts from here      -->
<!-- ===================================== -->

