$('document').ready(function()
{
  var condition_num = 2;
  // Create an array of objects to store trigger info
  var arrayOfChangedTrigger=[];
  var conditionArr=[];

  // list all option values for all select tags


// *********************************************************************************************************************************
// ***************                                Insert / Delete Triggers                                        ***************
// *********************************************************************************************************************************
  $('span.table-add').on('click',function () {
  // Clone and append the new row to the Trigger table
    var $newRow = $('#table').find('tr.table-outer').clone(true).removeClass('hide table-outer').addClass('existedTrigger');
    $('#table').find('#outer-table').append($newRow);
 
    // Everytime create new trigger, insert a new record to DB with defualt value
    var isInsertNewTrigger = true;
    var jsonisInsertNewTrigger = JSON.stringify(isInsertNewTrigger);
    var LastConditionId = $newRow.find('td.condt_id:last');
    var currentTriggerId = "tmr";
    $.ajax({
        type: "POST",
        url: "insert.php",
        data: {isInsertNewTrigger:jsonisInsertNewTrigger},
        success: function(data)
        {
            var currentTriggerId;
            var tmpConditionId = data;
            LastConditionId.html(data);


          // Another post request  
          $.ajax({
                type: "POST",
                url: "getLastTriggerId.php",
                data: {lastTriggerId: ''},
                success:function(data){
                  $newRow.find('td.trig_id').text(data);
                  currentTriggerId = $newRow.find('td.trig_id').text();
                  arrayOfChangedTrigger.push({
                    TriggerId:currentTriggerId.toString(),
                    ConditionIdArr:[tmpConditionId]
                  });
                  

                  // Front End Changes
                  // Add classes to the new row
                    $newRow.find('select.findParamInTrigger').removeClass('findParamInTrigger').addClass('condition-param');
                    $newRow.find('select.findOperatorInTrigger').removeClass('findOperatorInTrigger').addClass('condition-operator');
                    $newRow.find('input:text.findValueInTrigger').removeClass('findValueInTrigger').addClass('condition-value');
                  
                  // Add id and name for the Trigger Name field
                    $newRow.find('input:text.addId').attr('id','trigger_name_'+currentTriggerId);
                    $newRow.find('#displayTriggerName').attr('id','SpanTriggerName_'+currentTriggerId);
                  // Add id and name for the Message Type drop down list
                    $newRow.find('select.msg_type').attr('id','trigger_type_'+ currentTriggerId);
                    $newRow.find('#displayMsgType').attr('id','SpanTriggerMsgType_'+currentTriggerId);

                  // Add id and name for the Message Content drop down list
                    $newRow.find('select.msg_content').attr('id','message_content_'+ currentTriggerId);
                    $newRow.find('#displayMsgContent').attr('id','SpanTriggerMsgName_'+currentTriggerId);

                  // Add id for condition table
                    $newRow.find('div.container-cdt-table').attr('id','condition-table_'+ currentTriggerId);
                    $newRow.find('table.table-conditions').attr('id','inner-table_'+ currentTriggerId);
                  // Add courseidExisted attribute to the newRow
                    $newRow.attr('courseidExisted','false');
                } 
              });
        }
    });
  });

// Remove trigger
 $('span.table-remove').click(function () {
    // get the latest trigger number in the table so we can auto increment the trigger id
    var currentTriggerId = $(this).closest('tr').find('td.trig_id').html();
    var msgType = $(this).closest('tr').find('select.msg_type').val();
    var msgName = $(this).closest('tr').find('select.msg_content').val();

    if (confirm("Are you sure to delete this trigger Permanently?")) {
        $(this).parents('tr').detach();
      // Delete trigger ID from arrayOfChangedTrigger
        var i;
        for(i=0;i<arrayOfChangedTrigger.length;++i){
          if (arrayOfChangedTrigger[i].TriggerId == currentTriggerId) {
            arrayOfChangedTrigger.splice(i,1);
          }
        }
      // Delete trigger from DB
      deleteTriggerInfo={
        triggerId:currentTriggerId,
        msgType:msgType,
        msgName:msgName
      };
      var jsondeleteTriggerInfo = JSON.stringify(deleteTriggerInfo);
      $.ajax({
        type: "POST",
        url: "delete.php",
        data:{deleteTriggerInfo:jsondeleteTriggerInfo},
        success: function(data){
          console.log(data);
        }
      });
    }

    return false;

  });


// *********************************************************************************************************************************
// ***************                   Insert / Delete Trigger Conditins                                                  ***************
// *********************************************************************************************************************************

  // Add Condition
  $('.condition-add').on('click',function(){

    var currentTriggerId = $(this).closest('td').parent().find('td.trig_id').html(); 
    var $newRow = $('#table').find('tr.table-inner').clone(true).removeClass('hide table-inner');
    var currentCondititionTable = $(this).closest('#inner-table_'+currentTriggerId);
    var conditionNum = currentCondititionTable.find('select.condition-param').length;
    conditionNum++;

  // append new condition to condition table
    currentCondititionTable.append($newRow);
  // Add classes to new added condition
    currentCondititionTable.find('select.findParamInCondition').removeClass('findParamInCondition').addClass('condition-param');
    currentCondititionTable.find('select.findOperatorInCondition').removeClass('findOperatorInCondition').addClass('condition-operator');
    currentCondititionTable.find('input:text.findValueInCondition').removeClass('findValueInCondition').addClass('condition-value');
      
    

  // Display default condition
      $newRow.find('select.condition-param').val('userid');
      $newRow.find('select.condition-operator').val('Equal');
      $newRow.find('input:text.condition-value').val('1');

  // Add default condition to DB when click add condition
      var jsoninsertConditionTriggerId = JSON.stringify(currentTriggerId);
      var conditionId;
      var conditionIdContent = $(this).closest('table').find('td.condt_id:last');
      
      $.ajax({
        type:"POST",
        url:"insert.php",
        data:{insertConditionTriggerId:jsoninsertConditionTriggerId},
        success: function(data){ 
              conditionIdContent.html(data);
              var result = arrayOfChangedTrigger.find(function( obj ) {
              return obj.TriggerId == currentTriggerId;
              });
              result.ConditionIdArr.push(data);

            }
        });

});

  // Delete condition 
   $('.condition-minus').click(function () {
        var currentTriggerId = $(this).closest('table').closest('tr').find('td.trig_id').html();
        var currentConditionId = $(this).closest('tr').find('td.condt_id').html();
        
       if (confirm("Are you sure to delete this condition?"))
        {
            var jsoncurrentConditionId = JSON.stringify(currentConditionId);
            // Delete from UI
            $(this).closest('tr').detach();
            // Delete from arrayOfChangedTrigger
            var result = arrayOfChangedTrigger.find(function(obj){
              return obj.TriggerId == currentTriggerId;
            });
            var i;
            for(i=0;i<result.ConditionIdArr.length;++i){
              if (result.ConditionIdArr[i] == currentConditionId) {
                result.ConditionIdArr.splice(i,1);
              }
            }
            // Delete from DB
            $.ajax({
              type:"POST",
              url:"delete.php",
              data:{currentConditionId:jsoncurrentConditionId}
            });
        }
      return false;
  });




// *********************************************************************************************************************************
// ***************                        This section has all Button fucntions                                      ***************
// *********************************************************************************************************************************


// Save button

$('.saveTriggerRow').on('click',function(e){
      var TriggerId = $(this).closest('tr').find('td.trig_id').html();
      var TriggerName = $(this).closest('tr').find('input:text.addId').val();
      var TriggerMsgType = $(this).closest('tr').find('select.msg_type').val();  
      var TriggerMsgName = $(this).closest('tr').find('select.msg_content').val(); 
      var CondititionTable = $(this).closest('#inner-table_'+TriggerId);


      // Create arrays to store parameters, operators and values
      var ParamArray=[];
      // ParamArray2 is only for identifying how many times the parameter "courseid" appear
      var ParamArray2 = [];
      var OperatorArray = [];
      var ValueArray = [];
      var originalMsgName = $('#SpanTriggerMsgName_'+TriggerId).html();
      // Prevent user from proceeding when Trigger Name are empty
      if (TriggerName == ''){
          alert('Please fill out Trigger Name to save!');
          return false;
      }
          
    // Update Trigger (including conditions update)

      // Front-end Update
        $('#SpanTriggerName_'+TriggerId).html(TriggerName);
        $('#SpanTriggerMsgType_'+TriggerId).html(TriggerMsgType);
        $('#SpanTriggerMsgName_'+TriggerId).html(TriggerMsgName);

        // Push and store in array
        $('#inner-table_'+TriggerId).find('select.condition-param').each(function(){
          var tmp = $(this).val();
          ParamArray.push(tmp);
          ParamArray2.push(tmp);
        });


        if (countInArray(ParamArray2,"courseid") > 1) {
          alert("You can only have one courseid for one trigger");
          return false;
        }else if(countInArray(ParamArray2,"courseid") == 0){
          $(this).closest('tr').attr('courseidexisted','false');
        }else if(countInArray(ParamArray2,"courseid") == 1){
          $(this).closest('tr').attr('courseidexisted','true');
        }
        

        $('#inner-table_'+TriggerId).find('select.condition-param').each(function(){
          $(this).prop('disabled', true).addClass('hide');
        });

        $('#inner-table_'+TriggerId).find('select.condition-operator').each(function(){ 
          var tmp = $(this).val();
          OperatorArray.push(tmp);
          $(this).prop('disabled', true).addClass('hide');
        });

        $('#inner-table_'+TriggerId).find('.condition-value').each(function(){
          var tmp = $(this).val();
          ValueArray.push(tmp);
          $(this).prop("readOnly", true).addClass('hide');
        });
        
        // Pop and display to Span
        $('#inner-table_'+TriggerId).find('.displaySpan-param').each(function(){
          var tmp = ParamArray.shift();
          $(this).removeClass('hide');
          $(this).html(tmp);
        });

        $('#inner-table_'+TriggerId).find('.displaySpan-operator').each(function(){
          var tmp = OperatorArray.shift();
          $(this).removeClass('hide');
          $(this).html(tmp);
        });

        
        $('#inner-table_'+TriggerId).find('.displaySpan-paramValue').each(function(){
          var tmp = ValueArray.shift();
          $(this).removeClass('hide');
          $(this).html(tmp);
        });

        $(this).closest('tr').find('span.displaySpan').removeClass('hide');
        $(this).closest('tr').find('span.condition-icon').addClass('hide');
        $(this).closest('tr').find('button.EditTrigger').removeClass('hide');
        $('#trigger_name_'+TriggerId).prop("readOnly", true).addClass('hide');
        $('#trigger_type_'+TriggerId).prop('disabled', true).addClass('hide');
        $('#message_content_'+TriggerId).prop('disabled', true).addClass('hide');
      // Back-end Update
        var arrayOfTriggerInfo = {
          triggerId: TriggerId,
          triggerName: TriggerName,
          MsgType: TriggerMsgType,
          MsgName: TriggerMsgName,
          OriginalMsgName:originalMsgName
        };


        var arrayOfConditionId=[];
        var arrayOfConditionInfo=[];
        var i;

        for(i=0;i<arrayOfChangedTrigger.length;++i){
          if (arrayOfChangedTrigger[i].TriggerId == TriggerId) {
            arrayOfConditionId = arrayOfChangedTrigger[i].ConditionIdArr;
          }
        }

        for(i = 0; i < (arrayOfConditionId.length) ;++i){
          var conditionId = arrayOfConditionId[i];
          var conditionRow = getConditionRow($(this),conditionId);
          var tmp={};
          tmp.conditionId = conditionId;
          tmp.Param = conditionRow.find('select.condition-param').val();
          tmp.Operator = conditionRow.find('select.condition-operator').val();
          tmp.paramValue= conditionRow.find('input:text.condition-value').val();
          arrayOfConditionInfo.push(tmp);
        }

        var myTriggerInfo = {
          triggerRecord:arrayOfTriggerInfo,
          conditionRecord:arrayOfConditionInfo
        };

        var jsonTriggerInfo = JSON.stringify(myTriggerInfo);

        console.log(arrayOfTriggerInfo);
        console.log(arrayOfConditionInfo);
        $.ajax({
              type     : "POST",
              url      : "update.php", 
              data     : {myTriggerInfo:jsonTriggerInfo},
              success: function(data){
                console.log(data);
              }
        });

        // Remove obj if click save
        var i;
        for (i = 0; i < arrayOfChangedTrigger.length; ++i) {
            if (arrayOfChangedTrigger[i].TriggerId == TriggerId) {
                arrayOfChangedTrigger.splice(i,1);
            }
        }
    // Hide self when clicking
      $(this).addClass('hide');
});


$('#saveAllbtn').on('click',function(){
  
  // Prevent user from proceeding if any trigger names are missing


    $('input:text.addId').each(function(){
      
    });
    var arrayOfTriggerId=[];
    var arrayOfTriggerInfo=[];
    var arrayOfConditionId=[];
    var arrayOfConditionInfo=[];
    var i;
    for(i = 0; i < arrayOfChangedTrigger.length; ++i)
    {
        arrayOfTriggerId.push(arrayOfChangedTrigger[i].TriggerId);
        if ($('#trigger_name_'+arrayOfChangedTrigger[i].TriggerId).val() == '') {
          alert('Please fill out all the Trigger Names for Trigger '+arrayOfChangedTrigger[i].TriggerId+' to save trigger info!');
          return false;
        }
        var j;
        for(j = 0; j < (arrayOfChangedTrigger[i].ConditionIdArr.length) ;++j)
        {
          arrayOfConditionId.push(arrayOfChangedTrigger[i].ConditionIdArr[j]);
        }
    }

    // Push trigger info into array
    for(i=0;i<arrayOfTriggerId.length;++i){
      var courseidCounter = 0;
      var triggerId = arrayOfTriggerId[i];
      var tmp={};
      tmp.triggerId = triggerId;
      tmp.triggerName = $('#trigger_name_'+triggerId).val();
      tmp.MsgType = $('#trigger_type_'+triggerId).val();
      tmp.MsgName = $('#message_content_'+triggerId).val();
      arrayOfTriggerInfo.push(tmp);

      // Check if "courseid" duplicate exists
      var row = getTriggerRow(triggerId);
      row.find('select.condition-param').each(function(){
        if ($(this).val() == 'courseid') {
          courseidCounter++;
        }
      });
      if (courseidCounter > 1) {
        alert("One trigger can only have one courseid as condition. Please check your conditions to proceed!");
        row.css("background-color", "red");
        courseidCounter = 0;
        return false;
      }
      row.css("background-color", "");
    }
    console.log(courseidCounter);
    // Push condition info into array
    for(i=0;i<arrayOfConditionId.length;++i){
      var conditionId = arrayOfConditionId[i];
      var saveButtonObj;
      $('td.condt_id').each(function(){
        if ($(this).html() == conditionId){
          saveButtonObj = $(this).closest('div').closest('tr').find('button.saveTriggerRow');
        }
      });
      var conditionRow = getConditionRow(saveButtonObj,conditionId);
      var tmp={};
      tmp.conditionId = conditionId;
      tmp.Param = conditionRow.find('select.condition-param').val();
      tmp.Operator = conditionRow.find('select.condition-operator').val();
      tmp.paramValue= conditionRow.find('input:text.condition-value').val();
      arrayOfConditionInfo.push(tmp);
    }
    


    // Update in DB
    var allRecord = {
      triggerRecord:arrayOfTriggerInfo,
      conditionRecord:arrayOfConditionInfo
    };
    var jsonRecord = JSON.stringify(allRecord);

    $.ajax({
        type     : "POST",
        url      : "update.php", 
        data     : {allRecord:jsonRecord},
        success  : function() {
           location.reload();
        }
    });
});


// Eidt button
$('.EditTrigger').on('click',function(){
  var triggerRow = $(this).closest('tr');
  var currentTriggerId = $(this).closest('tr').find('td.trig_id').html();
  var trigger_MsgType = $(this).closest('tr').find('#SpanTriggerMsgType_'+currentTriggerId).html();
  var trigger_MsgContent = $(this).closest('tr').find('#SpanTriggerMsgName_'+currentTriggerId).html();
  var condition_arr = {
    param:[],
    operator:[],
    paramValue:[]
  };

  var courseidExistedCounter = 0;
  $(this).closest('tr').find('span.displaySpan').addClass('hide');
  $(this).closest('tr').find('span.condition-icon').removeClass('hide');
  $(this).closest('tr').find('button.saveTriggerRow').removeClass('hide');
  
  $('#trigger_name_'+currentTriggerId).prop("readOnly", false).removeClass('hide');
  $('#trigger_type_'+currentTriggerId).prop('disabled', false).removeClass('hide').val(trigger_MsgType);
  $('#message_content_'+currentTriggerId).prop('disabled', false).removeClass('hide').val(trigger_MsgContent);

  // Condition table
    // Push Param, operators and paramValues in arrays
      $(this).closest('tr').find('span.displaySpan-param').each(function(){
        condition_arr.param.push($(this).html());
          if ($(this).html() == "courseid") {
            courseidExistedCounter++;
            triggerRow.attr('courseidExisted','true');
          }
      });
      if (courseidExistedCounter == 0) {
        triggerRow.attr('courseidExisted','false');
      }
      $(this).closest('tr').find('span.displaySpan-operator').each(function(){
        condition_arr.operator.push($(this).html());
      });
      $(this).closest('tr').find('span.displaySpan-paramValue').each(function(){
        condition_arr.paramValue.push($(this).html());
      });

    // Enable edit and show existing data
      $('#inner-table_'+currentTriggerId+ ' .condition-param').each(function(){
        var value = condition_arr.param.shift();
        $(this).prop('disabled', false).removeClass('hide').val(value);
      });

      $('#inner-table_'+currentTriggerId+ ' .condition-operator').each(function(){
        var value = condition_arr.operator.shift();
        $(this).prop('disabled', false).removeClass('hide').val(value);
      });

      $('#inner-table_'+currentTriggerId+ ' .condition-value').each(function(){
        var value = condition_arr.paramValue.shift();
        $(this).prop("readOnly", false).removeClass('hide').val(value);
      });


      // Add object to arrayOfChangedTrigger
      if (!existedTrigger(arrayOfChangedTrigger,currentTriggerId)) {
          arrayOfChangedTrigger.push({
            TriggerId:currentTriggerId,
            ConditionIdArr:[]
        })
      }
      // Hide self when clicking 
      $(this).addClass('hide');
});


// *********************************************************************************************************************************
// ***************                        This section has all Pull down list fucntions                              ***************
// *********************************************************************************************************************************
  // Add class to changed condition "select"s
    $('select.condition-param').on('change',function(){
        getUpdateInfo($(this));
        return false;
    });

    $('select.condition-operator').on('change',function(){
        getUpdateInfo($(this));
    });

    $('input:text.condition-value').on('input',function(){
        getUpdateInfo($(this));
    });

    $('select.msg_type').on('change',function(){
        var Msg_type = $(this).val();
        var Msg_content = $(this).closest('tr').find('select.msg_content');
         if(Msg_type == "bar"){
            Msg_content.html("<option value='Recommand to Friends'>Recommand to Friends</option>"+"<option value='Other Features Reminder'>Other Features Reminder</option>"
              +"<option value='Bar 3'>Bar 3</option>"+"<option value='Bar 4'>Bar 4</option>");
          }else if(Msg_type == "email"){
            Msg_content.html("<option value='quiz_remder'>Quiz Function Reminder</option><option value='expire_remder'>Expire Reminder</option>");
          }
    });
    // Check if there's duplicate name exist
    function HasTriggerNameDuplicate(triggerNameArray){
        return (new Set(triggerNameArray)).size !== triggerNameArray.length;
    }
    

    // Store ID's that need to be updated in the DB
    function getUpdateInfo(e){
        var TriggerId = e.closest('div').closest('tr').find('td.trig_id').html();
        var conditionId = e.closest('tr').find('td.condt_id').html() ;
        var result = arrayOfChangedTrigger.find(function( obj ) {
            return obj.TriggerId == TriggerId;
        });
        if (!existedCondition(result.ConditionIdArr,conditionId)) {
            result.ConditionIdArr.push(conditionId);      
        }

        // Add this class only for debug purposes, need to be deleted when in production
        $(this).addClass('condition-changed');
    }
    // Check if condition is existed in array  
    function existedCondition(ConditionIdArr,target){
      var i;
      for ( i = 0; i < ConditionIdArr.length; i++) {
        if (ConditionIdArr[i] == target) {
          return true;
        }
      }
      return false;
    }


    // Check if trigger is existed in array 
    function existedTrigger(TriggerArr,target){
      var i;
      for ( i = 0; i < TriggerArr.length; i++) 
      {
        if (TriggerArr[i].TriggerId == target) {
          return true;
        }
      }
      return false;
    }


    // Return the condition selected
    function getConditionRow(saveButtonObj,target){
      var row;
            saveButtonObj.closest('tr').find('td.condt_id').each(function() {
              if ($(this).html() == target) {
                  row = $(this).closest('tr'); 
              }
          });
          return row;
    }


    // Return the Trigger Row as per trigger id
    function getTriggerRow(triggerId){
      var row;
      var TriggerId = Number(triggerId);
      $('td.trig_id').each(function(){
        if (Number($(this).html()) == triggerId) {
          row = $(this).closest('tr');
          return false;
        }
      });
      return row;
    }

    // This sections is passing trigger info to api using ajax
$('#test-ajax').on('click',function(){
  console.log(arrayOfChangedTrigger);
  // var row = getTriggerRow(91);
});

    // Return how many times an element appear in an array
    function countInArray(array, what) {
          var count = 0;
          for (var i = 0; i < array.length; i++) {
              if (array[i] === what) {
                  count++;
              }
          }
          return count;
        }

});

