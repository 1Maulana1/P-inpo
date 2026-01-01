<?php
$databaseHost = 'dbserver'; // nama container DB
$databaseName = 'dadakanDB';
$databaseUsername = 'appuser';
$databasePassword = 'secret123';

$mysqli = mysqli_connect(
    $databaseHost,
    $databaseUsername,
    $databasePassword,
    $databaseName
);
?>