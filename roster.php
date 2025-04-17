<?php
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
date_default_timezone_set('Australia/Melbourne');
session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");

if ($_SESSION['userSession'] == '' &&  $_SESSION['userType']!='CONSULTANT')
{
    $msg = base64_encode("Access Denied");
    header("Location:login.php?error_msg=$msg");
}

$empStatus = 'ACTIVE';
$clientId = $_REQUEST['clientId'];
$stateId = $_REQUEST['stateId'];
$deptId = $_REQUEST['departmentId'];
$status = 1;
$positionId = $_REQUEST['expPosition'];
$auditStatus =1;
$allQuery = $mysqli->prepare('SELECT 
                                  employee_allocation.candidateId,
                                  employee_allocation.clientId,
                                  employee_allocation.stateId,
                                  employee_allocation.deptId,
                                  employee_allocation.ohs_sent_time,
                                  employee_positions.positionid,
                                  candidate.sex,
                                  candidate.firstName,
                                  candidate.lastName,
                                  candidate.nickname,
                                  candidate.mobileNo,
                                  department.department  
                                FROM
                                  employee_allocation
                                  INNER JOIN employee_positions ON (employee_allocation.candidateId = employee_positions.candidateId)
                                  INNER JOIN candidate ON (employee_allocation.candidateId = candidate.candidateId)
                                  INNER JOIN department ON(employee_allocation.deptId = department.deptId)
                                WHERE
                                  employee_allocation.clientId = ? AND
                                  employee_allocation.stateId = ? AND
                                  employee_allocation.deptId = ? AND
                                  employee_allocation.status = ? AND
                                  employee_positions.positionid = ? AND
                                  candidate.empStatus = ? AND
                                  candidate.auditStatus = ?
                                  ORDER BY candidate.firstName ASC');
$allQuery->bind_param('iiiiisi',$clientId,$stateId,$deptId,$status,$positionId,$empStatus,$auditStatus);
$allQuery->execute();
$allQuery->bind_result($candidateId,$clientId,$stateId,$deptId,$ohs_sent_time,$positionid,$sex,$firstName,$lastName,$nickname,$mobileNo,$department) or die($mysqli->error);
$allQuery->store_result();
$allRecords = $allQuery->num_rows;

$limit = 10;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

$stmt = $mysqli->prepare('SELECT 
                                  employee_allocation.candidateId,
                                  employee_allocation.clientId,
                                  employee_allocation.stateId,
                                  employee_allocation.deptId,
                                  employee_allocation.ohs_sent_time,
                                  employee_positions.positionid,
                                  candidate.sex,
                                  candidate.firstName,
                                  candidate.lastName,
                                  candidate.nickname,
                                  candidate.mobileNo,
                                  department.department
                                FROM
                                  employee_allocation
                                  INNER JOIN employee_positions ON (employee_allocation.candidateId = employee_positions.candidateId)
                                  INNER JOIN candidate ON (employee_allocation.candidateId = candidate.candidateId)
                                  INNER JOIN department ON(employee_allocation.deptId = department.deptId)   
                                WHERE
                                  employee_allocation.clientId = ? AND
                                  employee_allocation.stateId = ? AND
                                  employee_allocation.deptId = ? AND
                                  employee_allocation.status = ? AND
                                  employee_positions.positionid = ? AND
                                  candidate.empStatus = ? AND
                                  candidate.auditStatus = ?
                                  ORDER BY candidate.firstName ASC LIMIT ?,?');
$stmt->bind_param('iiiiisiii',$clientId,$stateId,$deptId,$status,$positionId,$empStatus,$auditStatus, $start, $limit);
$stmt->execute();
$stmt->bind_result($candidateId,$clientId,$stateId,$deptId,$ohs_sent_time,$positionid,$sex,$firstName,$lastName,$nickname,$mobileNo,$department) or die($mysqli->error);
$stmt->store_result();

$total = $allRecords;
$pages = ceil( $total / $limit );

$Previous = $page - 1;
$Next = $page + 1;
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <title> <?php echo DOMAIN_NAME; ?>Admin </title>
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production-plugins.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-production.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-skins.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/smartadmin-rtl.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/chandlerStyle.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/demo.min.css">
    <link rel="shortcut icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <link rel="icon" href="img/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" media="all" href="css/daterangepicker.css" />

</head>
<body>
<header id="header">
    <div id="logo-group">
        <span id="" style="padding: 15px 10px 10px 10px;"><img src="./img/logo.png" width="220" alt=" <?php echo DOMAIN_NAME; ?> Admin"></span>
    </div>
    <div align="center" style="color:red; font-style:normal; margin-left:0%; margin-top:10px; width:200px; height:25px;">
        <?php $error_msg = false;
        if(isset($_REQUEST['error_msg'])){
            $error_msg = base64_decode($_REQUEST['error_msg']);
        }
        echo '<div class="errMsg" style="color:red; font-weight:bold; font-size:12px">'.$error_msg.'</div>';
        ?>
    </div>
    <!-- pulled right: nav area -->
    <div class="pull-right">
        <!-- clock -->
        <div id="clock" style="margin-top:12px;float:left;">
        </div>
        <div class="pull-right">
            <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="img/avatars/<?php echo getAvatarImage($mysqli,$_SESSION['userSession']);?>" alt="<?php echo $_SESSION['userSession']; ?>" class="online" style="height: 35px;"/> Welcome, <?php echo $_SESSION['userSession']; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu">
                <li class="dropdown-item"><a href="changeCredentials.php" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> Change Password</a></li>
                <li><hr class="dropdown-divider"></li>
                <li class="dropdown-item">
                    <a href="logout.php?csrf=<?php echo $_SESSION['token']; ?>" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                </li>
            </ul>
        </div>
        <div id="fullscreen" class="btn-header transparent pull-right">
            <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
        </div>
    </div>
    <?php if(basename($_SERVER['PHP_SELF']) != 'roster.php'){  } ?>
</header>
<aside id="left-panel">
    <div class="login-info">
        <?php include "template/user_info.php"; ?>
    </div>
    <?php include "template/navigation.php"; ?>
</aside>
<div id="main" role="main">
    <div id="content" class="container-fluid bg-color-white">
        <div>
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <table class="table">
                <tbody>
                  <tr>
                    <td>
                        <div id="reportrange" style="cursor:pointer;" class="form-control"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                            <span></span> <b class="caret"></b>
                        </div>
                    </td>
                    <td><select name="clientId" id="clientId"  class="form-control">
                        </select>
                    </td>
                    <td><select name="stateId" id="stateId"  class="form-control">
                        </select></td>
                    <td><select name="departmentId" id="departmentId"  class="form-control">
                        </select>
                    </td>
                    <td><select name="expPosition" id="expPosition"  class="form-control">
                          </select>
                    </td>
                    <td>
                        <button name="scheduleBtn" id="scheduleBtn" class="scheduleBtn btn btn-primary btn-square btn"><i class="fa fa-clock-o"></i>&nbsp; Schedule</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>
        </div>

        <table class="table table-striped table-bordered">
            <tr>
                <th>Client</th>
                <th>State</th>
                <th>Department</th>
            </tr>
            <?php
            while ($stmt->fetch()) {
                ?>
                <tr>
                    <td><?php echo $department.'<br>'.getCandidateFullName($mysqli,$candidateId).'('.$nickname.') <br> '.$candidateId; ?></td>
                    <td><?php echo $clientId; ?></td>
                    <td><?php echo $stateId; ?></td>
                </tr>
            <?php
            }
            $stmt->close();
            ?>
        </table>
        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <li class="page-item <?php if($page <= 1){ echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($page <= 1){ echo '#'; } else { echo "?page=" . $Previous."&clientId=".$clientId."&stateId=".$stateId."&departmentId=".$deptId."&expPosition=".$positionId; } ?>">Previous</a>
                </li>
                <?php for($i = 1; $i<= $pages; $i++) { ?>
                    <li class="page-item <?php if($page == $i) { echo 'active'; } ?>">
                        <a href="roster.php?page=<?php echo $i."&clientId=".$clientId."&stateId=".$stateId."&departmentId=".$deptId."&expPosition=".$positionId; ?>" class="page-link"><?php echo $i; ?></a>
                    </li>
                <?php } ?>
                <li class="page-item <?php if($page >= $pages) { echo 'disabled'; } ?>">
                    <a class="page-link" href="<?php if($page >= $pages){ echo '#'; } else {echo "?page=". $Next."&clientId=".$clientId."&stateId=".$stateId."&departmentId=".$deptId."&expPosition=".$positionId; } ?>">Next</a>
                </li>
            </ul>
        </nav>
    </div>
</div>
<br><br><br><br><br><br><br><br><br>
<?php include "template/footer.php"; ?>
<?php include "template/roster_scripts.php"; ?>

</body>
</html>
