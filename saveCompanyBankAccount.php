<?php

session_start();
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['action'] == 'ADD'){
    if(isset($_REQUEST['accountName'])&& isset($_REQUEST['accountNumber'])&& isset($_REQUEST['bsb']) && isset($_REQUEST['userName']) && isset($_REQUEST['userCode'])&&isset($_REQUEST['tradeCode'])){
        echo addCompanyBankAccount($mysqli,$_REQUEST['accountName'],$_REQUEST['accountNumber'],$_REQUEST['bsb'],$_REQUEST['userName'],$_REQUEST['userCode'],$_REQUEST['tradeCode'],$_REQUEST['companyId']);
    }
    else{
        echo 'Required';
    }
}else if($_REQUEST['action'] == 'UPDATE'){
    if(isset($_REQUEST['accountName'])&& isset($_REQUEST['accountNumber'])&& isset($_REQUEST['bsb']) && isset($_REQUEST['userName']) && isset($_REQUEST['userCode'])&&isset($_REQUEST['tradeCode'])&&isset($_REQUEST['accId'])){
        echo updateCompanyBankAccount($mysqli,$_REQUEST['accountName'],$_REQUEST['accountNumber'],$_REQUEST['bsb'],$_REQUEST['userName'],$_REQUEST['userCode'],$_REQUEST['tradeCode'],$_REQUEST['accId']);
    }
    else{
        echo 'Required';
    }
}else if($_REQUEST['action'] == 'DELETE'){
    echo deleteCompanyBankAccount($mysqli,$_REQUEST['accId']);
}
else if($_REQUEST['action'] == 'GET'){
    echo getCompanyBankAccountRows($mysqli);
}
?>