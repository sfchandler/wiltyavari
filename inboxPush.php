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
<body class="">

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

    <!-- RIBBON -->
    <div id="ribbon">
				<span class="ribbon-button-alignment">
				</span>
        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <?php include "template/breadcrumblinks.php"; ?>
        </ol>
        <!-- end breadcrumb -->
    </div>
    <!-- END RIBBON -->

    <!-- MAIN CONTENT -->
    <div id="content">
        <!--<div class="inbox-nav-bar no-content-padding">
            <h1 class="page-title txt-color-blueDark hidden-tablet"><i class="fa fa-fw fa-inbox"></i> Inbox &nbsp;
            </h1>
        </div>-->
        <div id="inbox-content" class="inbox-body no-content-padding">
            <!--<div class="inbox-side-bar">
                <ul class="inbox-menu-lg">
                    <li class="active">
                        <a class="inbox-load" href="javascript:void(0);"> Inbox (<span class="numRows"></span>) </a>
                    </li>
                </ul>
            </div>-->
            <div class="table-wrap custom-scroll animated fast fadeInRight">
                <!-- ajax will fill this area LOADING... -->
                <div class="well">
                    <!-- row -->
                    <div class="row">
                        <div class="col-sm-12">
                            <form name="frmSearch" action="" method="post">
                                <section class="col-sm-3">
                                    <input class="form-control input-md" type="text" name="searchTxt" id="searchTxt" placeholder="Search on Resume..." value="<?php if(!empty($_SESSION['searchTxt'])){echo $_SESSION['searchTxt'];} ?>"></section>
                                <section class="col-sm-3">
                                    <input class="form-control input-md" type="text" name="subjectSearchTxt" id="subjectSearchTxt" placeholder="Search By Subject..." value="<?php if(!empty($_SESSION['subjectSearchTxt'])){echo $_SESSION['subjectSearchTxt'];} ?>">
                                </section>
                                <section class="col-sm-3">
                                    <input class="form-control input-md" type="text" name="fromSearchTxt" id="fromSearchTxt" placeholder="Search From Whom..." value="<?php if(!empty($_SESSION['fromSearchTxt'])){echo $_SESSION['fromSearchTxt'];} ?>">
                                </section>
                                <div class="input-group-btn"><input type="hidden" name="srchCount" id="srchCount"/>
                                    <button id="searchBtn" class="searchBtn btn btn-default btn-primary" type="button" tabindex="0">
                                        &nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-search fa-lg"></i>&nbsp;&nbsp;&nbsp;
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="mailContainer">
                    <div class="mailListView">
                        <div class="mailBoxLink">
                            <ul class="inbox-menu-lg">
                                <li class="active">
                                    <a class="inbox-load" href="javascript:void(0);"> Inbox (<span class="numRows"><?php echo getInboxMessageCount($mysqli,$_SESSION['accountName']); ?></span>) </a>
                                </li>
                            </ul>
                        </div>
                        <div align="center"><span id="inbLoading">Loading Please wait...</span></div>
                        <div id="rowInbox" class="inboxList">
                            <input type="hidden" id="rCount" value=""/>
                            <table id="inb-table" class="mailListBody table table-striped table-hover">
                                <tbody id="inbody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mailView">
                        <table id="tblMailView"><!--width="100%" height="100%"-->
                            <tbody>
                            <tr>
                                <td width="10%">Subject:&nbsp;</td>
                                <td class="mailSubject">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="10%">From:&nbsp;</td>
                                <td class="mailFrom">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="10%">Sent Date:&nbsp;</td>
                                <td width="90%" class="sentDate">&nbsp;</td>
                            </tr>
                            <tr>
                                <td width="10%">To:&nbsp;</td>
                                <td class="mailTo"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="mailAttachments">&nbsp;</td>
                            </tr>
                            <tr>
                                <td colspan="2"><iframe class="mailBody" src="emailBody.php" width="100%" height="500px">&nbsp;</iframe>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="formDisplay"></div>
                    </div>
                </div>
            </div>
            <!-- END MAIN CONTENT -->
        </div>
        <!-- END MAIN PANEL -->

        <!-- PAGE FOOTER -->
        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white"> <?php echo DOMAIN_NAME; ?> <span class="hidden-xs"> - Employee Recruitment System</span> © <?php echo date('Y'); ?></span>
                </div>
                <div class="col-xs-6 col-sm-6 text-right hidden-xs">
                    <div class="txt-color-white inline-block">

                    </div>
                </div>
            </div>

        </div>
        <!-- END PAGE FOOTER -->
        <?php include "./template/js_links.php"; ?>
        <script type="text/javascript" src="js/SWPush.js"></script>

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