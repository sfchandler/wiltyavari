<?php
session_start();
require_once('includes/db_conn.php');
require_once('includes/functions.php');
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))){
    $msg = "Access Denied. Please use a computer to view this page";
    header('Location: error.php?error=$msg');
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
if(empty($_REQUEST['candidateId'])){
    $msg = "Access Denied";
    header("Location:error.php?error=$msg");
}elseif(validateCOVIDPolicyDocumentSigned($mysqli,$_REQUEST['candidateId'])){
    $msg = "COVID19 Policy Signed submitted";
    header("Location:error.php?error=$msg");
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CHANDLER COVID 19 POLICY</title>
    <script src="js/jquery/2.1.1/jquery.min.js"></script>
    <!-- this, preferably, goes inside head element: -->
    <!--[if lt IE 9]>
    <script type="text/javascript" src="js/jSignature/flashcanvas.js"></script>
    <![endif]-->
    <!-- Basic Styles -->
    <link rel="stylesheet" type="text/css" media="screen" href="css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="css/font-awesome.min.css">
    <script src="js/jSignature/jSignature.min.js"></script>
    <!-- Jquery Form Validator -->
    <script type="text/javascript" src="js/jquery.validate.js"></script>
    <script type="text/javascript" src="js/jquery.base64.js"></script>
    <!-- JQUERY FORM PLUGIN -->
    <script src="js/jqueryform/jquery.form.js"></script>
    <style>
        .error{
            color: red;
        }
        label{
            font-weight: normal;
        }
        #signature {
            border: 2px dotted black;
            background-color:lightgrey;
            color: #03038c;
        }
        body{
            background-image: url("img/subtle-stripes-pattern-2273.png");
            background-repeat: repeat;
        }
    </style>
</head>
<body>
<div style="font-family: Arial, Helvetica, sans-serif; font-size: 14px">
    <div style="padding-left: 280px; padding-top: 10px"><img src="includes/TCPDF-master/generalpdf/images/logochandler.png" width="200" alt=""></div>
    <div align="center"><h2>CHANDLER COVID 19 POLICY</h2></div>
    <div align="justify" style="width: 500px;">
    </div><br/>

    <div align="center" style="width: 980px; margin: 0 auto">
        <form action="" name="frmCovidForm" id="frmCovidForm" method="post" enctype="multipart/form-data">
        <table width="980px">
            <tbody>
            <tr>
                <td>
                    <p>It is the responsibility of <?php echo DOMAIN_NAME; ?> as an external contractor to have all personnel entering work sites to complete the below assessment prior to registration. It is a requirement that if any personnel providing the answer YES to any of the assessment questions, they are to be quarantined and provide  <?php echo DOMAIN_NAME; ?> with a medical clearance in writing.</p>
                </td>
            </tr>
            <tr>
                <td><p>Please answer the below questions truthfully</p></td>
            </tr>
            <tr>
                <td>
                    <ul>
                        <li>Have you travelled anywhere outside Australia in the last 14 days?</li>
                        <input type="radio" name="q1" value="YES">YES
                        <br>
                        <input type="radio" name="q1" value="NO">NO
                        <li>Have you transitioned through any International airports in the last 14 days?</li>
                        <input type="radio" name="q2" value="YES">YES
                        <br>
                        <input type="radio" name="q2" value="NO">NO
                        <li>Have you or an immediate family member been tested for COVID-19?</li>
                        <input type="radio" name="q3" value="YES">YES
                        <br>
                        <input type="radio" name="q3" value="NO">NO
                        <li>Do you currently have a fever, cough, sore throat, shortness of breath, runny nose, aches and pains or feel unwell?</li>
                        <input type="radio" name="q4" value="YES">YES
                        <br>
                        <input type="radio" name="q4" value="NO">NO
                        <li>Have you been in contact with someone who has returned from overseas in the past 14 days?</li>
                        <input type="radio" name="q5" value="YES">YES
                        <br>
                        <input type="radio" name="q5" value="NO">NO
                        <li>Have you been in close contact with a confirmed case in the past 14 days?</li>
                        <input type="radio" name="q6" value="YES">YES
                        <br>
                        <input type="radio" name="q6" value="NO">NO
                        <li>Declaration of not having been to any of the NSW government listed exposure sites</li>
                        <input type="radio" name="q7" value="YES">YES
                        <br>
                        <input type="radio" name="q7" value="NO">NO
                        <li>Declaration of not crossing work with other locations/employers within the last 14 days</li>
                        <input type="radio" name="q8" value="YES">YES
                        <br>
                        <input type="radio" name="q8" value="NO">NO
                    </ul>
                </td>
            </tr>
            <tr>
                <td>
                    <div style="width: 500px;">
                        <p>If you have answered YES to ANY of these questions you must notify your consultant immediately.</p>
                        <p><b>Declaration</b></p>
                        <p align="justify">I declare that the information and responses I have provided are correct and true. I have read and understand the COVID-19 fact sheet. </p>
                        <p align="justify">I understand that it is my responsibility to notify Chandler Health immediately, If I have had, or believe I may have had direct contact with a confirmed case of COVID-19</p>
                        <form name="frmCovidForm" id="frmCovidForm" method="post">
                            <label>Name: <?php echo getCandidateFullName($mysqli,$_REQUEST['candidateId']); ?></label>
                            <br>
                            <label>Signature</label>
                            <div id="signature"></div>
                            <br>
                            <input type="hidden" name="canId" id="canId" value="<?php echo $_REQUEST['candidateId']; ?>">
                            <input type="hidden" name="conEmail" id="conEmail" value="<?php echo $_REQUEST['conEmail']; ?>">
                            <input type="submit" id="submitBtn" class="submitBtn btn-success btn-lg" value="Submit"/>
                        </form>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        </form>
    </div>
</div>
<div id="imgSig" style="display: none;"></div>
<img id="dataImg" src="" style="border: 1px solid green;">
<script type="text/javascript" src="js/covidScript.js"></script>
<div class="modal"><!-- Place at bottom of page --></div>
</body>
</html>
