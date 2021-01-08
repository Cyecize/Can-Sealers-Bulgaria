<?php
$sql_conn = mysqli_connect('localhost', 'root', 'toor', 'zatvarachki2');

$query = "DELETE FROM password_recoveries  WHERE TIMESTAMPDIFF(MINUTE,time_requested,NOW()) > 30;";
mysqli_query($sql_conn, $query);
$rowsAffectedQuery = "SELECT ROW_COUNT() AS 'count'";
$res = mysqli_fetch_array(mysqli_query($sql_conn, $rowsAffectedQuery));
if($res['count'] < 1)
    exit(1);
$logQuery = "INSERT INTO logs (date, location ,message) VALUES (now(), 'Cron Job (Password Filter)', 'Just Filtered Requests')";
mysqli_query($sql_conn, $logQuery);
