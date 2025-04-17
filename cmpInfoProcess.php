<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 24/08/2018
 * Time: 4:14 PM
 */
require_once("includes/db_conn.php");
require_once("includes/functions.php");

$companyName = $_POST['companyName'];
$abn =  $_POST['abn'];
$acn = $_POST['acn'];
$companyAddress =  $_POST['companyAddress'];
$phone =  $_POST['phone'];
$fax =  $_POST['fax'];
$companyDesc =  $_POST['companyDesc'];
$website =  $_POST['website'];
$remittanceEmail =  $_POST['remittanceEmail'];
$companyNote =  $_POST['companyNote'];
$action =  $_POST['action'];
$companyId = $_POST['companyId'];
$logoPath = $_POST['logoPath'];
switch ($action){
    case "SAVE":
        echo saveCompanyInformation($mysqli,$companyName,$abn,$acn,$companyAddress,$phone,$fax,$companyDesc,$website,$remittanceEmail,$companyNote);
        break;
    case "GETINFO":
        $infoArray = getCompanyInfo($mysqli);
        $row = '';
        foreach($infoArray as $info){
            $row = $row.'<tr><td>'.$info['companyId'].'</td><td>'.$info['companyName'].'</td><td><img src="'.$info['companyLogoPath'].'" width="200" height="40"/></td><td data-companyid="'.$info['companyId'].'" data-logopath="'.$info['companyLogoPath'].'"><button name="editBtn" id="editBtn" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i></button><button name="removeBtn" id="removeBtn" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button></td></tr>';
        }
        echo $row;
        break;
    case 'REMOVE':
        if(removeCompany($mysqli,$companyId,$logoPath)){
            echo 'SUCCESS';
        }else{
            echo 'FAIL';
        }
        break;
    case 'EDIT':
        echo json_encode(getCompanyInfoById($mysqli,$companyId));
        break;
    case 'UPDATE':
        echo updateCompanyInformation($mysqli,$companyId,$companyName,$abn,$acn,$companyAddress,$phone,$fax,$companyDesc,$website,$remittanceEmail,$companyNote);
        break;
    case 'LOGO':
        echo '<img src="'.getCompanyLogoById($mysqli,$companyId).'" width="150" height="50"/>';
        break;
}