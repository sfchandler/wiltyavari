<nav>
				<ul>
					<!--<li>
						<a href="#" title="Dashboard"><i class="fa fa-lg fa-fw fa-home"></i> <span class="menu-item-parent">Dashboard</span></a>
						<ul>
							<li>
                            &nbsp;
								<a href="index.php" title="Dashboard"><span class="menu-item-parent">Analytics Dashboard</span></a>
							</li>
						</ul>	
					</li>-->
					<!--<li class="top-menu-invisible">
						<a href="#"><i class="fa fa-lg fa-fw fa-cube txt-color-blue"></i> <span class="menu-item-parent">Chandler Services Admin Intel</span></a>
						<ul>
							<li class="">
								<a href="layouts.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-gear"></i> <span class="menu-item-parent">App Layouts</span></a>
							</li>
							<li class="">
								<a href="skins.html" title="Dashboard"><i class="fa fa-lg fa-fw fa-picture-o"></i> <span class="menu-item-parent">Prebuilt Skins</span></a>
							</li>
							<li>
								<a href="applayout.html"><i class="fa fa-cube"></i> App Settings</a>
							</li>
						</ul>
					</li>-->
                    <?php
                    $consultant_id = getConsultantId($mysqli, $_SESSION['userSession']);
                    //if(($_SESSION['userSession'] != 'Swarnajith') || ($_SESSION['userSession'] != '') ||($_SESSION['userSession'] != '')) {
                    if (!in_array($consultant_id, array(105,111,112))) {
                    ?>
                    <li class="active">
                        <a href="main.php"><i class="fa fa-lg fa-fw fa-dashboard"></i> <span>Dashboard</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
					<li class="active">
						<a href="mailbox.php"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="manageReferences.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Manage Inbox References</a>
                            </li>
							<!--<li class="">
                               <a href="candidateInfo.php" title="Dashboard"><i class="fa fa-lg fa-fw fa-file-text-o"></i> <span class="menu-item-parent">Registered Candidates</span></a>
                            </li>-->
                        </ul>

					</li>
                    <li class="active">
                        <!-- inbox.php -->
                        <a href="jobBoardMailbox.php"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Jobboard</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="manageJBReferences.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Manage Job Board References</a>
                            </li>
                        </ul>
                    </li>
                    <li class="active">
                        <a href="talent.php"><i class="fa fa-lg fa-fw fa-inbox"></i> <span>Talent Requests </span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <?php } ?>
                    <!--<li class="active">
						<a href="dashBoard.php"><i class="fa fa-lg fa-fw fa-desktop"></i> <span class="menu-item-parent">Dashboard</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>-->
                    <li class="active">
						<a href="dashboardMain.php"><i class="fa fa-lg fa-fw fa-search"></i> <span>Search Candidates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <li class="active">
						<a href="candidateMain.php"><i class="fa fa-lg fa-fw fa-users"></i> <span>Candidates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <?php if (!in_array($consultant_id, array(105,111,112))) { ?>
                        <ul>
                            <li class="active">
                                <a href="candidatePositions.php"><i class="fa fa-lg fa-fw fa-certificate"></i> Position Types</a>
                            </li>
                            <li class="active">
                                <a href="employmentTerms.php"><i class="fa fa-lg fa-fw fa-certificate"></i> Employment Terms Agreement</a>
                            </li>
                            <li class="active">
                                <a href="timeclockReport.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Time Clock Report</a>
                            </li>
                            <li class="active">
                                <a href="vacancies.php"><i class="fa fa-lg fa-fw fa-volume-down"></i>Email/SMS Vacancies</a>
                            </li>
                        </ul>
                        <?php } ?>
                    </li>
                    <?php if (!in_array($consultant_id, array(105,111,112))) { ?>
                    <li class="active">
                        <a href="todoList.php"><i class="fa fa-lg fa-fw fa-sticky-note"></i> <span>To do List</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="kpiReport.php"><i class="fa fa-lg fa-fw fa-sticky-note"></i> <span>KPI Report</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="active">
                        <a href="calendar.php"><i class="fa fa-lg fa-fw fa-calendar"></i> <span>Appointments</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <?php if($_SESSION['userType']=='ADMIN'){ ?>
                        <li class="active">
                            <a href="companyMain.php"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Company Info</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        </li>
                    <?php }?>
                    <li class="active">
                        <a href="#"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Client</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="clientMain.php"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Client Information</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                <?php if($_SESSION['userType']=='ACCOUNTS' || $_SESSION['userType']=='ADMIN'){ ?>
                                    <ul>
                                        <li>
                                            <a href="clientTerms.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Client Terms</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </li>
                            <?php if($_SESSION['userType']=='ACCOUNTS' || $_SESSION['userType']=='ADMIN'){ ?>
                                <li class="active">
                                    <a href="createJobCode.php"><i class="fa fa-lg fa-fw fa-indent"></i>JobCode/Job Detail</a>
                                </li>
                                <li class="active">
                                    <a href="createRateCard.php"><i class="fa fa-lg fa-fw fa-dollar"></i>Create Rate Card</a>
                                </li>
                                <li class="active">
                                    <a href="payrule.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Payrule Definition</a>
                                </li>
                            <?php } ?>
                            <li class="active">
                                <a href="clientDocuments.php"><i class="fa fa-lg fa-fw fa-file"></i>Client Documents</a>
                            </li>
                            <li class="active">
                                <a href="clientShiftLocation.php"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Client Shift Location</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            </li>
                            <li class="active">
                                <a href="clientDepartments.php"><i class="fa fa-lg fa-fw fa-indent"></i>Client Departments</a>
                            </li>
                            <li class="active">
                                <a href="supervisor.php"><i class="fa fa-lg fa-fw fa-file"></i>Supervisor Information</a>
                            </li>
                            <li class="active">
                                <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Labour Hire Rates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                <ul>
                                    <li class="active">
                                        <a href="hireRates.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Generate/Send Labour Hire Rates </a>
                                    </li>
                                </ul>
                            </li>
                           <!-- <li class="active">
                                <a href="casualClockInList.php"><i class="fa fa-lg fa-fw fa-file"></i>Casuals ClockIn List</a>
                            </li>-->
                        </ul>
                    </li>
                    <li class="active">
						<a href="scheduleMain.php"><i class="fa fa-lg fa-fw fa-history"></i> <span>Schedule</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li>
                                <a href="rosterReport.php"><i class="fa fa-lg fa-fw fa-file-excel-o"></i>Roster Report</a>
                            </li>
                            <li>
                                <a href="rosterOpenShifts.php"><i class="fa fa-lg fa-fw fa-file-excel-o"></i>Export Open/ClockIn Shifts</a>
                            </li>
                            <li>
                                <a href="candidateImport.php"><i class="fa fa-lg fa-fw fa-file-excel-o"></i>Import Candidates</a>
                            </li>
                            <li>
                                <a href="smsStatusReport.php"><i class="fa fa-lg fa-fw fa-file-excel-o"></i>SMS Status Report</a>
                            </li>
                            <li>
                                <a href="jobOrderIndex.php"><i class="fa fa-lg fa-fw fa-tasks"></i>Job Order Index</a>
                            </li>
                            <!--<li>
                                <a href="jobOrder.php"><i class="fa fa-lg fa-fw fa-hand-o-right"></i>Job Order</a>
                            </li>-->
                            <li>
                                <a href="sickReport.php"><i class="fa fa-lg fa-fw fa-hospital-o"></i>Sick Report</a>
                            </li>
                            <li>
                                <a href="covidDeclarationReport.php"><i class="fa fa-lg fa-fw fa-hospital-o"></i>COVID19 Health Declaration Report</a>
                            </li>
                        </ul>
                    </li>
                    <?php if($_SESSION['userType']=='ACCOUNTS' || $_SESSION['userType']=='ADMIN'){ ?>
                        <li class="active">
                            <a href="#"><i class="fa fa-lg fa-fw fa-money"><i class="fa fa-lg fa-fw fa-exchange"></i></i><span>Payroll</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            <ul>
                                <li class="active">
                                    <a href="transactionCode.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Transaction Code Details</a>
                                </li>
                                <li class="active">
                                    <a href="profitCentre.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Profit Centre</a>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Tax</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="taxFormulaCodes.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Tax Formula Codes</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Pay</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="companyBankAccount.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Company Bank Account</a>
                                        </li>
                                        <li class="active">
                                            <a href="corporateAccount.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Corporate Bank Account</a>
                                        </li>
                                        <li class="active">
                                            <a href="createPayCategory.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Create PayCategory</a>
                                        </li>
                                        <li class="active">
                                            <a href="rateImport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Import Rate Card</a>
                                        </li>
                                        <li class="active">
                                            <a href="paySlip.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>PaySlip</a>
                                            <ul>
                                                <li>
                                                    <a href="payrollDetail.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Payroll Details</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="active">
                                            <a href="makePayment.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Make Payment</a>
                                        </li>
                                        <!--
                                        <li class="active">
                                            <a href="payReversal.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Pay Reversal</a>
                                        </li>
                                        -->
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Timesheet</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="timesheetDetails.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Timesheet Details</a>
                                        </li>
                                        <li class="active">
                                            <a href="timeclockTransfer.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Timeclock Data Transfer</a>
                                        </li>
                                        <li class="active">
                                            <a href="timesheetCheck.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>TimeSheet Check</a>
                                        </li>
                                        <li class="active">
                                            <a href="timesheet.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>TimeSheet Calculation</a>
                                        </li>
                                        <li class="active">
                                            <a href="manualTimesheetTotals.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Add Manual Timesheet Totals</a>
                                        </li>
                                        <li class="active">
                                            <a href="manualTimesheetImport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Manual Timesheet Totals Import</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Payroll Amendments</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="manualPayroll.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Manual Payroll Entry</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Single Touch</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="superCalculator.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Calculate Super & Save</a>
                                        </li>
                                        <li class="active">
                                            <a href="singletouchFirst.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Single Touch Initial CSV</a>
                                        </li>
                                        <li class="active">
                                            <a href="singletouchRegularReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Single Touch Regular CSV</a>
                                        </li>
                                        <!--<li class="active">
                                            <a href="singletouch.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Single Touch CSV</a>
                                        </li>-->
                                    </ul>
                                </li>
                                <!--<li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Labour Hire Rates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="hireRates.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Generate/Send Labour Hire Rates </a>
                                        </li>
                                    </ul>
                                </li>-->
                            </ul>
                        </li>
                        <li class="active">
                            <a href="#"><i class="fa fa-lg fa-fw fa-files-o"></i> <span>Invoice</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            <ul>
                                <!--<li class="active">
                                    <a href="invoiceCreation.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Bulk Invoice Creation</a>
                                </li>
                                <li class="active">
                                    <a href="clientInvoiceCreation.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Client Based Invoice Creation</a>
                                </li>-->
                                <li class="active">
                                    <a href="invoiceAdditions.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Invoice Additions</a>
                                </li>
                                <li class="active">
                                    <a href="allInvoiceCreation.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>All Invoice Creation</a>
                                </li>
                                <li class="active">
                                    <a href="invoiceReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Invoice Report</a>
                                </li>
                                <li class="active">
                                    <a href="myob.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>MYOB CSV Generation</a>
                                </li>
                            </ul>
                        </li>
                        <li class="active">
                            <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Reports</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            <ul>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Timesheet Reports</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="timesheetAuditReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>TimeSheet Audit Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="timesheetReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Timesheet Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="timesheetHoursReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Timesheet Hours Report</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Payroll Reports</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="payrollCalculation.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Payroll Calculation Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="candidateBalance.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Candidate Balance Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="candidateReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Candidate List Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="superCalculationReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Super Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="timeclockReport.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Time Clock Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="pendingSupervisorApprovals.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Pending Supervisor Approval Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="paygSummary.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>PAYG Summary Generation</a>
                                        </li>
                                        <li class="active">
                                            <a href="transactionList.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Transaction List Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="paysheetReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Paysheet Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="payrollReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Payroll Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="tempVisaReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Visa Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="auditCheckReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Audit Check Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="clientSummary.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Client Summary Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="policeCheckReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Police Check Deduction Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="wageSubsidyReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Wage Subsidy Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="staffLevelCheck.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Staff Level Check Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="transactionCodeReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Attached Transaction Code Report</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="active">
                        <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Consultant Reports</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="candidateReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Candidate List Report</a>
                            </li>
                            <li class="active">
                                <a href="rateCardView.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Rate Card View</a>
                            </li>
                            <li class="active">
                                <a href="paysheetReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Paysheet Report</a>
                            </li>
                            <li class="active">
                                <a href="timeclockReport.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Time Clock Report</a>
                            </li>
                            <li class="active">
                                <a href="ohs_report.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>OHS Report</a>
                            </li>
                        </ul>
                    </li>
                    <?php if($_SESSION['userType']=='ADMIN'){  ?>
                    <li class="active">
                        <a href="adminDashboard.php"><i class="fa fa-lg fa-fw fa-lock"></i><span>Admin</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="adminDashboard.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>User Management</a>
                            </li>
                            <li class="active">
                                <a href="manageAttributes.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Add/Edit Attributes</a>
                            </li>
                            <!--
                                <li class="active">
                                    <a href="intvwMail.php" target="_blank"><i class="fa fa-lg fa-fw fa-sticky-note"></i> <span>Interview Booking Email Content</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                </li>
                                <li>
                                    <a href="dbCleanup.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Mail Data Cleanup</a>
                                </li>
                            -->
                        </ul>
                    </li>
                    <li class="active">
                        <a href="systemDashboard.php"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span>System Maintenance</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <?php }
                    }?>
				</ul>
			</nav>