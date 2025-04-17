<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(empty($_REQUEST['username'])){
    $error = 'Casual ID not set. Please contact chandler consultant';
    header("Location:error.php?error=$error");
}
$docTypes = getDocumentTypes($mysqli);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <title>Vaccination Certification Upload Form</title>
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
    <!-- JQUERY FORM PLUGIN -->
    <script src="js/jqueryform/jquery.form.js"></script>
    <style>
        .error{
            color: red;
        }
        label{
            font-weight: normal;
        }
        #signature {
            border: 2px dotted black;
            background-color:lightgrey;
            color: #03038c;
        }
        body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
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
            url('../img/page-loading.gif')
            50% 50%
            no-repeat;
        }
        .loadDisplay {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
            url('../img/page-loading.gif')
            50% 50%
            no-repeat;
        }
        /*.processing {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
                        url('../img/page-loading.gif')
                        50% 50%
                        no-repeat;
        }*/
        /*ajax-loader.gif*/
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
/*input[type='file'] {
  color: transparent;
}*/
    </style>
</head>
<body>
<div id="header" style="padding: 20px 20px 20px 20px" align="center">
    <img src="img/LogoChandlerServices.png" width="150" height="60">
    <h1>Candidate Vaccination Result Information</h1>
    <div class="error">Please do not copy paste anything to the form inputs when you fill the form</div>
</div>
<div class="container">
        <div class="row">
            <div class="error"><?php echo $_REQUEST['msg']; ?></div>
            <form name="frmVaccForm" id="frmVaccForm" class="frmVaccForm" action="docUpload.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Attach Vaccination Information</legend>
                        <section class="col-md-4">
                            <input type="hidden" name="candid" id="candid" value="<?php echo base64_decode($_REQUEST['username']);?>"/>
                            <input type="hidden" name="action" id="action" value="VACCINE"/>
                            <select name="docTypeId" id="docTypeId" class="form-control">
                                <?php
                                $options = '';
                                foreach($docTypes as $doc){
                                    if(!validateVaccinationDocument($mysqli,base64_decode($_REQUEST['username']),$doc['typeId'])) {
                                        if ($doc['typeId'] == 57) {
                                            $options = $options . '<option value="' . $doc['typeId'] . '">' . $doc['typeLabel'] . '</option>';
                                        } elseif ($doc['typeId'] == 58) {
                                            $options = $options . '<option value="' . $doc['typeId'] . '">' . $doc['typeLabel'] . '</option>';
                                        } elseif ($doc['typeId'] == 59) {
                                            $options = $options . '<option value="' . $doc['typeId'] . '">' . $doc['typeLabel'] . '</option>';
                                        }
                                    }
                                }
                                echo $options;
                                ?>
                            </select>
                        </section>
                        <section class="col-md-4">
                            <input type="file" name="file" id="file" class="form-control" title=""/>
                        </section>
                        <section class="col-md-4">
                            <button type="submit" class="btnUpload btn btn-success">Upload</button>
                        </section>
                </fieldset>
            </form>
        </div>
</div>
<div class="modal"><!-- Place at bottom of page --></div>
<script type="text/javascript">
    $(function(){

    });
</script>
</body>
</html>