<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
</head>
<body>
<div class="container">
    <br><br>
    <div class="sign-panel">
        <br><br>
        <div style="margin: 0 auto; text-align: center">
            <img src="img/wiltyavari_logo_small.png" width="120" height="93"/>
            <br>
            <i>Powered by</i>
            <br>
            <img src="img/logo.png" width="220" height="50"/>
        </div>
        <span style="text-align: center"><div class="h3box"><h3>JOB BOARD APPLICATION FORM</h3></div></span>
        <div id="msg" class="msg error"></div>
        <form id="frmJbAppForm" name="frmJbAppForm" method="post" enctype="multipart/form-data">
            <div class="row">
                <section class="col col-sm-12">
                    <table class="table table-responsive" style="width: 100%">
                        <tbody>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span style="color:red">*</span> First Name:
                                        </div>
                                        <input type="text" class="form-control" id="first_name"
                                               name="first_name" placeholder="First Name" required/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span style="color:red">*</span> Last Name:</div>
                                        <input type="text" class="form-control" id="last_name"
                                               name="last_name" placeholder="Last Name" required/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span style="color:red">*</span> Gender</div>
                                        <select name="gender" id="gender" class="form-control">
                                            <option value="Select One" selected disabled>Select One</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Prefer not to answer">Prefer not to answer</option>
                                        </select>
                                    </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span style="color:red">*</span> Email:</div>
                                        <input type="email" class="form-control" id="candidate_email"
                                               name="candidate_email" placeholder="Email" required/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span style="color:red">*</span> Mobile No:</div>
                                        <input type="text" class="form-control" id="candidate_phone"
                                               name="candidate_phone" placeholder="Phone" required/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span style="color:red">*</span> Suburb:</div>
                                        <input type="text" class="form-control" id="suburb" name="suburb"
                                               placeholder="Suburb" required/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">Relevant Experience & Notes:
                                            <textarea class="form-control" id="experience" name="experience"
                                                      placeholder="Experience & notes"></textarea></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="drivers_licence"
                                                   name="drivers_licence"
                                                   value="TRUE">
                                            <label class="form-check-label" for="inlineCheckbox1">Drivers
                                                licence</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="own_car" name="own_car"
                                                   value="TRUE">
                                            <label class="form-check-label" for="own_car">Own Car</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="work_with_children"
                                                   name="work_with_children"
                                                   value="TRUE">
                                            <label class="form-check-label" for="work_with_children">Working with
                                                children's check</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="police_check"
                                                   name="police_check"
                                                   value="TRUE">
                                            <label class="form-check-label" for="police_check">Police Check</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="forklift_licence"
                                                   name="forklift_licence"
                                                   value="TRUE">
                                            <label class="form-check-label" for="forklift_licence">Forklift
                                                licence</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="white_card_holder"
                                                   name="white_card_holder"
                                                   value="TRUE">
                                            <label class="form-check-label" for="white_card_holder">White Card
                                                holder</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="mr_licence"
                                                   name="mr_licence"
                                                   value="TRUE">
                                            <label class="form-check-label" for="mr_licence">MR licence</label>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"> Australian Work Rights</div>
                                        <select name="work_rights" id="work_rights" class="form-control">
                                            <option value="Select One" selected disabled>Select One</option>
                                            <option value="Australian Citizen">Australian Citizen</option>
                                            <option value="Permanent Resident and/or New Zealand Citizen">Permanent
                                                Resident and/or New Zealand Citizen
                                            </option>
                                            <option value="Temporary Visa">Temporary Visa</option>
                                            <option value="Student Visa">Student Visa</option>
                                        </select>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon"><span style="color:red">*</span>Please upload
                                            your CV/Resume
                                        </div>
                                        <input type="file" name="resume" id="resume" class="form-control" required/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">Upload Photograph</div>
                                        <input type="file" name="photo" id="photo" class="form-control"/>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </section>
                <section class="col col-sm-6"></section>
            </div>
            <div class="row">
                <section class="col col-sm-8">
                    <br>
                    <input type="hidden" name="position" id="position"
                           value="<?php echo base64_encode($_REQUEST['position']); ?>">
                    <button id="applyBtn" class="applyBtn btn-info btn-lg">&nbsp;&nbsp;Submit&nbsp;&nbsp;</button>
                    <br>
                    <br>
                </section>
            </div>
        </form>

    </div>
    <br><br>
</div>
<br><br><br>
<script src="js/jquery/2.1.1/jquery.min.js"></script>
<!-- Basic Styles -->
<link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
<!-- BOOTSTRAP JS -->
<script src="js/bootstrap/bootstrap.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<!-- JQUERY VALIDATE -->
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<script type="text/javascript" src="js/jquery.base64.js"></script>
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<style>
    .form-control {
        border-radius: 0.5rem;
    }

    .input-group-addon {
        border-radius: 0.5rem;
    }

    .error {
        color: red;
    }

    .invalid {
        color: red;
    }

    label {
        font-weight: normal;
    }

    .sign-panel {
        margin: 0 auto;
        padding: 10px 100px 10px 100px;
        border-radius: 10px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        background: #FFFFFF;
        width: 90%;
    }

    body {
        background-image: url("img/subtle-stripes-pattern-2273.png");
        background-repeat: repeat;
    }

    .table th, .table td {
        border-top: none !important;
    }

    .h3box {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }

    * {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    *:before, *:after {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
    }

    .clearfix {
        clear: both;
    }

    .text-center {
        text-align: center;
    }

    a {
        color: tomato;
        text-decoration: none;
    }

    a:hover {
        color: #2196f3;
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
        padding: 20px 0;
        position: relative;
        margin-bottom: 10px;
    }

    .header:after {
        content: "";
        display: block;
        height: 1px;
        background: #eee;
        position: absolute;
        left: 30%;
        right: 30%;
    }

    .header h2 {
        font-size: 3em;
        font-weight: 300;
        margin-bottom: 0.2em;
    }

    .header p {
        font-size: 14px;
    }

    #a-footer {
        margin: 20px 0;
    }

    .new-react-version {
        padding: 20px 20px;
        border: 1px solid #eee;
        border-radius: 20px;
        box-shadow: 0 2px 12px 0 rgba(0, 0, 0, 0.1);

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

    .success-box1 {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }

    .success-box1 img {
        margin-right: 10px;
        display: inline-block;
        vertical-align: top;
    }

    .success-box1 > div {
        vertical-align: top;
        display: inline-block;
        color: #888;
    }

    .success-box2 {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }

    .success-box2 img {
        margin-right: 10px;
        display: inline-block;
        vertical-align: top;
    }

    .success-box2 > div {
        vertical-align: top;
        display: inline-block;
        color: #888;
    }

    .success-box3 {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }

    .success-box3 img {
        margin-right: 10px;
        display: inline-block;
        vertical-align: top;
    }

    .success-box3 > div {
        vertical-align: top;
        display: inline-block;
        color: #888;
    }

    .success-box4 {
        margin: 50px 0;
        padding: 10px 10px;
        border: 1px solid #eee;
        background: #f9f9f9;
    }

    .success-box4 img {
        margin-right: 10px;
        display: inline-block;
        vertical-align: top;
    }

    .success-box4 > div {
        vertical-align: top;
        display: inline-block;
        color: #888;
    }


</style>
<script>
    $(document).ready(function () {

        /* AJAX loading animation */
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });
        /* -  end  -*/
        $.ajaxSetup({
            headers: {
                'CsrfToken': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).on('click', '#applyBtn', function (e) {
            var errorClass = 'invalid';
            var errorElement = 'div';
            var frmJbAppForm = $('#frmJbAppForm ').validate({
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
                    first_name: {
                        required: true
                    },
                    last_name: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    candidate_email: {
                        required: true
                    },
                    candidate_phone: {
                        required: true
                    },
                    suburb: {
                        required: true
                    }
                },
                messages: {
                    first_name:
                        {
                            required: "Please enter your first name "
                        },
                    last_name:
                        {
                            required: "Please enter your last name "
                        },
                    gender:
                        {
                            required: "Please select your gender"
                        },
                    candidate_email: {
                        required: "Please enter your email"
                    },
                    candidate_phone: {
                        required: "Please enter your phone"
                    },
                    suburb: {
                        required: "Please enter your suburb"
                    }
                },
                submitHandler: function (form) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    var formData = new FormData(form);
                    formData.append('first_name', $.base64.encode($('#first_name').val()));
                    formData.append('last_name', $.base64.encode($('#last_name').val()));
                    formData.append('gender', $.base64.encode($('#gender :selected').val()));
                    formData.append('candidate_email', $.base64.encode($('#candidate_email').val()));
                    formData.append('candidate_phone', $.base64.encode($('#candidate_phone').val()));
                    formData.append('suburb', $.base64.encode($('#suburb').val()));
                    formData.append('experience', $.base64.encode($('textarea#experience').val()));
                    formData.append('drivers_licence', $.base64.encode($('input[name=drivers_licence]:checked', '#frmJbAppForm').val()));
                    formData.append('own_car', $.base64.encode($('input[name=own_car]:checked', '#frmJbAppForm').val()));
                    formData.append('work_with_children', $.base64.encode($('input[name=work_with_children]:checked', '#frmJbAppForm').val()));
                    formData.append('police_check', $.base64.encode($('input[name=police_check]:checked', '#frmJbAppForm').val()));
                    formData.append('forklift_licence', $.base64.encode($('input[name=forklift_licence]:checked', '#frmJbAppForm').val()));
                    formData.append('white_card_holder', $.base64.encode($('input[name=white_card_holder]:checked', '#frmJbAppForm').val()));
                    formData.append('mr_licence', $.base64.encode($('input[name=mr_licence]:checked', '#frmJbAppForm').val()));
                    formData.append('work_rights', $.base64.encode($('#work_rights :selected').val()));
                    formData.append('resume', $('#resume')[0].files[0]);
                    formData.append('photo', $('#photo')[0].files[0]);
                    formData.append('position', $('#position').val());
                    $.ajax({
                        url: "./processJobBoard.php",
                        type: 'POST',
                        dataType: 'text',
                        data: formData,
                        mimeType: "multipart/form-data",
                        contentType: false,
                        processData: false,
                        success: function (data) {
                            console.log('JBAPPL '+data);
                            if (data == 'SUCCESS') {
                                $('.msg').html('');
                                $('.msg').html('Submission Successful');
                                $('html, body').animate({scrollTop: '0px'}, 300);
                            } else {
                                $('.msg').html('');
                                $('.msg').html(data);
                                //'Error Submission Unsuccessful'
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
<div class="modal"></div>
</body>
</html>