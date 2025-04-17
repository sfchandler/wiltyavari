<?php
require_once("includes/db_conn.php");
require_once("includes/functions.php");
if($_REQUEST['dropSelect']=='N'){
    echo getCandidatePositionListDefault($mysqli);
}else {
    echo getCandidatePositionList($mysqli);
}
?>