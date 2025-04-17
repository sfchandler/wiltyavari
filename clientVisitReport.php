<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
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

			<!-- RIBBON -->
			<div id="ribbon">
				<span class="ribbon-button-alignment"> 
				</span>
				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<?php include "template/breadcrumblinks.php"; ?>
				</ol>
				<!-- end breadcrumb -->
			</div>
			<!-- END RIBBON -->
			<!-- MAIN CONTENT -->
			<div id="content">
            	<div class="content-body no-content-padding">
					<div style="padding-left:10px;">
                    	<h1>Client Visit Information</h1>
                        <div id="msg" class="error"></div>
                    </div>
                    <div style="width:100%">
                        <div id="client_visit_form">
                            <form name="frmClientVisit" id="frmClientVisit" class="smart-form" method="post">
                                <div class="row" style="padding: 20px 20px 20px 20px">
                                    <section class="col col-lg-2">
                                        <label for="client">Client</label>
                                        <select name="client_id" id="client_id" class="form-control"></select>
                                    </section>
                                    <section class="col col-lg-2">
                                        <label for="date">Date</label><span style="color: red">*</span>
                                        <input type="text" name="client_visit_date" id="client_visit_date" class="form-control" readonly/>
                                    </section>
                                    <section class="col col-lg-2">
                                        <label for="consultant">Consultant</label>
                                        <select name="consultantId" id="consultantId" class="form-control">
                                            <?php echo getConsultantListDropdown($mysqli); ?>
                                        </select>
                                    </section>
                                    <section class="col col-lg-2">
                                        <br>
                                        <input type="hidden" name="action" id="action" value="ADD"/>
                                        <input type="hidden" name="id" id="id" value="0"/>
                                        <button type="submit" name="updateClientVisitBtn" id="updateClientVisitBtn" class="btn btn-lg btn-info">ADD INFO</button>
                                    </section>
                                </div>
                                <div class="row" style="padding: 0px 20px 20px 20px">
                                    <section class="col col-lg-4">
                                        <label for="notes">Notes</label><span style="color: red">*</span>
                                        <textarea name="notes" id="notes" cols="30" rows="10" class="form-control"></textarea>
                                    </section>
                                    <section class="col col-lg-4">
                                        <label for="issues">Issues</label>
                                        <textarea name="issues" id="issues" cols="30" rows="10" class="form-control"></textarea>
                                    </section>
                                    <section class="col col-lg-2">
                                        <label for="follow_up_date">Follow up date</label><span style="color: red">*</span>
                                        <input type="text" name="follow_up_date" id="follow_up_date" class="form-control" readonly/>
                                    </section>
                                </div>
                            </form>
                        </div>
                        <div id="client_visit_data" style="padding: 20px 20px 20px 20px">
                            <table id="dataTbl" class="table table-striped table-bordered">
                                <thead>
                                  <tr>
                                    <th>Client</th>
                                    <th>Date</th>
                                    <th>Consultant</th>
                                    <th>Notes</th>
                                    <th>Issues</th>
                                    <th>Follow up date</th>
                                    <th>Created at</th>
                                    <th>Modified at</th>
                                    <th>Action <button id="exportReport" class="btn btn-sm btn-success" title="Download Excel"><i class="fa fa-file-excel-o"></i></button></th>
                                  </tr>
                                </thead>
                                <tbody id="tblBody">
                                <?php echo displayClientVisits($mysqli); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                </div>
			</div>
			<!-- END MAIN CONTENT -->
		</div>
		<!-- END MAIN PANEL -->
		<?php include "template/footer.php"; ?>
		<?php include "template/scripts.php"; ?>
        <!-- DATE RANGE PICKER -->
        <script type="text/javascript" src="js/daterangepicker/moment.js"></script>
        <script type="text/javascript" src="js/daterangepicker/daterangepicker.js"></script>
		<!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="js/plugin/jquery-validate/additional-methods.js"></script>
        <!-- JQUERY MASKED INPUT -->
        <script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        <!-- JQUERY FORM PLUGIN -->
        <script src="js/jqueryform/jquery.form.js"></script>
        <script type="text/javascript">
            $(function(){
                $('input[name="client_visit_date"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false
                });
                $('input[name="client_visit_date"]').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
                    $('#client_visit_date').val(picker.startDate.format('YYYY-MM-DD'));
                });
                $('input[name="follow_up_date"]').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoUpdateInput: false
                });
                $('input[name="follow_up_date"]').on('apply.daterangepicker', function(ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD'));
                    $('#follow_up_date').val(picker.startDate.format('YYYY-MM-DD'));
                });
                var thead_length = $('#dataTbl thead th').length;
                $('#dataTbl thead th').each(function(index) {
                    if (index === thead_length - 1) {
                    }else {
                        var title = $('#dataTbl thead th').eq($(this).index()).text();
                        $(this).html(title + '\n<input type="text" />');
                    }
                });
                var table = $('#dataTbl').DataTable({
                    "bPaginate": true,
                    "bLengthChange": false, /* show entries off */
                    "bFilter": true,
                    "bInfo": true,
                    "bAutoWidth": false,
                    "order": [[7, "desc"]],
                    "pageLength": 100
                });
                table.columns().eq(0).each(function(colIdx) {
                    $('input', table.column(colIdx).header()).on('keyup change', function() {
                        table
                            .column(colIdx)
                            .search(this.value)
                            .draw();
                    });

                    $('input', table.column(colIdx).header()).on('click', function(e) {
                        e.stopPropagation();
                    });
                });
                getClients();
                loadClientVisits();
				 function getClients(){
                      let action = 'SINGLESELECT';
					  $.ajax({
							  url:"getClients.php",
							  type:"POST",
                              data:{action:action},
							  dataType:"html",
							  success: function(data){
                                  console.log('.....'+data);
								  $('#client_id').html('');
								  $('#client_id').html(data);
							  }
                      });
				 }
                 function loadClientVisits(){
                     let action = 'DISPLAY';
                     $.ajax({
                         url:"processClientVisit.php",
                         type:"POST",
                         data:{
                             action: action
                         },
                         dataType:"html",
                         success: function(data){
                             $('#tblBody').html('');
                             $('#tblBody').html(data);
                         }
                     });
                 }
                 $(document).on('click', '#updateClientVisitBtn', function() {
                      var errorClass = 'invalid';
                      var errorElement = 'em';
                      var frmClientVisit = $("#frmClientVisit").validate({
                          errorClass	: errorClass,
                          errorElement	: errorElement,
                          highlight: function(element) {
                              $(element).parent().removeClass('state-success').addClass("state-error");
                              $(element).removeClass('valid');
                          },
                          unhighlight: function(element) {
                              $(element).parent().removeClass("state-error").addClass('state-success');
                              $(element).addClass('valid');
                          },
                          rules: {
                              notes: {
                                required:true
                              }
                          },
                          messages: {
                                notes: {
                                    required: "Please enter Notes"
                                }
                          },
                          submitHandler: function (form) {
                              let action = $('#action').val();
                              let id = $('#id').val();
                              let consultantId = $('#consultantId :selected').val();
                              let client_visit_date = $('#client_visit_date').val();
                              let client_id = $('#client_id :selected').val();
                              let notes = $('textarea#notes').val();
                              let issues = $('textarea#issues').val();
                              let follow_up_date = $('#follow_up_date').val();
                              $.ajax({
                                    url:"processClientVisit.php",
                                    type:"POST",
                                    data:{
                                        id:id,
                                        action: action,
                                        consultantId: consultantId,
                                        client_visit_date: client_visit_date,
                                        client_id: client_id,
                                        notes:notes,
                                        issues: issues,
                                        follow_up_date: follow_up_date
                                    },
                                    dataType:"html",
                                    success: function(data){
                                        $('#msg').html('');
                                        $('#msg').html(data);
                                        loadClientVisits();
                                        $('#updateClientVisitBtn').html('ADD INFO');
                                        $('#frmClientVisit').trigger('reset');
                                    }
                                });
                          },
                          errorPlacement : function(error, element) {
                              error.insertAfter(element.parent());
                          }
                        });
                 });
                 $(document).on('click','.editClientVisitBtn',function (){
                    $('#updateClientVisitBtn').html('UPDATE INFO');
                    let id = $(this).closest('tr').attr('id');
                    $('#id').val(id);
                    $('#action').val('UPDATE');
                    let cons_id = $(this).closest('tr').attr('data-cons-id');
                    $('#consultantId').val(cons_id);
                    let visit_date = $(this).closest('tr').attr('data-visit-date');
                    $('#client_visit_date').val(visit_date);
                    let client_id = $(this).closest('tr').attr('data-client-id');
                    $('#client_id').val(client_id);
                    let notes = $(this).closest('tr').attr('data-notes');
                    $('textarea#notes').val(notes);
                    let issues = $(this).closest('tr').attr('data-issues');
                    $('textarea#issues').val(issues);
                    let followup = $(this).closest('tr').attr('data-followup');
                    $('#follow_up_date').val(followup);
                 });
                 $(document).on('click','#exportReport', function(){
                     let action = 'EXPORT';
                     $.ajax({
                         url:"processClientVisit.php",
                         type:"POST",
                         data:{
                             action: action
                         },
                         dataType:"html",
                         success: function(data){
                             console.log('.......'+data);
                             window.open(data);
                         }
                     });
                 });
			});
		</script>
	</body>

</html>