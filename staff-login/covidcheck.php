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
    <title>Employee COVID Health Declaration</title>
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
            COVID 19 Health Declaration
        </div>
        <div class="card-body">
            <form name="frmQuestion" action="">
                <div style="padding-left: 30px;font-weight: bold">
                    Shift on <?php echo date('d-m-Y', strtotime($_REQUEST['shift_date'])). ' at '.getClientNameByClientId($mysqli,$_REQUEST['client_id']); ?>
                </div>
                <br>
                <div style="padding-left: 30px;">
                    <span style="font-size: 12pt">Please select answer <b>Yes</b> or <b>No</b> to the below statements</span>
                </div>
                <br><br>
                <div style="padding-left: 30px;">
                    <ul>
                        <li style="list-style-type: disc"> I am free of COVID-19 symptoms (Loss or change in sense of smell or taste, Fever, Chills or sweats, Cough, Sore throat, Shortness of breath, Runny nose)</li>
                        <li style="list-style-type: disc"> I have not been in contact with a confirmed case of COVID-19 in the last 14 days</li>
                        <li style="list-style-type: disc"> I am not currently directed to isolate or quarantine</li>
                    </ul>
                </div>
                <br>
                <div style="padding-left: 30px;">
                    <input type="hidden" name="username" id="username" value="<?php echo $_SESSION['staffSession']; ?>">
                    <input type="hidden" name="shift_id" id="shift_id" value="<?php echo $_REQUEST['shift_id']; ?>">
                    <input type="hidden" name="shift_date" id="shift_date" value="<?php echo $_REQUEST['shift_date']; ?>">
                    <input type="hidden" name="client_id" id="client_id" value="<?php echo $_REQUEST['client_id']; ?>">
                    <input type="radio" name="covidAnswer" value="YES">&nbsp;Yes
                    &nbsp;
                    <input type="radio" name="covidAnswer" value="NO">&nbsp;NO
                </div>
                <br>
                <div style="padding-left: 30px;">
                    <button type="button" class="submitBtn btn btn-success">Submit</button>
                </div>
                <br>
                <div style="padding-left: 30px;">
                    If your answer is <span style="color: red; font-weight: bold">NO</span> please call 1300 49 94 49 immediately
                </div>
            </form>
            <br>

        </div>
    </div>
</div>

<script src="js/jquery-3.5.1.min.js"></script>
<script src="js/jquery-ui.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
    $(function(){
       $(document).on('click','.submitBtn',function (){

           var covidAnswer = $('input[name=covidAnswer]:checked').val();
           var username = $('#username').val();
           var shift_id = $('#shift_id').val();
           var shift_date = $('#shift_date').val();
           var client_id = $('#client_id').val();
           console.log('........'+covidAnswer+username);
           $.ajax({
               type:"POST",
               url: "updateCovidQuestion.php",
               data: { username : username,covidAnswer:covidAnswer,shift_id:shift_id,shift_date:shift_date,client_id:client_id},
               dataType: 'text',
               success: function (data) {
                   console.log('........'+data);
                   $('.error').html('');
                   $('.error').html(data);
               }
           });
       });

    });
</script>
</body>
</html>