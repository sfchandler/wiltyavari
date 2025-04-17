<?php
session_start();

require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
  $msg = base64_encode("Access Denied");
  header("Location:login.php?error_msg=$msg");
}

?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<?php include "template/header.php";?>
	</head>
	<body>
		<!-- HEADER -->
		<header id="header">
			<?php include "template/top_menu.php"; ?>
		</header>
		<!-- END HEADER -->

		<!-- Left panel : Navigation area -->
		<!-- Note: This width of the aside area can be adjusted through LESS variables -->
		<aside id="left-panel">

			<!-- User info -->
			<div class="login-info">
				<?php include "template/user_info.php"; ?>
			</div>
			<!-- end user info -->
				<?php include "template/navigation.php"; ?>
			<span class="minifyme" data-action="minifyMenu"> 
				<i class="fa fa-arrow-circle-left hit"></i> 
			</span>

		</aside>
		<!-- END NAVIGATION -->

		<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- MAIN CONTENT -->
			<div id="content" class="container-body">
                <div align="center">
                    <table class="table table-responsive" style="width: 90%">
                        <thead>
                          <tr>
                            <th>JobOrder Client</th>
                            <th>State</th>
                            <th>JobOrder Department</th>
                            <th>Note</th>
                            <th>Number of Casuals</th>
                            <th></th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><select name="clientId" id="clientId" class="form-control-sm">
                                </select>
                            </td>
                              <td><select name="stateId" id="stateId"  class="form-control-sm">
                                  </select></td>
                              <td><select name="departmentId" id="departmentId" class="form-control-sm">
                                  </select></td>
                              <td><textarea name="note" id="note" cols="25" rows="3"></textarea></td>
                            <td><input type="text" name="num_casual" id="num_casual" size="5" value="" class="form-control-sm"></td>
                            <td><button name="jobBtn" id="jobBtn" class="btn btn-info form-control-sm">Add Job</button></td>
                          </tr>
                        </tbody>
                      </table>
                      <div>
                          <table class="table table-responsive">
                              <thead>
                                <tr>
                                  <th>JobOrderID</th>
                                  <th>Client</th>
                                  <th>State</th>
                                  <th>Department</th>
                                  <th>Note</th>
                                  <th>Additional Notes</th>
                                  <th>Number of Casuals</th>
                                  <th>Job Creator</th>
                                  <th>JobOrder Time</th>
                                  <th>Status</th>
                                  <th>Attachments</th>
                                  <th></th>
                                  <th>Action</th>
                                  <th>Action View</th>
                                </tr>
                              </thead>
                              <tbody id="jobOrderDisplay">

                              </tbody>
                            </table>
                      </div>
                </div>
            </div>
			<!-- END MAIN CONTENT -->
            <div id="viewPopup" style="width:500px; display:block"></div>
            <div id="attachPopup" style="width:500px; display:block">
                <form id="frmFile" action="jobOrderUpload.php" class="smart-form" enctype="multipart/form-data" method="post">
                    <input type="hidden" name="jbId" id="jbId" value=""/>
                    <input class="input" type="file" id="file" name="file">Browse</span>
                    <button class="uploadBtn btn btn-primary btn-sm" type="button" value="Upload"><i class="glyphicon glyphicon-upload"></i>Upload</button>
                </form>
            </div>
			</div>

		<!-- END MAIN PANEL -->

		<!-- PAGE FOOTER -->
		<?php include "template/footer.php"; ?>
		<!-- END PAGE FOOTER -->		
		<!-- END SHORTCUT AREA -->
		<?php include "template/scripts.php"; ?>
		<!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/additional-methods.js"></script>
        <!-- JQUERY MASKED INPUT -->
        <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        <!-- JQUERY FORM PLUGIN -->
        <script src="js/jqueryform/jquery.form.js"></script>
		
		<script>
            $(document).ready(function(){
                var viewJobOrderLog;
                var attachDialog;
                loadJobOrders();
                function loadJobOrders(){
                    var action = 'View';
                    $.ajax({
                        url:"processJobOrder.php",
                        type:"POST",
                        dataType:"html",
                        data:{action:action},
                        success: function(data){
                            $('#jobOrderDisplay').html('');
                            $('#jobOrderDisplay').html(data);
                        }
                    });
                }
                populateClients();
                function populateClients(){
                    var action = 'scheduling';
                    $.ajax({
                        url:"getClients.php",
                        type:"POST",
                        dataType:"html",
                        data:{action:action},
                        success: function(data){
                            $('#clientId').html('');
                            $('#clientId').html(data);
                        }
                    });
                }
                $(document).on('change','#clientId',function(){
                    var clientId = $('#clientId :selected').val();
                    var action = 'scheduling';
                    $.ajax({
                        url:"getStateByClient.php",
                        type:"POST",
                        dataType:"html",
                        data:{clientId:clientId},
                        success: function(data){
                            $('#stateId').html('');
                            $('#stateId').html(data);
                        }
                    });
                    $.ajax({
                        url:"getClientPositionsList.php",
                        type:"POST",
                        dataType:"html",
                        data:{clientId:clientId,action:action},
                        success: function(data){
                            $('#expPosition').html('');
                            $('#expPosition').html(data);
                        }
                    });
                });
                $(document).on('click','#clientId',function(){
                    var clientId = $('#clientId :selected').val();
                    var action = 'scheduling';
                    $.ajax({
                        url:"getStateByClient.php",
                        type:"POST",
                        dataType:"html",
                        data:{clientId:clientId},
                        success: function(data){
                            $('#stateId').html('');
                            $('#stateId').html(data);
                        }
                    });
                    $.ajax({
                        url:"getClientPositionsList.php",
                        type:"POST",
                        dataType:"html",
                        data:{clientId:clientId,action:action},
                        success: function(data){
                            $('#expPosition').html('');
                            $('#expPosition').html(data);
                        }
                    });
                });
                $(document).on('click','#stateId',function(){
                    var clientId = $('#clientId :selected').val();
                    var stateId = $('#stateId :selected').val();
                    var action = 'scheduling';
                    $.ajax({
                        url:"getDepartment.php",
                        type:"POST",
                        dataType:"html",
                        data:{clientId:clientId,stateId:stateId,action:action},
                        success: function(data){
                            $('#departmentId').html('');
                            $('#departmentId').html(data);
                        }
                    });
                });
                $(document).on('click','#jobBtn',function(){
                    var clientId = $('#clientId :selected').val();
                    var stateId = $('#stateId :selected').val();
                    var departmentId = $('#departmentId :selected').val();
                    var note = $('textarea#note').val();
                    var numCasuals = $('#num_casual').val();
                    var action = 'Add';
                    $.ajax({
                        url:"processJobOrder.php",
                        type:"POST",
                        dataType:"html",
                        data:{clientId:clientId,stateId:stateId,departmentId:departmentId,note:note,numCasuals:numCasuals,action:action},
                        success: function(data){
                            $('#jobOrderDisplay').html('');
                            $('#jobOrderDisplay').html(data);
                        }
                    });
                });
                $(document).on('click','.filledBtn',function() {
                    var id = $(this).closest('tr').attr('id');
                    var action = 'Update';
                    var status = 'Filled';
                    $.ajax({
                        url:"processJobOrder.php",
                        type:"POST",
                        dataType:"html",
                        data:{id:id,status:status,action:action},
                        success: function(data){
                            $('#jobOrderDisplay').html('');
                            $('#jobOrderDisplay').html(data);
                        }
                    });

                });
                $(document).on('click','.noteBtn', function (){
                    var jobOrderId = $(this).closest('td').attr('data-joborderid');
                    var additionalNote = $(this).closest('td').find('textarea#addionalNote').val();
                    var action = 'Note';
                    $.ajax({
                        url:"processJobOrder.php",
                        type:"POST",
                        dataType:"html",
                        data:{jobOrderId:jobOrderId,additionalNote:additionalNote,action:action},
                        success: function(data){
                            $('#jobOrderDisplay').html('');
                            $('#jobOrderDisplay').html(data);
                        }
                    });
                });
                attachDialog = $("#attachPopup").dialog({
                    autoOpen: false,
                    height: 200,
                    width: 300,
                    modal: true,
                    open: function(event, ui) {
                        var id = $('#jbId').val(attachDialog.data('id'));
                    },
                    buttons: {
                        OK: function(){
                            attachDialog.dialog("close");
                        }
                    }
                });
                $(document).on('click','.uploadBtn',function(){
                     var id = $('#jbId').val();
                     var file = $('#file').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', file);
                    form_data.append('jobId',id);
                    $.ajax({
                        url: 'jobOrderUpload.php',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        success: function(data){
                            if(data == 'SUCCESS') {
                                attachDialog.dialog("close");
                                location.reload();
                            }else{
                                alert('Error Uploading document');
                            }
                        }
                    });
                });
                $(document).on('click','.attachBtn',function(){
                    var id = $(this).closest('td').attr('data-joborderid');
                    $('#jbI').val(id);
                    attachDialog.data('id',id);
                    attachDialog.dialog("open");
                    attachDialog.dialog("option", "title", 'Job Order Attachments');
                    attachDialog.dialog('option', 'position', {
                        my: 'top', at: 'top',of: target
                    });
                });



                $(document).on('click','.viewBtn',function(){
                    var id = $(this).closest('td').attr('data-joborderid');
                    var action = 'LogView';
                    $.ajax({
                        url:"processJobOrder.php",
                        type:"POST",
                        dataType:"html",
                        data:{id:id,action:action},
                        success: function(data){
                            $('#viewPopup').html('');
                            $('#viewPopup').html(data);
                        }
                    });
                    viewJobOrderLog.dialog("open");
                    viewJobOrderLog.dialog("option", "title", 'Job Order Log');
                    viewJobOrderLog.dialog('option', 'position', {
                        my: 'top', at: 'top',of: target
                    });
                });
                viewJobOrderLog = $("#viewPopup").dialog({
                    autoOpen: false,
                    height: 400,
                    width: 550,
                    modal: true,
                    open: function(event, ui) {
                    },
                    buttons: {
                        OK: function(){
                            viewJobOrderLog.dialog("close");
                        }
                    }
                });
            });
		</script>
        
        
	</body>

</html>