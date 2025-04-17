<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <style>
        .error{
            color: red;
        }
        .invalid{
            color: red;
        }
        .table th, .table td {
            border-top: none !important;
        }
    </style>
</head>
<body>
<div class="container">
    <br><br>
    <div>
        <br><br>
        <div>
            <img src="img/chandler_personnel.jpg" width="300" height="30">
        </div>
        <br>
        <div class="card">
        <div class="card-header">Mobile Device/App Information</div>
        <div id="msg" class="msg error"><?php if(!empty($_REQUEST['msg'])){ echo $_REQUEST['msg']; } ?></div>
        <form id="frmAppVersionForm" name="frmAppVersionForm" method="post" class="smart-form" action="appVersionProcess.php">
            <div class="row">
                <section class="col col sm-6">
                    <br><br>
                    <form action="">
                        <fieldset>
                            <legend>Please select your mobile device OS type and version and submit</legend>
                        <table class="table table-responsive" style="width: 50%">
                            <tbody>
                            <tr>
                                <td>
                                    <label>Mobile OS</label>
                                    <input type="hidden" name="can_id" id="can_id" value="<?php echo $_REQUEST['id']; ?>">
                                    <select name="mobile_os" id="mobile_os" class="form-control">
                                        <option value="iOS">iOS</option>
                                        <option value="Android">Android</option>
                                    </select>
                                </td>
                                <td>
                                    <label>OS Version</label>
                                    <select name="os_version" id="os_version" class="form-control">
                                        <option value="2.0">2.0</option>
                                        <option value="Old version">Old version</option>
                                    </select>
                                </td>
                                <td><br><button type="submit" class="btn btn-info btn-lg btn-success">SUBMIT</button></td>
                            </tr>
                            </tbody>
                        </table>
                        </fieldset>
                    </form>
                    </div>
                </section>
            <br>
        </form>
        </div>
</div>
<br><br>
</div>
<br><br><br>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<script src="js/jquery/2.1.1/jquery.min.js"></script>
<!-- this, preferably, goes inside head element: -->
<!--[if lt IE 9]>
 <![endif]-->
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

<div class="modal"></div>
</body>
</html>