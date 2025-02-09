<?php
if (isset($guildMatches[3])) {
    $guild = DB->prepare("SELECT * FROM guilds WHERE id=?");
    $guild->execute([
        $guildMatches[3]
    ]);
    $guildFind = $guild->fetch(PDO::FETCH_ASSOC);
    $pageTitle = ucfirst(substr($guildMatches[2], 1)) . ' - ' . $guildFind['name'];
    $file = $guildMatches[2] === '/leaderboard' ?
        $guildMatches[2] :
        $guildMatches[1];
} else {
    $file = explode('/', $requestedUrl);
    $file = empty($requestedUrl[1]) ?
        'home' :
        $file[1];
    $pageTitle = ucfirst($file);
    $file = "/$file";
}

$legal = [
    '/privacy',
    '/tos',
    '/guidelines'
];

if (in_array($file, $legal)) $file = '/legal';
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
    <meta name="image" property="og:image" content="https://cheryl-bot.ca/assets/images/logo/cheryl.png" />
    <meta name="theme-color" property="og:theme-color" content="#e99a74" data-react-helmet="true" />
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/png">
    <link rel="stylesheet" href="/assets/css/all.css<?= $version ?>">
</head>