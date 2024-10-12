<?php
$file = $_SERVER['REQUEST_URI'];
$file == '/' ?
    $file = '/home' :
    $file;

$interg = strpos($file, '?');
$interg ?
    $repInter = substr($file, 0, $interg) :
    $repInter = $file;

// Create the page title by making seperating it every slash
$titleExplode = explode('/', $repInter);
$pageTitle = ucfirst(end($titleExplode)); // Capitalize the first letter

if ($pageTitle == '') $pageTitle = 'Home';

?>

<html lang="en">

<head>
    <title>
        <?= $pageTitle ?>
    </title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?= $pageTitle ?>" property="og:title" />
    <meta content="<?= $pageDesc ?>" property="og:description" />
    <meta content="https://cheryl-bot.ca/" property="og:url" />
    <meta content="https://cheryl-bot.ca/assets/images/all/favicon.png" property="og:image" />
    <meta content="#e99a74" data-react-helmet="true" name="theme-color" />
    <link rel="icon" href="/assets/images/all/favicon.png" type="image/png">
    <link rel="stylesheet" href="/assets/css/all.css">
    <link rel="stylesheet" href="/assets/css<?= $repInter ?>.css">
    <script defer src="/assets/js/all.js"></script>
    <script defer src="/assets/js<?= $repInter ?>.js"></script>
</head>