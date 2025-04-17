<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Australia/Melbourne');
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if ($_SESSION['userSession'] == '' && $_SESSION['userType'] != 'CONSULTANT') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}

?>
<!DOCTYPE html>
<html lang="en-us" style="background: white">
<head>
    <?php include "template/header.php"; ?>
    <style>
        .ui-menu {
            width: 200px;
        }

        .ui-widget-header {
            padding: 0.2em;
        }
    </style>
</head>
<body>
<header id="header">
    <?php include "template/top_menu.php"; ?>
</header>
<aside id="left-panel">
    <div class="login-info">
        <?php include "template/user_info.php"; ?>
    </div>
    <?php include "template/navigation.php"; ?>
    <span class="minifyme" data-action="minifyMenu">
				<i class="fa fa-arrow-circle-left hit"></i> 
			</span>
</aside>
<div id="main" role="main">
    <div id="content" class="container-body">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Release a shift</h3>
            </div>
            <div class="panel-body">
                <fieldset>
                    <div align="center">
                        <table class="table table-bordered" style="width:40%; padding: 15px;">
                            <tbody>
                            <tr>
                                <td style="text-align: center"><img src="img/tick-box.gif" width="20" height="20"
                                                                    alt=""> Shifts pushed to mobile app via Google
                                    firebase Cloud
                                </td>
                                <td style="text-align: center"><img src="img/cross-mark.png" width="20" height="20"
                                                                    alt=""> Overlapping shifts detected for candidate
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="msg" class="error"></div>
                    <div class="row">
                        <section class="col col-12 padding-10">
                            <div class="pull-left">
                                <label for="clientId" class="select">
                                    <select name="clientId" id="clientId" class="form-control"
                                            style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 8pt">
                                    </select><i></i></label>
                            </div>
                            <div class="pull-left">
                                <label for="stateId" class="select">
                                    <select name="stateId" id="stateId" class="form-control"
                                            style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 8pt">
                                    </select><i></i></label>
                            </div>
                            <div class="pull-left">
                                <label for="departmentId" class="select">
                                    <select name="departmentId" id="departmentId" class="form-control"
                                            style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 8pt">
                                    </select><i></i></label>
                            </div>

                            <div class="pull-left">
                                <label for="empPosition" class="select">
                                    <select name="empPosition" id="empPosition" class="form-control"
                                            style="cursor: pointer; padding: 5px 10px; width: 100%; font-size: 8pt">
                                    </select><i></i></label>
                            </div>
                            <div class="pull-left">
                                <label for="shiftLocation">
                                    <select name="shiftLocation" id="shiftLocation" class="form-control"></select>
                                </label>
                            </div>

                            <input type="text" name="rel_date" id="rel_date" value="" class="pull-left"
                                   style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 15%; height: 32px;"
                                   placeholder="Release Date"/>
                            <input name="rel_start" id="rel_start" type="text" size="20" value="00:00"
                                   class="timepicker pull-left"
                                   style="cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 5%; height: 32px;">
                            <input name="rel_end" id="rel_end" type="text" size="20" value="00:00"
                                   class="timepicker pull-left"
                                   style="cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 5%; height: 32px;">
                            <button name="releaseBtn" id="releaseBtn" type="submit"
                                    class="releaseBtn btn btn-info reverse btn-square btn-sm"><i class="fa fa-feed"></i>&nbsp;RELEASE
                                SHIFT
                            </button>
                            <br><br>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-12 padding-10">
                            <div id="employeeList" class="scroll-custom"
                                 style="padding-left: 13px; width: 550px; height: 200px; overflow-y: scroll"></div>
                        </section>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">All Released Shifts</h3><div style="text-align: right"><button id="exportConReleaseBtn" class="btn btn-info reverse btn-sm"><i class="fa fa-file-excel-o"></i>&nbsp;Export</button>&nbsp;<button id="reloadBtn" class="btn btn-info reverse btn-sm"><i class="fa fa-refresh"></i>&nbsp;Reload</button></div>
            </div>
            <div class="panel-body">
                <div id="rel_shift_display">
                </div>
            </div>
        </div>
    </div>
    <br><br><br>
</div>
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>
<script type="text/javascript" src="js/daterangepicker/moment.js"></script>
<script type="text/javascript" src="js/daterangepicker/daterangepicker.js"></script>
<script src="js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="js/plugin/jquery-validate/additional-methods.js"></script>
<script src="js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<script src="js/jqueryform/jquery.form.js"></script>
<script src="js/fixTableHeader/jQuery.fixTableHeader.min.js"></script>
<script src="js/chosen_v1.8.7/chosen.jquery.js"></script>
<script src="js/release.js"></script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>