<?php

if(empty($_REQUEST['client_id'])){
    $msg = "Access Denied";
    header("Location:error.php?error=$msg");
}
if(empty($_REQUEST['log_id'])){
    $msg = "Access Denied";
    header("Location:error.php?error=$msg");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CHANDLER PERSONNEL CLIENT SURVEY</title>
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <style>
        body{
            font-family: "Helvetica Neue", Roboto, "Segoe UI", Calibri, sans-serif;
            background: lightgrey
        }
        /* ------------- ajax loading styles ---------- */
        /* Start by setting display:none to make this hidden.
           Then we position it in relation to the viewport window
           with position:fixed. Width, height, top and left speak
           for themselves. Background we set to 80% white with
           our animation centered, and no-repeating */
        .modal {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
            url('/img/page-loading.gif')
            50% 50%
            no-repeat;
        }
        /* When the body has the loading class, turn
           the scrollbar off with overflow:hidden */
        body.loading {
            overflow: hidden;
        }
        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.loading .modal {
            display: block;
        }
        body.ajaxLoader {
            overflow: hidden;
        }
        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.ajaxLoader .loadDisplay {
            display: block;
        }
        /* ------------  end ajax styles -------------*/
        .state-error{
            color:red;
        }
        .invalid{
            color:red;
        }
        .error{
            color:red;
        }
        label{
            font-weight: normal;
        }
        .outer-panel{
            margin: 0 auto;
            padding: 20px 50px 20px 50px;
            border-radius: 10px;
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            background: #FFFFFF;
            width: 80%;
        }

        .btn {
            display: inline-block;
            margin-bottom: 0;
            font-weight: normal;
            text-align: center;
            vertical-align: middle;
            touch-action: manipulation;
            cursor: pointer;
            background-image: none;
            border: 1px solid transparent;
            white-space: nowrap;
            padding: 6px 12px;
            font-size: 13px;
            line-height: 1.42857143;
            border-radius: 2px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        .btn-info{
            background-color: #1b2839;
            border: 1px solid #1b2839;
            color: white;
        }
        .btn-info:hover{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info:active:focus{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info:active{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info.reverse{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info.reverse:active:focus{
            background-color: #45c3c2;
            border: 1px solid #45c3c2;
            color: white;
        }
        .btn-info.reverse:hover{
            background-color: #1b2839;
            border: 1px solid #1b2839;
            color: white;
        }
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
        .header h2 {
            font-size:3em;
            font-weight:300;
            margin-bottom:0.2em;
        }
        .header p {
            font-size:14px;
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
<br>
<br>
<div class="outer-panel">
        <div style="text-align: center">
            <div class="h3box"><img src="../img/logo.png" width="220" alt=""></div>
                <h3> CLIENT SURVEY</h3>
        </div>
        <div align="center" style="width: 980px; height: 100%; margin: 0 auto">
            <div id="msg" class="msg error"></div>
            <form name="frmcl" id="frmcl" method="post" class="smart-form">
            <table class="table">
                <tbody>
                <tr>
                    <td>
                        <ol>
                            <li>Recruitment Services:</li>
                            <p>Are you satisfied with the quality and fit of candidates we have sourced for your open positions?
                                <br>
                                <input type="radio" name="q1" value="Yes"> Yes <input type="radio" name="q1" value="No"> No
                            </p>
                            <p>
                                Were there any specific areas where you feel we could improve?
                                <br>
                                <textarea name="q1_exp" id="q1_exp" cols="30" rows="10" class="form-control"></textarea>
                            </p>
                            <li>After-Hours Service:</li>
                            <p>
                                Did our after-hours service meet your expectations related to responsiveness and effectiveness in handling any issues or inquiries?
                                <br>
                                <input type="radio" name="q2" value="Yes"> Yes <input type="radio" name="q2" value="No"> No
                            </p>
                            <p>
                                Were there any specific areas where you feel we could improve?
                                <br>
                                <textarea name="q2_exp" id="q2_exp" cols="30" rows="10" class="form-control"></textarea>
                            </p>
                            <li>Payroll Services:</li>
                            <p>Are you satisfied with the accuracy and timeliness of the payroll processing/Invoicing we provide?
                                <br>
                                <input type="radio" name="q3" value="Yes"> Yes <input type="radio" name="q3" value="No"> No <input type="radio" name="q3" value="Not Applicable"> Not Applicable
                            </p>
                            <p>
                                Have there been any issues or areas for improvement that you have noticed?
                                <br>
                                <textarea name="q3_exp" id="q3_exp" cols="30" rows="10" class="form-control"></textarea>
                            </p>
                            <li>Suggestions for Improvement:</li>
                            <p>Are there any additional services or changes you would suggest to improve our recruitment, after-hours support, or payroll services? We are always looking for ways to better serve you.
                                <br>
                                <input type="radio" name="q4" value="Yes"> Yes <input type="radio" name="q4" value="No"> No
                                <br>
                                <textarea name="q4_exp" id="q4_exp" cols="30" rows="10" class="form-control"></textarea>
                            </p>
                        </ol>
                        <ol>
                            <input type="hidden" name="client_id" id="client_id" value="<?php echo $_REQUEST['client_id']; ?>"/>
                            <input type="hidden" name="client_name" id="client_name" value="<?php echo $_REQUEST['client_name']; ?>"/>
                            <input type="hidden" name="client_position" id="client_position" value="<?php echo $_REQUEST['client_position']; ?>"/>
                            <input type="hidden" name="client_email" id="client_email" value="<?php echo $_REQUEST['client_email']; ?>"/>
                            <input type="hidden" name="log_id" id="log_id" value="<?php echo $_REQUEST['log_id']; ?>"/>
                            <button type="submit" name="surveySubmitBtn" id="surveySubmitBtn" class="btn btn-lg btn-info">Submit</button>
                        </ol>
                    </td>
                </tr>
                </tbody>
            </table>
            </form>
        </div>
</div>
<script src="js/jquery/2.1.1/jquery.min.js"></script>

<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });

        $(document).on('click', '#surveySubmitBtn', function (e) {
            var errorClass = 'invalid';
            var errorElement = 'em';
            var frmcl = $('#frmcl').validate({
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
                    q1_exp: {
                        required: function (element) {
                            return $("input:radio[name='q1']:checked").val() == 'Yes';
                        }
                    },
                    q2: {
                        required: true
                    },
                    q2_exp: {
                        required: function (element) {
                            return $("input:radio[name='q2']:checked").val() == 'Yes';
                        }
                    },
                    q3: {
                        required: true,
                    },
                    q4: {
                        required: true
                    }
                },
                messages: {

                },
                submitHandler: function (form) {
                    var client_id = $('#client_id').val();
                    var log_id = $('#log_id').val();
                    var client_name = $('#client_name').val();
                    var client_position = $('#client_position').val();
                    var client_email = $('#client_email').val();
                    var q1 = $('input[name=q1]:checked', '#frmcl').val();
                    var q1_exp = $('textarea#q1_exp').val();
                    var q2 = $('input[name=q2]:checked', '#frmcl').val();
                    var q2_exp = $('textarea#q2_exp').val();
                    var q3 = $('input[name=q3]:checked', '#frmcl').val();
                    var q3_exp = $('textarea#q3_exp').val();
                    var q4 = $('input[name=q4]:checked', '#frmcl').val();
                    var q4_exp = $('textarea#q4_exp').val();
                    $.ajax({
                        type:"POST",
                        url:"./process_client_survey.php",
                        data: {
                            client_id:client_id,
                            log_id:log_id,
                            client_name:client_name,
                            client_position:client_position,
                            client_email:client_email,
                            q1:q1,
                            q1_exp:q1_exp,
                            q2:q2,
                            q2_exp:q2_exp,
                            q3:q3,
                            q3_exp:q3_exp,
                            q4:q4,
                            q4_exp:q4_exp
                        },
                        dataType: "text",
                        success: function (data) {
                            if(data === 'SUCCESS'){
                                $('#msg').html('');
                                $('#msg').html('Survey submitted successfully');
                            }else{
                                $('#msg').html('');
                                $('#msg').html('Error submitting Survey');
                            }
                            $('html, body').animate({scrollTop: '0px'}, 300);
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
