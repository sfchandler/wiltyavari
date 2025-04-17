<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}

/*$refString = '%ref:%';
$sql = $mysqli->prepare("SELECT autoid,subject,mailfrom,mailto FROM resume WHERE subject LIKE ? ORDER BY date DESC LIMIT 0,10")or die($mysqli->error);
$sql->bind_param("s",$refString)or die($mysqli->error);
$sql->execute();
$sql->store_result();
$sql->bind_result($autoid,$subject,$mailfrom,$mailto)or die($mysqli->error);*/

?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php";?>
    <style>
        .linkBox{
            width: 100%;
            height: 100%;
            border: 1px solid;
        }
        ul li{
            text-decoration: none;
            list-style-type: none;
        }
        .mailLink{
            cursor: pointer;
        }
    </style>
</head>
<body>
<!-- HEADER -->
<header id="header">
    <?php include "template/top_menu.php"; ?>
</header>
<!-- END HEADER -->
<!-- Left panel : Navigation area -->
<!-- Note: This width of the aside area can be adjusted through LESS variables -->
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
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <h2 align="center"><i class="fa fa-sitemap"></i>&nbsp;Mail Tree</h2>
        <div class="error"></div>
        <div class="container-fluid">
            <div class="row">
                <section class="col col-lg-2">
                    <div class="references"></div>
                </section>
                <section class="col col-lg-8">
                    <div class="mailList"></div>
                </section>
                <section class="col col-lg-2">
                    <div class="mailBody"></div>
                </section>
            </div>
        </div>
    </div>
    <!-- END MAIN CONTENT -->
</div>
<!-- END MAIN PANEL -->
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>



<script>
    $(document).ready(function(){
        /*$body = $("body");
        $(document).on({
            ajaxStart: function() { $body.addClass("loading"); },
            ajaxStop: function() { $body.removeClass("loading"); }
        });*/
        /*var tree = [
            {
                text: "Parent 1",
                nodes: [
                    {
                        text: "Child 1",
                        nodes: [
                            {
                                text: "Grandchild 1"
                            },
                            {
                                text: "Grandchild 2"
                            }
                        ]
                    },
                    {
                        text: "Child 2"
                    }
                ]
            },
            {
                text: "Parent 2"
            },
            {
                text: "Parent 3"
            },
            {
                text: "Parent 4"
            },
            {
                text: "Parent 5"
            }
        ];*/
        /*getTree();
        function getTree() {
            $.ajax({
                url: "getTree.php",
                type: "POST",
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('#tree').treeview({data: data});
                }
            });
        }*/
        getMailReferences();
        function getMailReferences(){
            var action ='REFERENCE';
            $.ajax({
                url: "mailSource.php",
                type: "POST",
                dataType: "html",
                data:{action:action},
                success: function (data) {
                    $('.references').html('');
                    $('.references').html(data);
                }
            });
        }
        $(document).on('click','.referenceLink',function (){
            var reference = $(this).data('reference');
            var action ='LIST';
            $.ajax({
                url: "mailSource.php",
                type: "POST",
                dataType: "html",
                data:{action:action,reference:reference},
                success: function (data) {
                    $('.mailList').html('');
                    $('.mailList').html(data);
                }
            });
        });
        $(document).on('click','.mailLink',function (){
            var id = $(this).data('maillink');
            var action ='VIEW';
            $.ajax({
                url: "mailSource.php",
                type: "POST",
                dataType: "html",
                data:{action:action,id:id},
                success: function (data) {
                    $('.mailBody').html('');
                    $('.mailBody').html(data);
                }
            });
        });
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>

</body>

</html>