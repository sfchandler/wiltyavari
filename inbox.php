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
			<!-- MAIN CONTENT -->
			<div id="container">
                <ul id="inboxTab" class="nav nav-tabs">
                    <li class="active">
                        <a href="#jbl" data-toggle="tab"><i class="fa fa-fw fa-lg fa-inbox"></i>JOB BOARD (<span class="numRows"><?php echo getInboxMessageCount($mysqli,'jobboard'); ?></span>)</a>
                    </li>
                    <!--<li class="active">
                        <a href="#mlb" data-toggle="tab"><i class="fa fa-fw fa-lg fa-inbox"></i>SEEK (<span class="numRows"><?php /*echo getInboxMessageCount($mysqli,$_SESSION['accountName']); */?></span>)</a>
                    </li>-->
                </ul>
                <div id="myTabContent1" class="tab-content padding-10">
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
                                    <div class="input-group-btn">
                                        <button id="searchBtn" class="searchBtn btn btn-default btn-primary" type="button" tabindex="0">
                                            &nbsp;&nbsp;&nbsp;<i class="fa fa-fw fa-search fa-lg"></i>&nbsp;&nbsp;&nbsp;
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div id="jbl">
                        <div class="table-wrap custom-scroll animated fast fadeInRight">
                            <!-- ajax will fill this area LOADING... -->
                            <div class="mailContainer">
                                <div class="mailListView">
                                    <div class="mailBoxLink">

                                    </div>
                                    <div align="center"><span id="jbLoading">Loading Please wait...</span></div>
                                    <div id="jbRowInbox" class="jbInboxList" style="height:700px;overflow: scroll;">
                                        <input type="hidden" id="rCount" value=""/>
                                        <table id="jb-table" class="mailListBody table table-striped table-hover">
                                            <tbody id="jb-body">
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
                                            <td colspan="2"><div class="mailBody"  width=200 height=200 ></div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <div class="formJBDisplay"></div>
                                </div>
                            </div>
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


		<!-- END MAIN PANEL -->
                <br><br><br><br><br>
		<!-- PAGE FOOTER -->
		<div class="page-footer">
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<span class="txt-color-white"><?php echo DOMAIN_NAME; ?> <span class="hidden-xs"> - Employee Recruitment System</span> © <?php echo date('Y'); ?></span>
				</div>
				<div class="col-xs-6 col-sm-6 text-right hidden-xs">
					<div class="txt-color-white inline-block">
						
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE FOOTER -->
		<?php include "./template/js_links.php"; ?>
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