<?php
$file = $_SERVER['REQUEST_URI'];
$file == '/' ?
    $file = '/home' :
    $file;

$interg = strpos($file, '?');
$interg ?
    $repInter = substr($file, 0, $interg) :
    $repInter = $file;

$legal = [
    '/privacy',
    '/tos',
    '/guidelines'
];

if (in_array($repInter, $legal)) $repInter = '/legal';
$version = '?v=1.1';

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" property="og:title" content="<?= $pageTitle ?>" />
    <meta name="description" property="og:description" content="<?= $pageDesc ?>" />
    <meta name="url" property="og:url" content="https://cheryl-bot.ca/" />
    <meta name="image" property="og:image" content="https://cheryl-bot.ca/assets/images/logo/favicon.png" />
    <meta name="theme-color" property="og:theme-color" content="#e99a74" data-react-helmet="true" />
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="/assets/css/all.css<?= $version ?>">
</head>