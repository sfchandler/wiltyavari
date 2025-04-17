<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <?php include "template/header.php"; ?>
    <style>
        /*.pendingTable tbody {
            display: block;
            height: 500px;
            overflow: auto;
        }
        .pendingTable thead, tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;!* even columns width , fix width of table too*!
        }
        .pendingTable thead {
            width: calc( 100% - 1em )!* scrollbar is average 1em/16px width, remove it from thead width *!
        }
        .pendingTable table {
            width: 400px;
        }*/
        #displayPending{
            width:15%;
            height: 460px;
            overflow: scroll;
            float: left;
            padding-left: 5px;
        }
        .tblJB{
            margin-left: 20px;
        }
        .jbOrdTbl{
            text-align: center;
            padding-left: 0px;
            height: 800px;
            width: 85%;
            overflow:auto;
            float: left;
        }
        .jbOrderTableHeaderCell{
            text-align: center;
        }
        .jbOrderTableCell{
            text-align: center;
            align-items: center;
            align-content: center;
        }
        .jobOrderTable{
            width: 100%;
            margin-left: 0px;
        }
        .jbOrderBubble{
            margin: 0 auto;
            background: #1b4f76;
            border-radius: 8%;
            width:70px;
            height:100px;
            align-items: center;
            text-align: center;
        }
        .jbOrderBubbleGreen{
            background: green;
        }
        .jbEdit{
            padding-top: 3px;
            color: white;
        }
        .jbEdit :hover{
            color: #00ffff;
        }
        .jbCollapse{
            color: white;
        }
        .jbCollapse :hover{
            color: #00ffff;
        }
        .jbQty{
            color: #00FF00;
            font-size: 12pt;
            font-weight: bold;
            line-height: 18px;
        }
        .jbInfo{
            padding-top: 2px;
            color: #FFFFFF;
            font-size: 9pt;
        }
        .jbEmp{
            text-align: left;
        }
        .jbMale{
            color: #00ffff;
            font-size: 10pt;
            font-weight: bold;
        }
        .jbFemale{
            color: #FB87C9;
            font-size: 10pt;
            font-weight: bold;
        }
        .jbOrderAdd{
            font-size: 20pt;
            font-weight: bold;
            color: #2a9055;
            cursor: pointer;
        }
        .filledTotal{
            color: #2a3133;
            background-color: #00ffff;
            font-weight: bold;
        }
        .filledQty{
            color: #2a3133;
            background-color: #00FF00;
            font-weight: bold;
            animation: blinker 3s linear infinite;
        }
        .toFillQty {
            color: #2a3133;
            background-color: #ff7676;
            font-weight: bold;
            animation: blinker 3s linear infinite;
        }
        /*.toFillQty>td:nth-child(1) {
            background-color: #00FF00;
        }*/
        @keyframes blinker {
            50% {
                opacity: 0;
            }
        }
       /* th, td {
            padding: 2px;
        }
        th{
            width: 100%;
        }*/
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
        <div class="row">
            <h3 style="margin-left: 20px;"><i class="fa fa-tasks"></i>&nbsp;JOB ORDER INDEX<span style="text-align: center; align-items: center; align-content: center;padding-left: 300px;" class="error"></span></h3>
        </div>
        <div class="row">
            <div class="form-group">
            <table class="tblJB">
                <tbody>
                <tr>
                    <td>
                        <div id="reportrange" style="cursor: pointer; border: 1px solid #cccccc; height: 32px;">
                            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span></span> <b class="caret"></b>
                        </div>
                        <input type="hidden" name="startDate" id="startDate">
                        <input type="hidden" name="endDate" id="endDate">
                    </td>
                    <td>
                        <select name="clientId" id="clientId" style="cursor: pointer;" class="form-control">
                        </select>
                    </td>
                    <td>
                        <select name="stateId" id="stateId" style="cursor: pointer;" class="form-control">
                        </select>
                    </td>
                    <td>
                        <select name="departmentId" id="departmentId" class="form-control"
                                style="cursor: pointer;">
                        </select>
                    </td>
                    <td>
                        <select name="expPosition" id="expPosition" class="form-control"
                                style="cursor: pointer;">
                        </select>
                    </td>
                    <td>
                        <button class="filterBtn btn btn-md btn-primary">View <i class="glyphicon glyphicon-eye-open"></i></button>
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
            <div id="displayPending">
            </div>
            <div class="jbOrdTbl">
                <table class="jobOrderTable table table-striped table-bordered table-responsive">
                    <thead class="jobOrderHead">
                    </thead>
                    <tbody class="jobOrderBody">

                    </tbody>
                </table>
            </div>
            <div style="clear: both"></div>
            <br>
            <br>
            <br>
        </div>

        <div id="jobOrderPopup" style="width:500px; display:block">
            <form id="jbOrdFrm" name="jbOrdFrm" class="smart-form" method="post">
                <div class="row">
                    <input type="hidden" name="ordDate" id="ordDate">
                    <input type="hidden" name="clid" id="clid">
                    <input type="hidden" name="posid" id="posid">
                    <input type="hidden" name="deptid" id="deptid">
                    <input type="hidden" name="stateid" id="stateid">
                    <input type="hidden" name="ordStatus" id="ordStatus">
                    <section class="col col-12">
                        <span class="erMsg"></span>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label for="shiftStart">Start Time</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-clock-o"></i>
                            <input name="shiftStart" id="shiftStart" type="text" size="20" value="00:00">
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label for="ordQty">JobOrder Qty</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-shopping-bag"></i>
                            <input name="ordQty" id="ordQty" type="text" size="20" value="">
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-6">
                        <label for="ordMaleQty">Male Qty</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-shopping-bag"></i>
                            <input name="ordMaleQty" id="ordMaleQty" type="text" size="20" value="">
                        </label>
                    </section>
                    <section class="col col-6">
                        <label for="ordFemaleQty">Female Qty</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-shopping-bag"></i>
                            <input name="ordFemaleQty" id="ordFemaleQty" type="text" size="20" value="">
                        </label>
                    </section>
                </div>
            </form>
        </div>
        <div id="jobOrderEditPopup" style="width:500px; display:block">
            <form id="jbOrdEditFrm" name="jbOrdEditFrm" class="smart-form" method="post">
                <div class="row">
                    <input type="hidden" name="job_id" id="job_id"/>
                    <input type="hidden" name="eordDate" id="eordDate"/>
                    <input type="hidden" name="eclid" id="eclid"/>
                    <input type="hidden" name="eposid" id="eposid"/>
                    <input type="hidden" name="edeptid" id="edeptid"/>
                    <input type="hidden" name="estateid" id="estateid"/>
                    <input type="hidden" name="eordStatus" id="eordStatus"/>
                    <section class="col col-12">
                        <span class="erMsg"></span>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label for="shiftStart">Start Time</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-clock-o"></i>
                            <input name="eshiftStart" id="eshiftStart" type="text" size="20" value="00:00">
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label for="ordQty">JobOrder Qty</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-shopping-bag"></i>
                            <input name="eordQty" id="eordQty" type="text" size="20" value="">
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-6">
                        <label for="ordMaleQty">Male Qty</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-shopping-bag"></i>
                            <input name="eordMaleQty" id="eordMaleQty" type="text" size="20" value="">
                        </label>
                    </section>
                    <section class="col col-6">
                        <label for="ordFemaleQty">Female Qty</label>
                        <label class="input" style="width:110px"><i class="icon-append fa fa-shopping-bag"></i>
                            <input name="eordFemaleQty" id="eordFemaleQty" type="text" size="20" value="">
                        </label>
                    </section>
                </div>
            </form>
        </div>

    </div>
</div>
<!-- END MAIN PANEL -->
<?php include "template/footer.php"; ?>
<?php include "template/scripts.php"; ?>
<!-- DATE RANGE PICKER -->
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
<script src="js/jobOrderScript.js"></script>

<div class="modal"></div>
</body>
</html>