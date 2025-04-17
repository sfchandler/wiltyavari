<?php
/**
 * Created by PhpStorm.
 * User: Swarnajith
 * Date: 18/08/2017
 * Time: 4:38 PM
 */

require_once("includes/db_conn.php");
require_once("includes/functions.php");

$taxCode = $_POST['taxCode'];
$taxCodeDesc = $_POST['taxCodeDesc'];
$response = updateTaxCode($mysqli,$taxCode,$taxCodeDesc);
if($response=='inserted'){
    for($i = 0; $i<10; $i++) {
        echo addWeeklyScaleRates($mysqli,$taxCode,$_POST['lessThan'][$i],$_POST['rate'][$i],$_POST['adj'][$i]);
    }
}else if($response == 'update' || $response == ''){
    for($j = 0; $j<10; $j++) {
       echo updateWeeklyScaleRates($mysqli,$taxCode,$_POST['lessThan'][$j],$_POST['rate'][$j],$_POST['adj'][$j]);
    }
}
//echo $_POST['taxCode'].$_POST['taxCodeDesc'].$_POST['lessThan1'].$_POST['rate1'].$_POST['adj1'].'<br>'.$_POST['lessThan2'].$_POST['rate2'].$_POST['adj2'].'<br>'.$_POST['lessThan3'].$_POST['rate3'].$_POST['adj3'].'<br>'.$_POST['lessThan4'].$_POST['rate4'].$_POST['adj4'].'<br>'.$_POST['lessThan5'].$_POST['rate5'].$_POST['adj5'].'<br>'.$_POST['lessThan6'].$_POST['rate6'].$_POST['adj6'].'<br>'.$_POST['lessThan7'].$_POST['rate7'].$_POST['adj7'].'<br>'.$_POST['lessThan8'].$_POST['rate8'].$_POST['adj8'].'<br>'.$_POST['lessThan9'].$_POST['rate9'].$_POST['adj9'].'<br>'.$_POST['lessThan10'].$_POST['rate10'].$_POST['adj10'];
?>