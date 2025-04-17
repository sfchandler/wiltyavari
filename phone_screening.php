<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'CONSULTANT') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$consId = getConsultantId($mysqli, $_SESSION['userSession']);
?>

<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
    <style>

        .error{
            color: red;
        }
        .invalid{
            color: red;
        }
        label{
            font-weight: normal;
        }
        .outer-panel{
            margin: 0 auto;
            padding: 10px 50px 10px 50px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
            width: 99%;
        }
        /*body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }*/
        .table th, .table td {
            border-top: none !important;
        }
        .h3box {
            margin:50px 0;
            padding:10px 10px;
            border:1px solid #eee;
            background:#f9f9f9;
        }

        * {
            -webkit-box-sizing:border-box;
            -moz-box-sizing:border-box;
            box-sizing:border-box;
        }

        *:before, *:after {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }

        .clearfix {
            clear:both;
        }

        .text-center {text-align:center;}

       .question{
            text-align: justify;
            padding-left: 0px;
       }

        pre {
            display: block;
            padding: 9.5px;
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.42857143;
            color: #333;
            word-break: break-all;
            word-wrap: break-word;
            background-color: #F5F5F5;
            border: 1px solid #CCC;
            border-radius: 4px;
        }
        .header {
            padding:20px 0;
            position:relative;
            margin-bottom:10px;

        }
        .header:after {
            content:"";
            display:block;
            height:1px;
            background:#eee;
            position:absolute;
            left:30%; right:30%;
        }
        .header h2 {
            font-size:3em;
            font-weight:300;
            margin-bottom:0.2em;
        }
        .header p {
            font-size:14px;
        }
        #a-footer {
            margin: 20px 0;
        }
        .new-react-version {
            padding: 20px 20px;
            border: 1px solid #eee;
            border-radius: 20px;
            box-shadow: 0 2px 12px 0 rgba(0,0,0,0.1);

            text-align: center;
            font-size: 14px;
            line-height: 1.7;
        }
        .new-react-version .react-svg-logo {
            text-align: center;
            max-width: 60px;
            margin: 20px auto;
            margin-top: 0;
        }
        p.selection{
            padding-left: 20px;
        }
        .jb_desc{
            border: 1px solid black;
            width: 100%;
            height: 400px;
            overflow: scroll;
        }
        fieldset{
            border: none;

        }
        legend{
            color: #0c7cd5;
        }
    </style>
</head>
<body>
<header id="header">
    <?php include "template/top_menu.php";
    if ($_REQUEST['error_msg'] <> '') {
        echo base64_decode($_REQUEST['error_msg']);
    } ?>
</header>
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
    </div>
    <!-- END RIBBON -->
    <!-- MAIN CONTENT -->
    <div id="content">
        <br><br>
        <div class="outer-panel">
            <br>
            <span style="text-align: center"><div class="h3box"><i class="fa fa-2x fa-phone-square" style="color: #1EB7B5"></i><span style="font-size: 18pt;">&nbsp;PHONE INTERVIEW</span></div></span>
            <div id="msg" class="msg error"></div>
            <div class="container-lg">
                <form name="frmPhr" id="frmPhr" action="" method="post" class="smart-form">
                <div class="row">
                    <section class="col-lg-6">
                        <fieldset>
                            <b style="color: #0c7cd5; font-weight: bold; font-size: 11pt;">
                                Candidate ID: <?php echo $_REQUEST['canId']; ?>
                                <br>
                                Name: <?php echo getCandidateFullName($mysqli,$_REQUEST['canId']); ?></b>
                        </fieldset>
                        <fieldset>
                            <legend>Select client & position</legend>
                            <label for="client_id">Client<select name="client_id" id="client_id" class="form-control"></select></label>
                            <label for="position_id">Position<select name="position_id" id="position_id" class="form-control"></select></label>
                            <input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $_REQUEST['canId']; ?>">
                            <input type="hidden" name="cons_id" id="cons_id" value="<?php echo $_REQUEST['consId']; ?>">
                        </fieldset>
                        <fieldset>
                            <legend>Introduction</legend>
                            <p>Good morning <?php echo $_REQUEST['first_name'].' '.$_REQUEST['last_name']; ?>, I am <?php echo getConsultantName($mysqli,$_REQUEST['consId']); ?> calling from Chandler Personnel regarding a job opening that you have applied
                                <br><br>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question"> Do you have 10-20 minutes to talk?</span>
                            </p>
                            <p class="selection">
                                <input type="radio" name="q1" value="Yes" class="q1 form-control-sm"/> Yes
                                <br>
                                <input type="radio" name="q1" value="No" class="q1 form-control-sm"/> No
                            </p>
                            <br>
                            <textarea name="smsText" id="smsText" cols="20" rows="5" class="form-control" style="display: none" maxlength="160" placeholder="SMS text maximum characters 160"></textarea>
                            <br>
                            <table id="smsRecipients" class="table" style="display: none">
                                <thead>
                                    <tr>
                                        <th>NAME</th>
                                        <th>MOBILE</th>
                                        <th>ACTION</th>
                                    </tr>
                                </thead>
                                <tbody class="recipients">
                                </tbody>
                            </table>
                            <!--<input type="hidden" name="phone_number" id="phone_number" value="<?php /*echo getCandidateMobileNoByCandidateId($mysqli,$_REQUEST['canId']);*/?>">-->
                            <button type="button" name="smsBtn" id="smsBtn" class="btn btn-info" style="display: none"><i class="fa fa-envelope"></i>&nbsp;SEND</button>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Inform about call recording</legend>
                            <p>
                                Ok, great! Just to let you know, this call is being recorded for quality and training purposes.
                                <br><br>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Is that okay?</span>
                            </p>
                            <p class="selection">
                                <input type="radio" name="q2" value="Yes" class="q2 form-control-sm"/> Yes
                                <br>
                                <input type="radio" name="q2" value="No" class="q2 form-control-sm"/> No
                                <br><br>
                                <textarea name="q2_comment" id="q2_comment" cols="30" rows="5" class="form-control" style="display: none" placeholder="Comments"></textarea>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Call Overview</legend>
                            <p>
                                <b><i class="fa fa-2x fa-info-circle"></i></b>&nbsp;
                                <span class="question">During this call, we'll discuss the role, your relevant experience, location preferences, availability, and pay expectations. I will be happy to answer any questions you may have about the position or the hiring process at the end of the call.</span>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Ask about current employment status</legend>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Can you tell me about your current employment status? And why you are looking for a new position.</span>
                                <br><br>
                            </p>
                            <p>
                                <textarea name="q3_comment" id="q3_comment" cols="30" rows="5" class="form-control" placeholder="Comments"></textarea>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Describe the casual role (Refer to the job description on right side)</legend>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Thanks for sharing with me,
So, the role I am calling about is ...
                                <br>
                                Does that sound like something you would be interested in?</span>
                                <br><br>
                            </p>
                            <p>
                                <textarea name="q4_comment" id="q4_comment" cols="30" rows="5" class="form-control" placeholder="Comments"></textarea>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Discuss relevant experience</legend>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Can you tell me about any relevant experience you have had in the past that would contribute to your success in this casual role?</span>
                                <br><br>
                            </p>
                            <p>
                                <input type="hidden" name="consultant" id="consultant" value="<?php echo getConsultantName($mysqli,$_REQUEST['consId']); ?>" class="form-control" readonly/>
                                <label for="reason_suitable">Reason for suitability</label>
                                <textarea name="reason_suitable" id="reason_suitable" class="form-control" readonly><?php echo getCandidateReasonForSuitability($mysqli,$_REQUEST['canId']); ?></textarea>
                            </p>
                            <p>
                                <br>
                                <textarea name="q5_comment" id="q5_comment" cols="30" rows="5" class="form-control" placeholder="Comments"></textarea>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Medical/Police Check</legend>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Do you have any prior or pending criminal convictions that may affect your application? </span>
                            </p>
                            <br>
                            <p class="selection">
                                <input type="radio" name="q6" value="Yes" class="q6 form-control-sm"/> Yes
                                <br>
                                <input type="radio" name="q6" value="No" class="q6 form-control-sm"/> No
                                <br>
                                <textarea name="q6_comment" id="q6_comment" cols="30" rows="5" class="form-control" style="display: none" placeholder="Comments"></textarea>
                            </p>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">As per our company policy you are required to have a police check done within the last 3 years. Therefore do you have a valid police check done within the last 3 years?  </span>
                            </p>
                            <br>
                            <p class="selection">
                                <input type="radio" name="q7" value="Yes" class="q7 form-control-sm"/> Yes
                                <br>
                                <input type="radio" name="q7" value="No" class="q7 form-control-sm"/> No
                            </p>
                            <br>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span id="q8" class="question">If not, are you happy to do one? It will only be taken off your first pay slip ($49), and we will provide you a copy. </span>
                            </p>
                                <br>
                            <div id="q8answer" style="display: none">
                                <p class="selection">
                                        <input type="radio" name="q8_answer" value="Yes" class="q8_answer form-control-sm"/> Yes
                                        <br>
                                        <input type="radio" name="q8_answer" value="No" class="q8_answer form-control-sm"/> No
                                </p>
                            </div>
                            <br>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Do you have any medical conditions that might affect your work or impair your ability to perform manual handling tasks, such as lifting?  </span>
                                <br>
                            </p>
                            <p>
                                <textarea name="q9_comment" id="q9_comment" cols="30" rows="5" class="form-control" placeholder="Comments"></textarea>
                            </p>
                            <br>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Inquire about location and travel preferences</legend>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Could you please tell me what is your current location (suburb)?</span>
                                <br>
                            </p>
                            <p>
                                <input name="q10_textbox" id="q10_textbox" class="form-control" placeholder="suburb"/>
                            </p>
                                <br>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">If you are successful in this role, will you be travelling by public transport or driving a car? </span>
                                <br>
                            </p>
                            <p>
                                <input name="q11_textbox" id="q11_textbox" class="form-control" placeholder="transport method"/>
                            </p>
                            <br>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">How long are you willing to travel for? Example: 30-45 minutes </span>
                                <br>
                            </p>
                            <p>
                                <input name="q12_textbox" id="q12_textbox" class="form-control" placeholder="transport time"/>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Inquire about availability</legend>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">What is your general availability like?</span>
                                <br>
                            </p>
                            <p>
                                <input name="q13_textbox" id="q13_textbox" class="form-control" placeholder="availability"/>
                            </p>
                            <br>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Are you able to work on short notice, and do you have any preferred days or hours? </span>
                                <br>
                            </p>
                            <p>
                                <input name="q14_textbox" id="q14_textbox" class="form-control" placeholder="prefered days"/>
                            </p>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Are you looking for morning, afternoon or night shifts? (Depending on role) </span>
                                <br>
                            </p>
                            <p>
                                <input name="q15_textbox" id="q15_textbox" class="form-control" placeholder="shift types"/>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Address any questions from the candidate</legend>
                            <p>
                                <b><i class="fa fa-2x">Q</i></b>&nbsp;
                                <span class="question">Now that we've discussed the main aspects of the role, do you have any questions about the casual position, the company, or the hiring process that I can help answer for you? </span>
                            </p>
                            <p>
                                <textarea name="q16_comment" id="q16_comment" cols="30" rows="5" class="form-control" placeholder="Questions from candidate"></textarea>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Next steps</legend>
                            <p>
                                <b><i class="fa fa-2x fa-info-circle"></i></b>&nbsp;
                                So, <?php echo $_REQUEST['first_name'].' '.$_REQUEST['last_name']; ?>, the next step is to review your application with our hiring team. I will get back to you within 24 hours. If you are successful, I will send you a registration link via email.
                            </p>
                            <p>
                                You can call me on the general line during office hours (8:30am – 5pm, Monday - Friday) if you need any assistance with filling it out.
                            </p>
                            <p>After we have your documents, we will be in touch to organise the next steps. </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend>Suitable/Unsuitable</legend>
                            <p>
                                <input type="radio" name="decision" class="decision form-control-sm" value="SUCCESSFUL"/> SUCCESSFUL
                                <input type="radio" name="decision" class="decision form-control-sm" value="UNSUCCESSFUL"/> UNSUCCESSFUL
                                <br><br>
                                <span class="question">Any Other Comments</span>
                                <br>
                                <textarea name="other_comments" id="other_comments" cols="30" rows="10" placeholder="Any other comments" class="form-control"></textarea>
                            </p>
                        </fieldset>
                        <fieldset class="rest">
                            <legend></legend>
                            <input type="submit" name="phoneScreenBtn" id="phoneScreenBtn" class="btn btn-lg btn-info" value="Submit">
                        </fieldset>
                    </section>
                    <section class="col-lg-6">
                        <a href="<?php echo '.'.getCandidateDocumentByDocTypeId($mysqli, $_REQUEST['canId'],24); ?>" target="_blank" style="cursor: pointer; text-decoration: none">CLICK HERE TO VIEW RESUME IF NOT LOADED BELOW</a>
                        <br>
                        <?php
                        if(strpos(getCandidateDocumentByDocTypeId($mysqli, $_REQUEST['canId'],24),'.pdf') !== false)
                        {
                        ?>
                            <embed src="<?php echo str_replace('./', './',getCandidateDocumentByDocTypeId($mysqli, $_REQUEST['canId'],24)); ?>" type="application/pdf" width="100%" height="850px">
                        <?php
                        }
                        else{
                        ?>
                        <iframe src='https://view.officeapps.live.com/op/embed.aspx?src=<?php echo str_replace('./',DOMAIN_URL,getCandidateDocumentByDocTypeId($mysqli, $_REQUEST['canId'],24)); ?>' width='100%' height='850px' frameborder='0'></iframe>
                        <?php } ?>
                        <br>
                        <br>
                        <br>
                        <br>
                        <h4>Job Description</h4>
                        <br>
                        <div class="jb_desc"></div>
                        <textarea name="jb_desc" id="jb_desc" class="jb_desc" cols="30" rows="10" style="display: none"></textarea>
                    </section>
                </div>
                </form>
                <div class="row">
                    <section class="col col-lg-12">
                        <br><br>
                    </section>
                </div>
            </div>
        </div>
        <br><br><br><br><br>
    </div>
    <!-- end content -->
</div>
<!-- PAGE FOOTER -->
<div class="page-footer">
    <div class="row">
        <div class="col-xs-12 col-sm-6">
            <span class="txt-color-white"><?php echo DOMAIN_NAME;  ?> <span class="hidden-xs"> - Employee Recruitment System</span> © <?php echo date('Y'); ?></span>
        </div>

        <div class="col-xs-6 col-sm-6 text-right hidden-xs">
            <div class="txt-color-white inline-block">

            </div>
        </div>
    </div>
</div>
<!-- END PAGE FOOTER -->

<?php include "./template/scr_scripts.php"; ?>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<!-- JQUERY MASKED INPUT -->
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<script type="text/javascript">
    $(function () {
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });
        getClients();
        function getClients(){
            let action = 'SINGLESELECT';
            $.ajax({
                url:"getClients.php",
                type:"POST",
                data:{action:action},
                dataType:"html",
                success: function(data){
                    $('#client_id').html('');
                    $('#client_id').html(data);
                }
            });
        }
        $(document).on('change', '#client_id', function(){
            getPositionByClient();
        });
        $(document).on('click', '#client_id', function(){
            getPositionByClient();
        });
        getPositionByClient();
        function getPositionByClient(){
            var action = 'CLIENTPOSITION';
            var clientId = $('#client_id :selected').val();
            $.ajax({
                url :"loadPositions.php",
                type:"POST",
                dataType:"html",
                data:{action:action,clientId:clientId},
                success: function(data) {
                }
            }).done(function(data){
                $('#position_id').html('');
                $('#position_id').html(data);
            });
        }
        $(document).on('click','.q1', function (){
            if($(this).val() == 'No'){
                $('#smsText').show();
                $('#smsBtn').show();
                $('.rest').hide();
            }else{
                $('#smsText').hide();
                $('#smsBtn').hide();
                $('.rest').show();
            }
        });
        $(document).on('click','.q2', function (){
            if($(this).val() == 'No'){
                $('#q2_comment').show();
                $('#q2_comment').show();
            }else{
                $('#q2_comment').hide();
                $('#q2_comment').hide();
            }
        });
        $(document).on('click','.q6', function (){
            if($(this).val() == 'No'){
                $('#q6_comment').hide();
            }else{
                $('#q6_comment').show();
            }
        });
        $(document).on('click','.q7', function (){
            if($(this).val() == 'No'){
                $('#q8answer').show();
            }else{
                $('#q8answer').hide();
            }
        });
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
        $(document).on('click','#position_id',function (){
            let client_id = $('#client_id :selected').val();
            let position_id = $('#position_id :selected').val();
            $.ajax({
                url:"getJobDescription.php",
                type:"POST",
                dataType:"html",
                data:{client_id:client_id,position_id:position_id},
                success: function(data){
                    $('.jb_desc').html('');
                    $('.jb_desc').html(data);
                }
            });
        });
        $(document).on('change','#position_id',function (){
            let client_id = $('#client_id :selected').val();
            let position_id = $('#position_id :selected').val();
            $.ajax({
                url:"getJobDescription.php",
                type:"POST",
                dataType:"html",
                data:{client_id:client_id,position_id:position_id},
                success: function(data){
                    $('.jb_desc').html('');
                    $('.jb_desc').html(data);
                }
            });
        });
        var canId = $('#candidate_id').val();
        loadRecipients(canId,0);
        function loadRecipients(cid,attempt){
            $.ajax({
                url: "smsList.php",
                type: "POST",
                dataType: "html",
                data: { cid : cid,attempt : attempt},
                success: function(data) {
                    $('.recipients').html('');
                    $('.recipients').html(data);
                }
            });
        }
        $(document).on('click','#smsBtn',function (e) {
            e.preventDefault();
            let act = 'SMS';
            let alertMe = 'Yes';
            let smsAccount = 3;
            let smsText = $('textarea#smsText').val();
            $.ajax({
                url: "sendSMS.php",
                type: "POST",
                dataType: "text",
                data: {act: act, alertMe: alertMe, smsAccount: smsAccount, smsText: smsText},
                success: function (data) {
                    if (data == 'MSGSENT') {
                        $('#msg').html('SMS sent');
                    } else if (data == 'NORECIPIENTS') {
                        $('#msg').html('no recipients added');
                    } else {
                    }
                }
            });
        });
        $(document).on('click', '#phoneScreenBtn', function (e) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmPhr = $("#frmPhr").validate({
                errorClass: errorClass,
                errorElement: errorElement,
                highlight: function (element) {
                    $(element).parent().removeClass('state-success').addClass("state-error");
                    $(element).removeClass('valid');
                },
                unhighlight: function (element) {
                    $(element).parent().removeClass("state-error").addClass('state-success');
                    $(element).addClass('valid');
                },
                rules: {
                    q1: {
                      required: true
                    },
                    q2: {
                        required: true
                    },
                    q2_comment: {
                        required: function (element) {
                            return $("input:radio[name='q2']:checked").val() == 'No';
                        }
                    },
                    q3_comment: {
                        required: true,
                    },
                    q4_comment: {
                        required: true
                    },
                    q5_comment: {
                        required: true
                    },
                    q6: {
                        required: true
                    },
                    q6_comment: {
                        required: function (element) {
                            return $("input:radio[name='q6']:checked").val() == 'Yes';
                        }
                    },
                    q7: {
                        required: true
                    },
                    q8_answer: {
                        required: function (element) {
                            return $("input:radio[name='q7']:checked").val() == 'No';
                        }
                    },
                    q9_comment:{
                        required: true
                    },
                    q10_comment:{
                        required: true
                    },
                    q11_comment:{
                        required: true
                    },
                    q12_comment:{
                        required: true
                    },
                    q13_comment:{
                        required: true
                    },
                    q14_comment:{
                        required: true
                    },
                    q15_comment:{
                        required: true
                    },
                    q16_comment:{
                        required: true
                    },
                    decision:{
                        required: true
                    }
                },
                messages: {

                },
                submitHandler: function (form) {
                    let candidate_id = $('#candidate_id').val();
                    let cons_id = $('#cons_id').val();
                    let client_id = $('#client_id :selected').val();
                    let position_id = $('#position_id :selected').val();
                    let jb_desc = $('textarea#jb_desc').val();
                    let q1 = $('input[name=q1]:checked', '#frmPhr').val();
                    let q2 = $('input[name=q2]:checked', '#frmPhr').val();
                    let q2_comment = $('textarea#q2_comment').val();
                    let q3_comment = $('textarea#q3_comment').val();
                    let q4_comment = $('textarea#q4_comment').val();
                    let q5_comment = $('textarea#q5_comment').val();
                    let q6 = $('input[name=q6]:checked', '#frmPhr').val();
                    let q6_comment = $('textarea#q6_comment').val();
                    let q7 = $('input[name=q7]:checked', '#frmPhr').val();
                    let q8_answer = $('input[name=q8_answer]:checked', '#frmPhr').val();
                    let q9_comment = $('textarea#q9_comment').val();
                    let q10_textbox = $('#q10_textbox').val();
                    let q11_textbox = $('#q11_textbox').val();
                    let q12_textbox = $('#q12_textbox').val();
                    let q13_textbox = $('#q13_textbox').val();
                    let q14_textbox = $('#q14_textbox').val();
                    let q15_textbox = $('#q15_textbox').val();
                    let q16_comment = $('#q16_comment').val();
                    let decision = $('input[name=decision]:checked', '#frmPhr').val();
                    let other_comments = $('textarea#other_comments').val();
                    $.ajax({
                        type: "POST",
                        url: "./gen_phone_scr_doc.php",
                        data: {
                            candidate_id:candidate_id,
                            cons_id:cons_id,
                            client_id:client_id,
                            position_id:position_id,
                            q1:q1,
                            q2:q2,
                            q2_comment:q2_comment,
                            q3_comment:q3_comment,
                            q4_comment:q4_comment,
                            q5_comment:q5_comment,
                            q6:q6,
                            q6_comment:q6_comment,
                            q7:q7,
                            q8_answer:q8_answer,
                            q9_comment:q9_comment,
                            q10_textbox:q10_textbox,
                            q11_textbox:q11_textbox,
                            q12_textbox:q12_textbox,
                            q13_textbox:q13_textbox,
                            q14_textbox:q14_textbox,
                            q15_textbox:q15_textbox,
                            q16_comment:q16_comment,
                            decision:decision,
                            jb_desc:jb_desc,
                            other_comments:other_comments
                        },
                        dataType: "text",
                        success: function (data) {
                           if(data !== 'SUCCESS'){
                               $('.msg').html('');
                               $('.msg').html(data);
                               $('html, body').animate({scrollTop: '0px'}, 300);
                              /* $('.msg').html('');
                               $('.msg').html('Phone Screen Submission successful');
                               $('html, body').animate({scrollTop: '0px'}, 300);
                               $('#phoneScreenBtn').hide();*/
                           }else{
                               /*$('.msg').html('');
                               $('.msg').html(data);
                               $('html, body').animate({scrollTop: '0px'}, 300);*/
                               history.back();
                           }
                        },
                        error: function (jqXHR, exception) {
                            if (jqXHR.status === 0) {
                                console.log('Not connect.\n Verify Network.');
                            } else if (jqXHR.status == 404) {
                                console.log('Requested page not found. [404]');
                            } else if (jqXHR.status == 500) {
                                console.log('Internal Server Error [500].');
                            } else if (exception === 'parsererror') {
                                console.log('Requested JSON parse failed.');
                            } else if (exception === 'timeout') {
                                console.log('Time out error.');
                            } else if (exception === 'abort') {
                                console.log('Ajax request aborted.');
                            } else {
                                console.log('Uncaught Error.\n' + jqXHR.responseText);
                            }
                        }
                    });
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.parent());
                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>