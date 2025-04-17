<?php
include "includes/functions.php";
$password = randomPassword(8);
$options = [
    'cost' => 12,
];
echo password_hash("abc123456",PASSWORD_BCRYPT,$options);
?>