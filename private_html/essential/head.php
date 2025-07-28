<html lang="en">

<head>
    <title>
        <?php
        $pageTitle = isset($_GET['page']) ?
            "$pageTitle → Page #" . $_GET['page'] :
            $pageTitle;
        echo "$pageTitle\n";
        ?>
    </title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="title" property="og:title" content="<?= $pageTitle ?>" />
    <meta name="description" property="og:description" content="<?= $pageDesc ?>" />
    <meta name="url" property="og:url" content="https://cheryl-bot.ca/" />
    <meta name="image" property="og:image" content="https://cheryl-bot.ca/assets/images/cheryl/cheryl.jpg" />
    <meta name="theme-color" property="og:theme-color" content="#e99a74" data-react-helmet="true" />
    <link rel="icon" href="/assets/images/cheryl/favicon.png" type="image/png">
    <link rel="stylesheet" href="/assets/css/essential/head.css<?= $version ?>">
    <?php
    $legal = [
        '/privacy',
        '/guidelines'
    ];

    if (in_array($requestedUrl, $legal)) $requestedUrl = 'legal';

    if ($error === true) {
        echo '<link rel="stylesheet" href="/assets/css/error.css' . $version . '"' . "\n";
    } else {
        if (file_exists("../public_html/assets/css/$requestedUrl.css")) {
            echo '<link rel="stylesheet" href="/assets/css/' . $requestedUrl . '.css' . $version . '">' . "\n";
        }

        if (file_exists("../public_html/assets/js/$requestedUrl.js")) {
            echo '<script defer src="/assets/js/' . $requestedUrl . '.js' . $version . '"></script>' . "\n";
        }
    }
    ?>
</head>