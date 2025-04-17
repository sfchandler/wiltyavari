<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if($_SESSION['userType']==''){
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if(isset($_REQUEST['default'])){
    $_SESSION['searchTxt'] = '';
    $_SESSION['subjectSearchTxt'] = '';
}

?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "./template/header.php";?>
</head>
<body style="background: white">

<!-- HEADER -->
<header id="header">
    <?php include "./template/top_menu.php";
    $error_msg = false;
    if(isset($_REQUEST['error_msg'])){
        $error_msg = base64_decode($_REQUEST['error_msg']);
    }
    echo $error_msg;
    ?>
</header>
<!-- END HEADER -->

<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
<aside id="left-panel">
    <!-- User info -->
    <div class="login-info">
        <?php include "./template/user_info.php"; ?>
    </div>
    <!-- end user info -->
    <?php include "./template/navigation.php"; ?>
    <span class="minifyme" data-action="minifyMenu">
				<i class="fa fa-arrow-circle-left hit"></i>
			</span>

</aside>
<!-- END NAVIGATION -->
<!-- MAIN PANEL -->
<div id="main" role="main">
    <!-- MAIN CONTENT -->
    <div id="container">
        <ul id="inboxTab" class="nav nav-tabs">
            <li>
                <a href="#jbl" data-toggle="tab"><b><i class="fa fa-fw fa-lg fa-inbox"></i>JOB BOARD (<span class="numRows"><?php echo getInboxMessageCount($mysqli,'jobboard'); ?></span></b>)</a>
            </li>
        </ul>
        <div id="myTabContent1" class="tab-content">
            <div class="tab-pane fade in active" id="mlb">
                <div class="table-wrap custom-scroll animated fast fadeInRight">
                    <div id="rowInbox">
                        <input type="hidden" id="rCount" value=""/>
                        <table id="tblMail" class="table">
                            <thead id="tblHead">
                            <tr>
                                <th>Reference Code/Position &nbsp;&nbsp;&nbsp;<button id="viewInactiveBtn" class="viewInactiveBtn btn btn-sm btn-warning"><i class="glyphicon glyphicon-eye-open"></i> View Inactive</button><button id="viewActiveBtn" class="viewActiveBtn btn btn-sm btn-primary"><i class="glyphicon glyphicon-eye-open"></i> View Active</button></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody id="inboxBody">

                            </tbody>
                        </table>
                    </div>

                    <div id="commentPopup" style="width:500px; display:block">
                        <form id="commentFrm" name="commentFrm" class="smart-form" method="post" action="saveMailComment.php">
                            <div class="row">
                                <section class="col col-12" style="width:100%;height:100%;">
                                    <label class="textarea textarea-resizable">
                                        <input type="hidden" id="mAutoId" name="mAutoId" value=""/>
                                        <textarea rows="20" class="custom-scroll textarea" name="mComment" id="mComment" placeholder="Add Comment" style="width: 100%"></textarea>
                                    </label>
                                </section>
                            </div>
                        </form>
                    </div>
                    <div id="jotEmailPopup" style="width:500px; display:block">
                        <form id="jotEmailFrm" name="jotEmailFrm" class="smart-form" method="post" action="jotEmail.php">
                            <div class="row">
                                <section class="col col-12" style="width:100%;height:100%;">
                                    <label class="textarea textarea-resizable">
                                        <input type="hidden" name="consultantEmail" id="consultantEmail" value="<?php echo getConsultantEmail($mysqli,getConsultantId($mysqli,$_SESSION['userSession'])); ?>"/>
                                        <input type="text" id="jotEmailAddress" name="jotEmailAddress" value="" class="form-control"/>
                                        <textarea rows="10" class="custom-scroll textarea" name="jotEmailBody" id="jotEmailBody" placeholder="" style="width: 100%; display: none"></textarea>
                                        <div id="jotEmailText"></div>
                                    </label>
                                </section>
                            </div>
                        </form>
                    </div>
                    <div id="taxEmailPopup" style="width:500px; display:block">
                        <form id="taxEmailFrm" name="taxEmailFrm" class="smart-form" method="post" action="formsEmail.php">
                            <div class="row">
                                <section class="col col-12" style="width:100%;height:100%;">
                                    <div class="taxError" style="color: red"></div>
                                    <label class="textarea textarea-resizable">
                                        <input type="hidden" name="taxConsultantEmail" id="taxConsultantEmail" value="<?php echo getConsultantEmail($mysqli,getConsultantId($mysqli,$_SESSION['userSession'])); ?>"/>
                                        <input type="text" id="taxEmailAddress" name="taxEmailAddress" value="" class="form-control"/>
                                        <textarea rows="10" class="custom-scroll textarea" name="taxEmailBody" id="taxEmailBody" placeholder="" style="width: 100%; display: none"></textarea>
                                        <div id="taxEmailText"></div>
                                    </label>
                                </section>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--<div class="tab-pane fade" id="jbl">
                <div class="table-wrap custom-scroll animated fast fadeInRight">
                    <div class="mailContainer">
                        <div class="mailList" style="width: 70%;float:left;">
                            <div id="jbRowInbox" class="jbInboxList">
                                <input type="hidden" id="rCount" value=""/>
                                <table class="table table-borderless table-striped">
                                    <thead>
                                    <tr>
                                        <th>Reference Code/Position</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody id="inboxJBBody">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mailInfo" style="width: 30%;float:right;">

                        </div>
                    </div>

                </div>
            </div>-->
        </div>
        <br><br>
        <!-- PAGE FOOTER -->
        <div class="page-footer" style="position: fixed;left: 0; bottom: 0;">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white">  <?php echo DOMAIN_NAME; ?> <span class="hidden-xs"> - Employee Recruitment System</span> © <?php echo date('Y'); ?></span>
                </div>
                <div class="col-xs-6 col-sm-6 text-right hidden-xs">
                    <div class="txt-color-white inline-block">

                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE FOOTER -->
        <?php include "./template/job_board_scripts.php"; ?>
        <?php
        if(!empty($_SESSION['searchTxt']) || !empty($_SESSION['subjectSearchTxt'])){
            echo "<script language='javascript'>";
            echo "$(function(){ $('#searchBtn').trigger('click');});";
            echo "</script>";
        }
        ?>
        <div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>