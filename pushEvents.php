<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
date_default_timezone_set('Australia/Melbourne');

$startDate = date('Y-m-d');//date('Y-m-d',strtotime('-1 days'));
$endDate = date('Y-m-d');//date('Y-m-d', strtotime('+3 days'));
if($_REQUEST['action']=='GET') {
    try {
        $events = getCalendarEvents($mysqli, $startDate);
    } catch (Exception $e) {
        $e->getMessage();
    }
    echo json_encode($events);
}elseif ($_REQUEST['action'] == 'DELETE'){
    $events = getDeleteCalendarEvents($mysqli);
    echo json_encode($events);
}elseif ($_REQUEST['action'] == 'FLUSH'){
    $id = $mysqli->real_escape_string($_REQUEST["id"]);
    flushEvents($mysqli,$id);
}
/*foreach ($events as $event) {
    echo '<li id="'.$event['id'].'" class="eventItem"><img src="img/interview.png"/><p><span class="evTitle">' . $event['title'] . '</span><br>' . $event['start'] . ' ' . $event['end'] . '</p></li>';
}*/