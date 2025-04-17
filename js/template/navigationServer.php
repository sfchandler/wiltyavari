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
					<li class="active">
						<a href="inbox.php?default=load"><i class="fa fa-lg fa-fw fa-inbox"></i> <span class="menu-item-parent">Inbox</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <!--
                        <ul>
							<li class="">
                               <a href="candidateInfo.php" title="Dashboard"><i class="fa fa-lg fa-fw fa-file-text-o"></i> <span class="menu-item-parent">Registered Candidates</span></a>
                            </li>
                        </ul>
                        -->    	
					</li>
                    <!--<li class="active">
						<a href="dashBoard.php"><i class="fa fa-lg fa-fw fa-desktop"></i> <span class="menu-item-parent">Dashboard</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>-->
                    <li class="active">
						<a href="dashboardMain.php"><i class="fa fa-lg fa-fw fa-search"></i> <span>Search Candidates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                    </li>
                    <li class="active">
						<a href="candidateMain.php"><i class="fa fa-lg fa-fw fa-users"></i> <span>Candidates</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                        <ul>
                            <li class="active">
                                <a href="candidatePositions.php"><i class="fa fa-lg fa-fw fa-certificate"></i> Position Types</a>
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
                            <li class="active">
                                <a href="clientShiftLocation.php"><i class="fa fa-lg fa-fw fa-industry"></i> <span>Client Shift Location</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            </li>
                            <li class="active">
                                <a href="clientDepartments.php"><i class="fa fa-lg fa-fw fa-indent"></i>Client Departments</a>
                            </li>
                            <li class="active">
                                <a href="clientDocuments.php"><i class="fa fa-lg fa-fw fa-file"></i>Client Documents</a>
                            </li>
                            <li class="active">
                                <a href="supervisor.php"><i class="fa fa-lg fa-fw fa-file"></i>Add/Remove Supervisors Contact Details</a>
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
                                <a href="shiftImport.php"><i class="fa fa-lg fa-fw fa-file-excel-o"></i>Import Roster</a>
                            </li>
                        </ul>
                    </li>
                    <?php if($_SESSION['userType']=='ACCOUNTS' || $_SESSION['userType']=='ADMIN'){ ?>
                        <li class="active">
                            <a href="#"><i class="fa fa-lg fa-fw fa-money"><i class="fa fa-lg fa-fw fa-exchange"></i></i><span>Payroll</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                            <ul>
                                <li class="active">
                                    <a href="createJobCode.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>JobCode/Job Detail</a>
                                </li>
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
                                            <a href="corporateAccount.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Corporate Bank Account</a>
                                        </li>
                                        <li class="active">
                                            <a href="createPayCategory.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Create PayCategory</a>
                                        </li>
                                        <li class="active">
                                            <a href="createRateCard.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Create Rate Card</a>
                                        </li>
                                        <li class="active">
                                            <a href="payrule.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Payrule Definition</a>
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
                                        <li class="active">
                                            <a href="invoiceCreation.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Invoice Creation</a>
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
                                            <a href="timesheetCheck.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>TimeSheet Check</a>
                                        </li>
                                        <li class="active">
                                            <a href="timesheet.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>TimeSheet Calculation</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Reports</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="timesheetAuditReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>TimeSheet Audit Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="payrollCalculation.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Payroll Calculation Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="candidateBalance.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Candidate Balance Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="superCalculationReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Super Report</a>
                                        </li>
                                        <li class="active">
                                            <a href="timesheetReport.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Timesheet Hours Report</a>
                                        </li>
                                        <!--<li class="active">
                                            <a href="timeclockReport.php"><i class="fa fa-lg fa-fw fa-clock-o"></i>Time Clock Report</a>
                                        </li>-->
                                        <li class="active">
                                            <a href="paygSummary.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>PAYG Summary Generation</a>
                                        </li>
                                        <li class="active">
                                            <a href="transactionList.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Transaction List Report</a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#"><i class="fa fa-lg fa-fw fa-file"></i> <span>Single Touch</span> <span class="badge pull-right inbox-badge margin-right-13"></span></a>
                                    <ul>
                                        <li class="active">
                                            <a href="singletouch.php"><i class="fa fa-lg fa-fw fa-caret-right"></i>Single Touch CSV</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    <?php } ?>
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
                    <?php } ?>
				</ul>
			</nav>