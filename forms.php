<?php
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <title>Candidate Information Collection Form</title>
    <script src="js/jquery/2.1.1/jquery.min.js"></script>
    <!-- this, preferably, goes inside head element: -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/jSignature/flashcanvas.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" media="screen" href="css/jquery-ui.css">
    <!-- JQUERY UI AUTO COMPLETE STYLES -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/jquery.ui.autocomplete.css">
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <!-- Jquery UI date range picker -->
    <link rel="stylesheet" type="text/css" media="all" href="css/daterangepicker.css" />
    <!-- Jquery UI date time picker -->
    <link rel="stylesheet" type="text/css" href="css/jquery-ui-timepicker-addon.css">
    <link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">
    <!-- FAVICONS -->
    <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <!-- BOOTSTRAP JS -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
    <!--<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>-->
    <script src="js/libs/jquery-ui-1.10.3.min.js"></script>
    <!-- you load jquery somewhere before jSignature...-->
    <script src="js/jSignature/jSignature.min.js"></script>
    <!-- Jquery Form Validator -->
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/jquery.base64.js"></script>
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

    <style>
        .error{
            color: red;
            font-weight: bold;
        }
        label{
            font-weight: normal;
        }
        #signature {
            border: 2px dotted black;
            background-color:lightgrey;
            color: #03038c;
        }
        #superSignature {
            border: 2px dotted black;
            background-color:lightgrey;
            color: #03038c;
        }
        body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }
        .vertical {
            border-left: 2px ridge  grey;
            height: 100%;
            position:absolute;
            left: 50%;
        }
    </style>
</head>
<body>
<div id="header" style="padding: 20px 20px 20px 20px">
    <img src="img/LogoChandlerServices.png" width="150" height="60">
</div>
<div align="center" class="error"></div>
<div class="container">
  <div class="row">
    <section class="col col-lg-6">
    <div align="center"><h1>Tax Declaration Form Submission</h1></div>
        <div class="row">
            <h4 style="color: red">Important Instructions for uploading</h4>
            <section class="col col-sm-12">
                <ul>
                    <li>Click the below link to download tax declaration form to your computer.</li>
                    <li>Open the pdf form from Adobe acrobat reader in your computer.</li>
                    <li>Fill the form</li>
                    <li>Select the saved form and click attach file</li>
                    <li style="color: red;font-weight: bold">DO NOT sign and enter the date on pdf file</li>
                    <li>Enter your Full name and mobile number below and Sign digitally below then Press "Send Tax Form to Chandler"</li>
                </ul>
                <b><a href="docform/CPS_TFN_declaration_form_N3092.pdf" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i>&nbsp;Download ATO Tax Declaration form</a></b>
            </section>
        </div>
        <br>
        <form name="frmTaxForm" id="frmTaxForm" class="frmTaxForm" method="post" action="taxUpload.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Filled/Saved Tax Form</legend>
            <div class="row">
                <section class="col col-sm-12">
                    <label>Select filled & saved tax declaration form from your computer here</label>
                    <input type="file" name="file" id="file" onchange="this.parentNode.nextSibling.value = this.value"/>
                    <button class="btn btn-info btn-sm" type="submit" value="Upload"><i class="glyphicon glyphicon-upload"></i> Attach File</button>
                    <div id="taxprogress">
                        <div id="taxbar"></div>
                        <div id="taxpercent">0%</div>
                    </div>
                    <div id="taxmessage"></div>
                </section>
            </div>
            </fieldset>
        </form>
        <form name="frmTax" id="frmTax" class="frmTax" method="post" action="taxDeclaration.php">
            <fieldset>
                <legend>Send Tax Form</legend>
                <div class="row">
                    <section class="col col-sm 3">
                    </section>
                    <section class="col col sm-3">
                        &nbsp;&nbsp;&nbsp;
                        <label for="fullName">Name:</label>
                        <input type="text" name="fullName" id="fullName" placeholder="Enter your Full name">
                        <label for="mobileNo">Mobile No:</label>
                        <input type="text" name="mobileNo" id="mobileNo" maxlength="10" placeholder="enter your phone number">
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-12">
                        <input type="hidden" name="fileSubmitted" id="fileSubmitted" value="">
                        <label><b>Candidate Signature</b></label>&nbsp;&nbsp;&nbsp;<span><b><?php echo date('d/m/Y'); ?></b></span>
                        <div id="signature"></div>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-12">
                        <br>
                        <input type="submit" id="taxSubmitBtn" class="taxSubmitBtn btn-success btn-sm" value="Send Tax Form to Chandler"/>
                    </section>
                </div>
            </fieldset>
        </form>
    </section>
    <div class="vertical"></div>
    <section class="col col-lg-6">
        <div align="center"><h1>Superannuation Declaration Form Submission</h1></div>
        <div class="row">
            <h4 style="color: red; padding-left: 20px;">Important Instructions for uploading</h4>
            <section class="col col-sm-12">
                <ul>
                    <li>Click the below link to download superannuation declaration form to your computer.</li>
                    <li>Open the pdf form from Adobe acrobat reader in your computer.</li>
                    <li>Fill the form</li>
                    <li>Select the saved form and click attach file</li>
                    <li style="color: red;font-weight: bold">DO NOT sign and enter the date on pdf file</li>
                    <li>Enter your Full name and mobile number below and Sign digitally below then Press "Send Superannuation Form to Chandler"</li>
                </ul>
                <b><a href="docform/SUPER17983Superannuation_standard_choice_form.pdf" class="btn btn-primary" target="_blank"><i class="fa fa-download"></i>&nbsp;Download Superannuation Declaration form</a></b>
            </section>
        </div>
        <br>
        <form name="frmSuperForm" id="frmSuperForm" class="frmSuperForm" method="post" action="superUpload.php" enctype="multipart/form-data">
            <fieldset>
                <legend>Filled/Saved Superannuation Form</legend>
                <div class="row">
                    <section class="col col-sm-12">
                        <label>Select filled & saved superannuation declaration form from your computer here</label>
                        <input type="file" name="superFile" id="superFile" onchange="this.parentNode.nextSibling.value = this.value"/>
                        <button class="btn btn-info btn-sm" type="submit" value="Upload"><i class="glyphicon glyphicon-upload"></i> Attach File</button>
                        <div id="superprogress">
                            <div id="superbar"></div>
                            <div id="superpercent">0%</div>
                        </div>
                        <div id="supermessage"></div>
                    </section>
                </div>
            </fieldset>
        </form>
        <form name="frmSuper" id="frmSuper" class="frmSuper" method="post" action="superDeclaration.php">
            <fieldset>
                <legend>Send Superannuation Form</legend>
                <div class="row">
                    <section class="col col-sm 3">

                    </section>
                    <section class="col col sm-3">
                        &nbsp;&nbsp;&nbsp;
                        <label for="superFullName">Name:</label>
                        <input type="text" name="superFullName" id="superFullName" placeholder="Enter your Full name">
                        <label for="superMobileNo">Mobile No:</label>
                        <input type="text" name="superMobileNo" id="superMobileNo" maxlength="10" placeholder="enter your phone number">
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-12">
                        <input type="hidden" name="superFileSubmitted" id="superFileSubmitted" value="">
                        <label><b>Candidate Signature</b></label>&nbsp;&nbsp;&nbsp;<span><b><?php echo date('d/m/Y'); ?></b></span>
                        <div id="superSignature"></div>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-sm-12">
                        <br>
                        <input type="submit" id="superSubmitBtn" class="superSubmitBtn btn-success btn-sm" value="Send Superannuation Form to Chandler"/>
                    </section>
                </div>
            </fieldset>
        </form>
    </section>
</div>
</div>
<br>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<div id="imgSuperSig" style="display: none;"></div>
<img id="dataSuperImg" src="" style="border: 1px solid green;">
<script type="text/javascript" src="js/formScript.js"></script>
<div class="modal"><!-- Place at bottom of page --></div>

</body>
</html>