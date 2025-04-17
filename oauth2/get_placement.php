<?php
session_start();
require_once("../includes/db_conn.php");
require_once("../includes/functions.php");
$refresh_token = $_SESSION['refresh_token'];
$access_token = $_SESSION['access_token'];
$base_url ='https://api.jobadder.com/v2/';
$query_url ='placements/'.$_REQUEST['placement_id'];
/*error_reporting(E_ALL);
ini_set('display_errors', true);*/

try {
    $response_data = jobAdderConnect($access_token, $base_url, $query_url);
    //var_dump($response_data);
    echo 'Placement ID : '.$response_data->placementId.'<br>';
    echo '<hr>';
    /* Candidate Details */
    echo '<h3>Candidate Details</h3><br>';
    echo $response_data->candidate->firstName.'<br>';
    echo $response_data->candidate->lastName.'<br>';
    echo $response_data->candidate->mobile.'<br>';
    echo '<hr>';
    /* Job Details */
    echo '<h3>Job Details</h3><br>';
    echo $response_data->company->name.'<br>';
    echo $response_data->jobTitle.'<br>';

    echo '<h4>Workplace address</h4><br>';
    echo $response_data->workplaceAddress->addressId.'<br>';
    echo $response_data->workplaceAddress->name.'<br>';
    echo $response_data->workplaceAddress->street[0].'<br>';
    echo $response_data->workplaceAddress->city.'<br>';
    echo $response_data->workplaceAddress->state.'<br>';
    echo $response_data->workplaceAddress->postalCode.'<br>';
    echo $response_data->workplaceAddress->country.'<br>';
    echo '<h4>Supervisor/Approvers info</h4><br>';
    echo $response_data->export->approvers[0]->firstName.'<br>';
    echo $response_data->export->approvers[0]->lastName.'<br>';
    echo $response_data->export->approvers[0]->email.'<br>';
    echo '<hr>';
    /* Placement Period */
    echo '<h3>Placement Period</h3><br>';
    echo $response_data->type.'<br>';
    echo $response_data->startDate.'<br>';
    echo $response_data->endDate.'<br>';
    echo '<hr>';
    /* Payment Details */
    echo '<h3>Payment Details</h3><br>';
    echo 'Pay rate '.$response_data->contractRate->candidateRate.'<br>';
    echo 'Charge rate '.$response_data->contractRate->clientRate.'<br>';
    echo $response_data->contractRate->netMargin.'<br>';
    echo $response_data->award.'<br>';
    echo '<hr>';
    /* billing Details */
    echo '<h3>Billing Details</h3><br>';
    echo $response_data->billing->contact->firstName.'<br>';
    echo $response_data->billing->contact->lastName.'<br>';
    echo $response_data->billing->contact->email.'<br>';
    echo '<h4>Billing address info</h4><br>';
    echo $response_data->billing->address->addressId.'<br>';
    echo $response_data->billing->address->name.'<br>';
    echo $response_data->billing->address->street[0].'<br>';
    echo $response_data->billing->address->city.'<br>';
    echo $response_data->billing->address->state.'<br>';
    echo $response_data->billing->address->postalCode.'<br>';
    echo $response_data->billing->address->country.'<br>';
    echo $response_data->billing->terms.'<br>';


}catch(Exception $e){
    echo $e->getMessage();
}