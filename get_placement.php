<?php
session_start();
require_once("./includes/db_conn.php");
require_once("./includes/functions.php");
if ($_SESSION['userSession'] == '') {
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<!-- job adder page -->
<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta name="csrf-token" content="<?php echo $_SESSION['csrf_token']; ?>">
    <?php include "template/header.php"; ?>
    <style>
        body{
            font-size: 10pt;
        }
    </style>
</head>
<body>
<header id="header">
    <?php include "template/top_menu.php"; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11/font/bootstrap-icons.css">
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
<!-- END NAVIGATION -->
<!-- MAIN PANEL -->
<div id="main" role="main">
    <div id="ribbon">
        <span class="ribbon-button-alignment">
        </span>
    </div>
    <!-- MAIN CONTENT -->
    <div id="content" class="container-body" style="margin-bottom: 50px;">
        <img src="img/jobAdder.svg" width="142" height="24" alt="" style="padding-left: 20px;"/>
        <br>
        <h3 style="padding-left: 20px;"><i class="glyphicon glyphicon-user"></i> Placement Information</h3>
        <div class="error"></div>
        <?php
        if(!empty($_SESSION['access_token'])) {
            $response_data = jobAdderConnect($_SESSION['access_token'],JA_API_URL,JA_GET_PLACEMENTS.$_REQUEST['placement_id']);
        ?>

        <div style="padding: 20px 20px 20px 20px">
                <div style="float: left;"><b>Placement ID: <?php echo $response_data->placementId; ?></b></div>

                <div style="float: left; padding-left: 20px;">

                        <input type="hidden" name="fName" id="fName" value="<?php echo $response_data->candidate->firstName; ?>"/>
                        <input type="hidden" name="lName" id="lName" value="<?php echo $response_data->candidate->lastName; ?>"/>
                        <input type="hidden" name="mobile" id="mobile" value="<?php echo $response_data->candidate->mobile; ?>"/>
                        <input type="hidden" name="email" id="email" value="<?php echo $response_data->candidate->email; ?>"/>
                        <input type="hidden" name="gender" id="gender" value="Noanswer"/>
                        <input type="hidden" name="foundhow" id="foundhow" value="JobAdder"/>
                        <input type="hidden" name="placement_id" id="placement_id" value="<?php echo $response_data->placementId; ?>"/>
                        <input type="hidden" name="candidate_id" id="candidate_id" value="<?php echo $response_data->candidate->candidateId; ?>"/>
                        <input type="hidden" name="candidate_name" id="candidate_name" value="<?php echo $response_data->candidate->firstName.' '.$response_data->candidate->lastName; ?>"/>
                        <input type="hidden" name="candidate_mobile" id="candidate_mobile" value="<?php echo $response_data->candidate->mobile; ?>"/>
                        <input type="hidden" name="candidate_email" id="candidate_email" value="<?php echo $response_data->candidate->email; ?>"/>
                        <input type="hidden" name="candidate_dob" id="candidate_dob" value="<?php echo $response_data->candidate->dateOfBirth; ?>"/>
                        <input type="hidden" name="job_detail_name" id="job_detail_name" value="<?php echo $response_data->company->name; ?>"/>
                        <input type="hidden" name="job_title" id="job_title" value="<?php echo $response_data->jobTitle; ?>"/>
                        <input type="hidden" name="work_place_address" id="work_place_address" value="<?php echo $response_data->workplaceAddress->name.'<br>'.$response_data->workplaceAddress->street[0].'<br>'.$response_data->workplaceAddress->city.'<br>'.$response_data->workplaceAddress->state.'<br>'.$response_data->workplaceAddress->postalCode.'<br>'.$response_data->workplaceAddress->country; ?>"/>
                        <input type="hidden" name="approver_name" id="approver_name" value="<?php echo $response_data->export->approvers[0]->firstName.' '.$response_data->export->approvers[0]->lastName;?>"/>
                        <input type="hidden" name="approver_email" id="approver_email" value="<?php echo $response_data->export->approvers[0]->email; ?>"/>
                        <input type="hidden" name="placement_period_type" id="placement_period_type" value="<?php echo  $response_data->type; ?>"/>
                        <input type="hidden" name="placement_period_start_date" id="placement_period_start_date" value="<?php echo $response_data->startDate; ?>"/>
                        <input type="hidden" name="placement_period_end_date" id="placement_period_end_date" value="<?php echo $response_data->endDate; ?>"/>
                        <input type="hidden" name="billing_name" id="billing_name" value="<?php echo $response_data->billing->contact->firstName.' '.$response_data->billing->contact->lastName; ?>"/>
                        <input type="hidden" name="billing_email" id="billing_email" value="<?php echo $response_data->billing->contact->email; ?>"/>
                        <input type="hidden" name="billing_address" id="billing_address" value="<?php echo $response_data->billing->address->name.'<br>'.$response_data->billing->address->street[0].'<br>'.$response_data->billing->address->city.'<br>'.$response_data->billing->address->state.'<br>'.$response_data->billing->address->postalCode.'<br>'.$response_data->billing->address->country; ?>"/>
                        <input type="hidden" name="billing_terms" id="billing_terms" value="<?php echo $response_data->billing->terms; ?>"/>
                        <input type="hidden" name="pay_rate" id="pay_rate" value="<?php echo $response_data->contractRate->candidateRate; ?>"/>
                        <input type="hidden" name="charge_rate" id="charge_rate" value="<?php echo $response_data->contractRate->clientRate; ?>"/>
                        <input type="hidden" name="net_margin" id="net_margin" value="<?php echo $response_data->contractRate->netMargin; ?>"/>
                        <input type="hidden" name="award" id="award" value="<?php echo $response_data->award; ?>"/>
                        <input type="submit" name="createProfileBtn" id="createProfileBtn" class="btn btn-sm btn-info" value="CREATE PROFILE"/>

                </div>
        </div>
        <div style="clear: both"></div>
        <br>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-users"></i>&nbsp;Candidate Details</div>
                        <div class="panel-body">
                            <ul>
                                <li>Name: <?php echo $response_data->candidate->firstName.' '.$response_data->candidate->lastName; ?></li>
                                <li>Mobile: <?php echo $response_data->candidate->mobile; ?></li>
                                <li>Email: <?php echo $response_data->candidate->email; ?></li>
                                <li>DateOfBirth: <?php echo $response_data->candidate->dateOfBirth; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-info"></i>&nbsp;Job Details</div>
                        <div class="panel-body">
                            <ul>
                                <li>Name: <?php echo $response_data->company->name; ?></li>
                                <li>Job Title: <?php echo $response_data->jobTitle; ?></li>
                                <li>Workplace Address: <?php echo $response_data->workplaceAddress->name.'<br>'.$response_data->workplaceAddress->street[0].'<br>'.$response_data->workplaceAddress->city.'<br>'.$response_data->workplaceAddress->state.'<br>'.$response_data->workplaceAddress->postalCode.'<br>'.$response_data->workplaceAddress->country; ?></li>
                            </ul>
                            <ul>
                                <b>Supervisor/Approver Info:</b>
                                <li>Name: <?php echo $response_data->export->approvers[0]->firstName.' '.$response_data->export->approvers[0]->lastName;?></li>
                                <li>Email:<?php echo $response_data->export->approvers[0]->email; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-clock-o"></i>&nbsp;Placement Period</div>
                        <div class="panel-body">
                            <ul>
                                <li>Type: <?php echo  $response_data->type; ?></li>
                                <li>Start Date: <?php echo $response_data->startDate; ?></li>
                                <li>End Date: <?php echo $response_data->endDate; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-money"></i>&nbsp;Payment Details</div>
                        <div class="panel-body">
                            <ul>
                                <li>Pay rate: <?php echo $response_data->contractRate->candidateRate; ?></li>
                                <li>Charge rate <?php echo $response_data->contractRate->clientRate; ?></li>
                                <li>NetMargin: <?php echo $response_data->contractRate->netMargin; ?></li>
                                <li>Award: <?php echo $response_data->award; ?></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"><i class="fa fa-info"></i>&nbsp;Billing Details</div>
                        <div class="panel-body">
                            <ul>
                                <li>Name: <?php echo $response_data->billing->contact->firstName.' '.$response_data->billing->contact->lastName; ?></li>
                                <li>Email: <?php echo $response_data->billing->contact->email; ?></li>
                            </ul>
                            <ul>
                                <b>Billing Address:</b>
                                <li><?php echo $response_data->billing->address->name.'<br>'.$response_data->billing->address->street[0].'<br>'.$response_data->billing->address->city.'<br>'.$response_data->billing->address->state.'<br>'.$response_data->billing->address->postalCode.'<br>'.$response_data->billing->address->country; ?></li>
                                <li>Terms: <?php echo $response_data->billing->terms; ?></li>
                            </ul>
                        </div>
                    </div>
            </div>
        </div>
            <?php
        }
        ?>
    </div>
    <!-- END MAIN CONTENT -->
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
<!-- JQUERY FORM PLUGIN -->
<script src="js/jqueryform/jquery.form.js"></script>
<script>
    $(document).ready(function () {
        $body = $("body");
        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });
        $(document).on('click', '#createProfileBtn', function (e) {
            e.preventDefault();
            let fName = $('#fName').val();
            let lName = $('#lName').val();
            let mobile = $('#mobile').val();
            let email = $('#email').val();
            let gender = $('#gender').val();
            let foundhow = $('#foundhow').val();
            let consultantId = 1;
            let action = 'JobAdder';
            let placement_id = $('#placement_id').val();
            let candidate_id = $('#candidate_id').val();
            let candidate_name = $('#candidate_name').val();
            let candidate_mobile = $('#candidate_mobile').val();
            let candidate_email = $('#candidate_email').val();
            let candidate_dob = $('#candidate_dob').val();
            let job_detail_name = $('#job_detail_name').val();
            let job_title = $('#job_title').val();
            let work_place_address = $('#work_place_address').val();
            let approver_name = $('#approver_name').val();
            let approver_email = $('#approver_email').val();
            let placement_period_type = $('#placement_period_type').val();
            let placement_period_start_date = $('#placement_period_start_date').val();
            let placement_period_end_date = $('#placement_period_end_date').val();
            let billing_name = $('#billing_name').val();
            let billing_email = $('#billing_email').val();
            let billing_address = $('#billing_address').val();
            let billing_terms = $('#billing_terms').val();
            let pay_rate = $('#pay_rate').val();
            let charge_rate = $('#charge_rate').val();
            let net_margin = $('#net_margin').val();
            let award = $('#award').val();

            $.ajax({
                type: "POST",
                url: "./saveCandidate.php",
                data: {
                    fName: fName,
                    lName: lName,
                    mobile:mobile,
                    email:email,
                    gender:gender,
                    foundhow:foundhow,
                    consultantId:consultantId,
                    action:action,
                    placement_id:placement_id,
                    candidate_id:candidate_id,
                    candidate_name:candidate_name,
                    candidate_mobile:candidate_mobile,
                    candidate_email:candidate_email,
                    candidate_dob:candidate_dob,
                    job_detail_name:job_detail_name,
                    job_title:job_title,
                    work_place_address:work_place_address,
                    approver_name:approver_name,
                    approver_email:approver_email,
                    placement_period_type:placement_period_type,
                    placement_period_start_date:placement_period_start_date,
                    placement_period_end_date:placement_period_end_date,
                    billing_name:billing_name,
                    billing_email:billing_email,
                    billing_address:billing_address,
                    billing_terms:billing_terms,
                    pay_rate:pay_rate,
                    charge_rate:charge_rate,
                    net_margin:net_margin,
                    award:award
                },
                dataType: "text",
                success: function (data) {
                    if (data == 'Updated') {
                        $('.error').html('Candidate Details Updated');
                    } else if (data == 'Inserted') {
                        $('.error').html('Candidate Created Successfully');
                    } else if (data == 'Required') {
                        $('.error').html('Please fill all the fields');
                    } else {
                        $('.error').html('Error - ' + data);
                    }
                }
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>