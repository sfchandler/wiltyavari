<nav>
				<ul>
                    <?php
                    $consultant_id = getConsultantId($mysqli, $_SESSION['userSession']);
                    ?>
                    <li class="active">
                        <a href="main.php"><i class="fa fa-lg fa-fw fa-dashboard"></i> <span>Dashboard</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <li class="active">
                        <a href="jobadder.php"><i class="fa fa-lg fa-fw fa-dashboard"></i> <span>JobAdder</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <li class="active">
                        <a href="resumeShortList.php"><i class="fa fa-lg fa-fw fa-globe"></i><span class="menu-item-parent">Talent Pool</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <li class="active">
                        <a href="jobBoard.php"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Jobboard</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="manageJBReferences.php">Manage Job Board References</a>
                            </li>
                            <li class="active">
                                <a href="jobBoardUnsuccess.php">Unsuccessful Job Board Applications</a>
                            </li>
                        </ul>
                    </li>
					<li class="active">
						<a href="mailbox.php"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="manageReferences.php">Manage Inbox References</a>
                            </li>
                        </ul>
					</li>

                    <li class="active">
						<a href="dashboardMain.php"><i class="fa fa-lg fa-fw fa-search"></i> <span>Search Candidates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <li class="active">
						<a href="candidateMain.php"><i class="fa fa-lg fa-fw fa-users"></i> <span>Candidates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>

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

                    </li>

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
                    <?php } ?>
                    <li class="active">
                        <a href="#"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Client</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="clientMain.php"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Client Information</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                <?php if($_SESSION['userType']=='ACCOUNTS' || $_SESSION['userType']=='ADMIN'){ ?>
                                    <ul>
                                        <li>
                                            <a href="clientTerms.php">Client Terms</a>
                                        </li>
                                        <li>
                                            <a href="wicRates.php">Workcover Classifications</a>
                                        </li>
                                    </ul>
                                <?php } ?>
                            </li>
                            <li class="active">
                                <a href="clientDocuments.php"><i class="fa fa-lg fa-fw fa-file"></i>Client Documents</a>
                            </li>
                            <li class="active">
                                <a href="clientDepartments.php"><i class="fa fa-lg fa-fw fa-indent"></i>Client Departments</a>
                            </li>
                            <li class="active">
                                <a href="clientShiftLocation.php"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Client Shift Location</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            </li>
                            <?php if($_SESSION['userType']=='ACCOUNTS' || $_SESSION['userType']=='ADMIN'){ ?>
                                <li class="active">
                                    <a href="createJobCode.php"><i class="fa fa-lg fa-fw fa-indent"></i>JobCode/Job Detail</a>
                                </li>
                                <li class="active">
                                    <a href="rateCard.php"><i class="fa fa-lg fa-fw fa-dollar"></i>Create Rate Card</a>
                                </li>
                                <li class="active">
                                    <a href="payrule.php">Payrule Definition</a>
                                </li>
                            <?php } ?>
                            <li class="active">
                                <a href="supervisor.php"><i class="fa fa-lg fa-fw fa-file"></i>Supervisor Information</a>
                            </li>
                            <?php if($_SESSION['userType']=='ACCOUNTS' || $_SESSION['userType']=='ADMIN'){ ?>
                                <li class="active">
                                    <a href="holiday_info.php"><i class="fa fa-lg fa-fw fa-dollar"></i>Public Holidays</a>
                                </li>
                            <?php } ?>
                            <li class="active">
                                <a href="clientSurvey.php"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Client Survey</span></a>
                            <li class="active">
                                <a href="clientVisitReport.php"><i class="fa fa-lg fa-fw fa-users"></i>Client Visits</a>
                            </li>
                            <li class="active">
                                <a href="rec_job_description.php"><i class="fa fa-lg fa-fw fa-users"></i>Recruitment Job Descriptions</a>
                            </li>
                            <li class="active">
                                <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Labour Hire Rates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                <ul>
                                    <li class="active">
                                        <a href="hireRates.php">Generate/Send Labour Hire Rates </a>
                                    </li>
                                </ul>
                            </li>

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
                            <!--
                                <li>
                                    <a href="jobOrder.php"><i class="fa fa-lg fa-fw fa-hand-o-right"></i>Job Order</a>
                                </li>
                            -->
                            <li>
                                <a href="sickReport.php"><i class="fa fa-lg fa-fw fa-hospital-o"></i>Sick Report</a>
                            </li>
                            <li>
                                <a href="covidDeclarationReport.php"><i class="fa fa-lg fa-fw fa-hospital-o"></i>COVID19 Health Declaration Report</a>
                            </li>
                        </ul>
                    </li>
                    <li class="active">
                        <a href="scheduleRelease.php">
                            <div><img src="img/shift_release.png" width="30" alt="" style="color: white; margin-left: 25px;"></div>
                            <span>Shift Release</span>

                        </a>
                    </li>
                    <li class="active">
                        <a href="#"><i class="fa fa-lg fa-fw fa-money"><i class="fa fa-lg fa-fw fa-exchange"></i></i><span>Payroll</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            <ul>
                                <?php if($_SESSION['userType']!='CONSULTANT') { ?>
                                <li class="active">
                                    <a href="transactionCode.php">Transaction Code Details</a>
                                </li>
                                <li class="active">
                                    <a href="profitCentre.php">Profit Centre</a>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Tax</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="taxFormulaCodes.php">Tax Formula Codes</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Pay</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="companyBankAccount.php">Company Bank Account</a>
                                        </li>
                                        <li class="active">
                                            <a href="corporateAccount.php">Corporate Bank Account</a>
                                        </li>
                                        <li class="active">
                                            <a href="createPayCategory.php">Create PayCategory</a>
                                        </li>
                                        <li class="active">
                                            <a href="rateImport.php">Import Rate Card</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Timesheet</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <?php if($_SESSION['userType']!='CONSULTANT') { ?>
                                        <li class="active">
                                            <a href="timesheetDetails.php">Timesheet Details</a>
                                        </li>
                                        <li class="active">
                                            <a href="timeclockTransfer.php">Timeclock Data Transfer</a>
                                        </li>
                                        <?php } ?>
                                        <li class="active">
                                            <a href="payroll_check.php">Payroll Check</a>
                                        </li>
                                        <?php if($_SESSION['userType']!='CONSULTANT') { ?>
                                        <li class="active">
                                            <a href="timesheet.php">TimeSheet Calculation</a>
                                        </li>
                                        <li class="active">
                                            <a href="manualTimesheetTotals.php">Add Manual Timesheet Totals</a>
                                        </li>
                                        <li class="active">
                                            <a href="manualTimesheetImport.php">Manual Timesheet Totals Import</a>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php if($_SESSION['userType']!='CONSULTANT') { ?>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Payroll Amendments</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="manualPayroll.php">Manual Payroll Entry</a>
                                        </li>
                                    </ul>
                                </li>
                                    <li class="active">
                                        <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Payroll Finalisation</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                        <ul>
                                            <li class="active">
                                                <a href="timesheetAuditReport.php">TimeSheet Audit Report</a>
                                            </li>
                                            <li class="active">
                                                <a href="payrollCalculation.php">Payroll Calculation Report</a>
                                            </li>
                                            <li class="active">
                                                <a href="makePayment.php">Make Payment</a>
                                            </li>
                                            <li class="active">
                                                <a href="paySlip.php">PaySlip</a>
                                                <ul>
                                                    <li>
                                                        <a href="payrollDetail.php">Payroll Details</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="active">
                                        <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Single Touch</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                        <ul>
                                            <li class="active">
                                                <a href="superCalculator.php">Calculate Super & Save</a>
                                            </li>
                                            <li class="active">
                                                <a href="singletouchFirst.php">Single Touch Initial CSV</a>
                                            </li>
                                            <li class="active">
                                                <a href="singletouchRegularReport.php">Single Touch Regular CSV</a>
                                            </li>
                                            <li class="active">
                                                <a href="singletouchPhase2Report.php">Single Touch Phase 2 CSV</a>
                                            </li>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>
                    </li>
                    <?php if($_SESSION['userType']!='CONSULTANT') { ?>
                        <li class="active">
                            <a href="#"><i class="fa fa-lg fa-fw fa-files-o"></i> <span>Invoice</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            <ul>
                                <li class="active">
                                    <a href="invoiceAdditions.php">Invoice Additions</a>
                                </li>
                                <li class="active">
                                    <a href="allInvoiceCreation.php">All Invoice Creation</a>
                                </li>
                                <li class="active">
                                    <a href="invoiceReport.php">Invoice Report</a>
                                </li>
                                <li class="active">
                                    <a href="myob.php">MYOB CSV Generation</a>
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
                                            <a href="timesheetReport.php">Timesheet Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="timesheetHoursReport.php">Timesheet Hours Report</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Payroll Reports</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="candidateBalance.php">Candidate Balance Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="candidateReport.php">Candidate List Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="superCalculationReport.php">Super Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="timeclockReport.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Time Clock Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="pendingSupervisorApprovals.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Pending Supervisor Approval Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="paygSummary.php">PAYG Summary Generation</a>
                                        </li>
                                        <li class="active">
                                            <a href="transactionList.php">Transaction List Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="paysheetReport.php">Paysheet Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="payrollReport.php">Payroll Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="tempVisaReport.php">Visa Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="auditCheckReport.php">Audit Check Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="clientSummary.php">Client Summary Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="policeCheckReport.php">Police Check Deduction Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="wageSubsidyReport.php">Wage Subsidy Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="staffLevelCheck.php">Staff Level Check Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="transactionCodeReport.php">Attached Transaction Code Report</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="ohs_report.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>OHS Report</a>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>
                    <li class="active">
                            <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Consultant Reports</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            <ul>
                                <li class="active">
                                    <a href="candidateReport.php">Candidate List Report</a>
                                </li>
                                <li class="active">
                                    <a href="registeredCasuals.php">Registered Casuals View</a>
                                </li>
                                <li class="active">
                                    <a href="resumeScreeningReport.php">Resume Screening Report/View</a>
                                </li>
                                <li class="active">
                                    <a href="shiftCountReport.php"> Shift Count Report</a>
                                </li>
                                <li class="active">
                                    <a href="auditCheckReport.php">Audit Check Report</a>
                                </li>
                                <li class="active">
                                    <a href="rateCardView.php">Rate Card View</a>
                                </li>
                                <li class="active">
                                    <a href="paysheetReport.php">Paysheet Report</a>
                                </li>
                                <li class="active">
                                    <a href="timeclockReport.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Time Clock Report</a>
                                </li>
                                <li class="active">
                                    <a href="customerSurveyReport.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Customer Survey Details</a>
                                </li>
                                <li class="active">
                                    <a href="casualAvailabilityReport.php"> Casual Availability Report</a>
                                </li>
                                <li class="active">
                                    <a href="smsCostReport.php"> SMS Cost Report</a>
                                </li>
                            </ul>
                        </li>

                    <?php
                    if($_SESSION['userType']=='ADMIN'){  ?>
                        <li class="active">
                            <a href="adminDashboard.php"><i class="fa fa-lg fa-fw fa-lock"></i><span>Admin</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            <ul>
                                <li class="active">
                                    <a href="adminDashboard.php">User Management</a>
                                </li>
                                <li class="active">
                                    <a href="manageAttributes.php">Add/Edit Attributes</a>
                                </li>
                                <!--
                                    <li class="active">
                                        <a href="intvwMail.php" target="_blank"><i class="fa fa-lg fa-fw fa-sticky-note"></i> <span>Interview Booking Email Content</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    </li>
                                    <li>
                                        <a href="dbCleanup.php">Mail Data Cleanup</a>
                                    </li>
                                -->
                            </ul>
                        </li>
                        <li class="active">
                            <a href="systemDashboard.php"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i><span>System Maintenance</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        </li>
                    <?php
                    }
                    ?>
				</ul>
			</nav>