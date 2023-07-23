<?php
include_once __DIR__."/../../config/application.php";
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include_once __DIR__."/../../assets/styles.php"; ?>
    <title>
        <?=$title??"Dashboard"?> - <?= @$app_name ?>
    </title>
</head>

<body>
    <div class="dash">
        <?php include_once __DIR__."/../../pages/_partials/sidebar.php"; ?>
        <div class="dash-app">
            <?php include_once __DIR__."/../../pages/_partials/header.php"; ?>
            <?php include_once __DIR__."/../../library/session_message.php"; ?>

            <main class="dash-content">