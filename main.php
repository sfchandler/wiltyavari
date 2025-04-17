<?php
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if ($_SESSION['userSession'] == '')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
$consultant_id = getConsultantId($mysqli, $_SESSION['userSession']);
/*if (in_array($consultant_id, array(105,111,112))) {
    header("Location:dashboardMain.php");
}*/
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
        <h2 align="center"><i class="glyphicon glyphicon-dashboard"></i>&nbsp;Dashboard</h2>
        <div class="error"></div>
        <div class="container-fluid">
            <div class="row">
                <section class="col col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="glyphicon glyphicon-inbox"></i>&nbsp;Inbox</div>
                        <div class="panel-body"><a href="mailbox.php">Mails</a></div>
                    </div>
                </section>
                <section class="col col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>&nbsp;Person</div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="candidateMain.php"><i class="fa fa-user-secret"></i>&nbsp;Casual Profile</a></li>
                                <li><a href="dashboardMain.php"><i class="glyphicon glyphicon-search"></i>&nbsp;Search Person</a></li>
                                <li><a href="candidatePositions.php"><i class="glyphicon glyphicon-arrow-right"></i>&nbsp;Casual Position Type</a></li>
                                <!--<li><a href="employmentTerms.php"><i class="glyphicon glyphicon-arrow-right"></i>&nbsp;Send Employment Agreement</a></li>-->
                                <li><a href="timeclockReport.php"><i class="glyphicon glyphicon-record"></i>&nbsp;Timeclock Report</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section class="col col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>&nbsp;Consultant</div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="todoList.php"><i class="glyphicon glyphicon-list"></i>&nbsp;Todo List</a></li>
                                <li><a href="kpiReport.php"><i class="glyphicon glyphicon-record"></i>&nbsp;KPI Report</a></li>
                                <li><a href="rateCardView.php"><i class="glyphicon glyphicon-eye-open"></i>&nbsp;Rate Card View</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section class="col col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="glyphicon glyphicon-calendar"></i>&nbsp;Appointments</div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="calendar.php"><i class="glyphicon glyphicon-calendar"></i>&nbsp;Appointments</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>
            <div class="row">
                <section class="col col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-industry"></i>&nbsp;Client</div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="clientMain.php"><i class="fa fa-institution"></i>&nbsp;Clients Information</a></li>
                                <?php if($_SESSION['userType']!='CONSULTANT'){ ?>
                                    <li><a href="clientTerms.php"><i class="fa fa-institution"></i>&nbsp;Clients Terms</a></li>
                                <?php } ?>
                                <li><a href="clientShiftLocation.php"><i class="fa fa-institution"></i>&nbsp;Clients Shift Locations</a></li>
                                <li><a href="clientTerms.php"><i class="glyphicon glyphicon-file"></i>&nbsp;Clients Terms</a></li>
                                <li><a href="clientDepartments.php"><i class="glyphicon glyphicon-arrow-right"></i>&nbsp;Clients Departments</a></li>
                                <li><a href="clientDocuments.php"><i class="glyphicon glyphicon-open-file"></i>&nbsp;Clients Documents</a></li>
                                <li><a href="supervisor.php"><i class="glyphicon glyphicon-user"></i>&nbsp;Supervisor Information</a></li>
                                <li><a href="hireRates.php"><i class="glyphicon glyphicon-user"></i>&nbsp;Generate/Send Labour Hire Rates</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section class="col col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="glyphicon glyphicon-time"></i>&nbsp;Schedule</div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="scheduleMain.php"><i class="glyphicon glyphicon-time"></i>&nbsp;Schedule/Roster</a></li>
                                <li><a href="rosterReport.php"><i class="fa fa-file-excel-o"></i>&nbsp;Roster Report</a></li>
                                <li><a href="rosterOpenShifts.php"><i class="fa fa-file-excel-o"></i>&nbsp;Open Shifts Report</a></li>
                                <li><a href="candidateImport.php"><i class="fa fa-file-excel-o"></i>&nbsp;Import Candidates</a></li>
                                <li><a href="smsStatusReport.php"><i class="fa fa-file-excel-o"></i>&nbsp;Roster SMS Status Report</a></li>
                                <li><a href="jobOrder.php"><i class="fa fa-hand-o-right"></i>&nbsp;Job Order</a></li>
                                <li><a href="sickReport.php"><i class="fa fa-hand-o-right"></i>&nbsp;Sick Report</a></li>
                                <li><a href="covidDeclarationReport.php"><i class="fa fa-hand-o-right"></i>&nbspCOVID19 Health Declaration Report</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <section class="col col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="glyphicon glyphicon-open-file"></i>&nbsp;Consultant Reports</div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="candidateReport.php"><i class="glyphicon glyphicon-time"></i>&nbsp;Candidate List</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <?php if($_SESSION['userType']!='CONSULTANT'){ ?>
                <section class="col col-lg-3">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="glyphicon glyphicon-open-file"></i>&nbsp;Reports</div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="timesheetAuditReport.php"><i class="glyphicon glyphicon-time"></i>&nbsp;Timesheet Audit</a></li>
                                <li><a href="timesheetReport.php"><i class="fa fa-file-excel-o"></i>&nbsp;Timesheet Check</a></li>
                                <li><a href="timesheetHoursReport.php"><i class="fa fa-file-excel-o"></i>&nbsp;Timesheet Hours</a></li>
                                <li><a href="payrollCalculation.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Payroll Calculation</a></li>
                                <li><a href="candidateBalance.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Candidate Balance</a></li>
                                <li><a href="candidateReport.php"><i class="fa fa-file-excel-o"></i>&nbsp;Candidate List</a></li>
                                <li><a href="superCalculationReport.php"><i class="fa fa-file-excel-o"></i>&nbsp;Super Calculation</a></li>
                                <li><a href="timeclockReport.php"><i class="fa fa-file-excel-o"></i>&nbsp;Timeclock</a></li>
                                <li><a href="pendingSupervisorApprovals.php"><i class="fa fa-file-excel-o"></i>&nbsp;Pending Supervisor Approvals</a></li>
                                <li><a href="paygSummary.php"><i class="fa fa-file-pdf-o"></i>&nbsp;PAYG Summary</a></li>
                                <li><a href="transactionList.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Transaction List</a></li>
                                <li><a href="paysheetReport.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Pay Sheet</a></li>
                                <li><a href="payrollReport.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Payroll</a></li>
                                <li><a href="tempVisaReport.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Temporary Student Visa</a></li>
                                <li><a href="auditCheckReport.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Audit Check</a></li>
                                <li><a href="clientSummary.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Client Summary</a></li>
                                <li><a href="policeCheckReport.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Police Check Report</a></li>
                                <li><a href="wageSubsidyReport.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Wage Subsidy Report</a></li>
                                <li><a href="staffLevelCheck.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Staff Level Check Report</a></li>
                                <li><a href="transactionCodeReport.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Attached Transaction Code Report</a></li>
                                <li><a href="invoiceReport.php"><i class="fa fa-file-pdf-o"></i>&nbsp;Invoice Report</a></li>
                            </ul>
                        </div>
                    </div>
                </section>
                <?php } ?>
            </div>
            <div class="row">
                <?php if($_SESSION['userType']!='CONSULTANT'){ ?>
                <section class="col col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-money"><i class="fa fa-exchange"></i></i>&nbsp;Payroll</div>
                        <div class="panel-body">
                            <ul>
                                <li><a href="createJobCode.php"><i class="fa fa-code"></i>&nbsp;Job Codes</a></li>
                                <li><a href="transactionCode.php"><i class="fa fa-code"></i>&nbsp;Transaction Codes</a></li>
                                <li><a href="profitCentre.php"><i class="fa fa-houzz"></i>&nbsp;Profit Centre Information</a></li>
                            </ul>
                            <div class="row">
                                <section class="col col-lg-3">
                                <div class="panel panel-default">
                                    <div class="panel-heading"><i class="fa fa-money"><i class="fa fa-exchange"></i></i>&nbsp;Tax</div>
                                    <div class="panel-body">
                                        <ul>
                                            <li><a href="taxFormulaCodes.php"><i class="fa fa-money"></i>&nbsp;Tax Formula</a></li>
                                        </ul>
                                    </div>
                                </div>
                                </section>
                                <section class="col col-lg-4">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><i class="fa fa-money"><i class="fa fa-credit-card"></i></i>&nbsp;Pay</div>
                                        <div class="panel-body">
                                            <ul>
                                                <li><a href="companyBankAccount.php"><i class="fa fa-money"></i>&nbsp;Company Bank Account</a></li>
                                                <li><a href="corporateAccount.php"><i class="fa fa-money"></i>&nbsp;Corporate Bank Account</a></li>
                                                <li><a href="createPayCategory.php"><i class="glyphicon glyphicon-open-file"></i>&nbsp;Pay Category</a></li>
                                                <li><a href="createRateCard.php"><i class="glyphicon glyphicon-open-file"></i>&nbsp;Rate Card</a></li>
                                                <li><a href="rateImport.php"><i class="glyphicon glyphicon-open-file"></i>&nbsp;Import Rate Card</a></li>
                                                <li><a href="payrule.php"><i class="glyphicon glyphicon-open-file"></i>&nbsp;Pay Rules</a></li>
                                                <li><a href="payrollDetail.php"><i class="fa fa-file-text"></i>&nbsp;Payroll Details</a></li>
                                                <li><a href="paySlip.php"><i class="fa fa-file-text"></i>&nbsp;Payslip</a></li>
                                                <li><a href="makePayment.php"><i class="glyphicon glyphicon-open-file"></i>&nbsp;Payments</a></li>
                                                <li><a href="allInvoiceCreation.php"><i class="glyphicon glyphicon-open-file"></i>&nbsp;All Invoice Creation</a></li>
                                                <li><a href="invoiceAdditions.php"><i class="glyphicon glyphicon-open-file"></i> Invoice Additions</a></li>
                                                <li><a href="myob.php"><i class="glyphicon glyphicon-open-file"></i>&nbsp;MYOB CSV</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                                <section class="col col-lg-3">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><i class="glyphicon glyphicon-time"><i class="fa fa-file-text"></i></i>&nbsp;Timesheet</div>
                                        <div class="panel-body">
                                            <ul>
                                                <li><a href="timesheetDetails.php"><i class="fa fa-check"></i>&nbsp;Timesheet Details</a></li>
                                                <li><a href="timeclockTransfer.php"><i class="fa fa-check"></i>&nbsp;Time Clock Transfer</a></li>
                                                <li><a href="timesheetCheck.php"><i class="fa fa-check"></i>&nbsp;Timesheet Check</a></li>
                                                <li><a href="timesheet.php"><i class="fa fa-check"></i>&nbsp;Timesheet Calculation</a></li>
                                                <li><a href="manualTimesheetTotals.php"><i class="fa fa-check"></i>&nbsp;Add Manual Timesheet Totals</a></li>
                                                <li><a href="manualTimesheetImport.php"><i class="fa fa-check"></i> Manual Timesheet Totals Import</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                                <section class="col col-lg-3">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><i class="glyphicon glyphicon-time"><i class="fa fa-file-text"></i></i>&nbsp;Payroll Amendments</div>
                                        <div class="panel-body">
                                            <ul>
                                                <li><a href="manualPayroll.php"><i class="fa fa-check"></i>&nbsp;Payroll Manual Entry</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                                <section class="col col-lg-2">
                                    <div class="panel panel-default">
                                        <div class="panel-heading"><i class="glyphicon glyphicon-time"><i class="fa fa-file-text"></i></i>&nbsp;SingleTouch</div>
                                        <div class="panel-body">
                                            <ul>
                                                <li><a href="superCalculator.php"><i class="fa fa-check"></i>&nbsp;Calculate Super & Save</a></li>
                                                <li><a href="singletouchFirst.php"><i class="fa fa-check"></i>&nbsp;Singletouch CSV Initial</a></li>
                                                <li><a href="singletouchRegularReport.php"><i class="fa fa-check"></i>&nbsp;Singletouch CSV Regular</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </section>
                <?php } ?>
            </div>
            <div class="row">

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
    });
</script>
<div class="modal"><!-- Place at bottom of page --></div>

</body>

</html>