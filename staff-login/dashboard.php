<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
if ($_SESSION['staffSession'] == '')
{
    $msg = base64_encode("Session Expired/Access Denied. Please click/touch on the SMS received.");
    header("Location:index.php?error_msg=$msg");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Shift Information</title>
    <!-- CSS only -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<div class="container">
    <div> Welcome, <?php echo getCandidateFullName($mysqli,$_SESSION['staffSession']); ?>,</div>
    <div class="error"></div>
    <br>
    <a href="logout.php">Logout</a>
    <br>
    <div class="card">
        <div class="card-header">
            Please select any that apply to Accept Shifts below
        </div>
        <div class="card-body">
            <form name="frmQuestion" action="">
                <div><input type="checkbox" name="question1" value="q1" class="group1"/> I have new/recent onset symptoms consistent with COVID-19</div>
                <div><input type="checkbox" name="question2" value="q2" class="group1"/> I have returned from overseas in the past 14 days</div>
                <div><input type="checkbox" name="question3" value="q3" class="group1"/> I have been identified as a close contact of a confirmed case of COVID-19 and am in quarantine</div>
                <div><input type="checkbox" name="question4" value="q4" id="none" class="none"/> None of the above</div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col col-md-12">
            <div class="card">
                <div class="card-header">
                    Shift Information
                </div>
                <div id="shiftInfo" class="card-body">
                </div>
                <div class="card-header">
                    Shift History
                </div>
                <div id="shiftHistory" class="card-body"></div>
            </div>
        </div>
    </div>
</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    $(function(){
        $('button.acceptBtn').hide();
        $('button.informBtn').hide();
        $('#none').on('click',function(){
            if (this.checked) {
                $('input.group1').prop('checked', false);
                $('button.acceptBtn').show();
            }else{
                $('button.acceptBtn').hide();
            }
        });
        $('.group1').on('click',function(){
            $('#none').prop('checked', false);
            $('button.acceptBtn').hide();
            $('button.informBtn').show();
        });
       loadShiftData();
       function loadShiftData(){
           var action = 'INFO';
           $.ajax({
               type:"POST",
               url: "shiftInfo.php",
               data: { action : action },
               dataType: 'html',
               success: function (data) {
                   $('#shiftInfo').html('');
                   $('#shiftInfo').html(data);
               }
           }).done(function(data){
               $('button.acceptBtn').hide();
           });
       }
        loadShiftHistory();
        function loadShiftHistory(){
            var action = 'HISTORY';
            $.ajax({
                type:"POST",
                url: "shiftInfo.php",
                data: { action : action },
                dataType: 'html',
                success: function (data) {
                    $('#shiftHistory').html('');
                    $('#shiftHistory').html(data);
                }
            }).done(function(data){
            });
        }
       $(document).on('click','.acceptBtn',function (){
           var action = 'ACCEPT';
           var shiftId = $(this).closest('td').attr('data-shiftid');
           var status = 'CONFIRMED';
           var q1 = $('input[name=question1]:checked').val();
           var q2 = $('input[name=question2]:checked').val();
           var q3 = $('input[name=question3]:checked').val();
           var q4 = $('input[name=question4]:checked').val();
           $.ajax({
               type:"POST",
               url: "shiftInfo.php",
               data: { action : action,shiftId:shiftId,status:status,q1:q1,q2:q2,q3:q3,q4:q4},
               dataType: 'text',
               success: function (data) {
                   $('.error').html('');
                   $('.error').html(data);
                   loadShiftData();
                   loadShiftHistory();
               }
           });
       });

        $(document).on('click','.rejectBtn',function (){
            var action = 'REJECTED';
            var shiftId = $(this).closest('td').attr('data-shiftid');
            var status = 'REJECTED';
            var q1 = $('input[name=question1]:checked').val();
            var q2 = $('input[name=question2]:checked').val();
            var q3 = $('input[name=question3]:checked').val();
            var q4 = $('input[name=question4]:checked').val();
            $.ajax({
                type:"POST",
                url: "shiftInfo.php",
                data: { action : action,shiftId:shiftId,status:status,q1:q1,q2:q2,q3:q3,q4:q4},
                dataType: 'text',
                success: function (data) {
                    $('.error').html('');
                    $('.error').html(data);
                    loadShiftData();
                    loadShiftHistory();
                }
            });
        });
    });
</script>
</body>
</html>