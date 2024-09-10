<?php
// Check if user is logged in
if (!array_key_exists('userId', $_SESSION)) {
    header('Location: /');
}

$userId = $_SESSION['userId'];

// Check if the guild exist in the database
$guildFind = DB->prepare("SELECT guildName, guildId, guildIcon FROM guilds WHERE guildId=:guildId");
$guildFind->execute(
    [
        ':guildId' => $_GET['id']
    ]
);
$guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

// Return to the server list if the guild doesn't exist
if (!$guildFindResult) {
    header('Location: /dashboard/servers');
}

$guildName = $guildFindResult['guildName'];
$guildId = $guildFindResult['guildId'];
$guildIcon = $guildFindResult['guildIcon'];

// Check if the user requesting has permission to edit the guild
$guildPermission = DB->prepare("SELECT userId, guildId FROM permissionsuserguilds WHERE guildId=:guildId and userId=:userId");
$guildPermission->execute(
    [
        ':guildId' => $_GET['id'],
        ':userId' => $userId,
    ]
);
$guildPermissionResult = $guildPermission->fetch(PDO::FETCH_ASSOC);

// Return to the server list if he doesn't have access to edit the server
if (!$guildPermissionResult) {
    header('Location: /dashboard/servers');
}

function fetchLogging()
{
    $guildLogging = DB->prepare("SELECT `language` FROM loggings WHERE guildId=:guildId");
    $guildLogging->execute(
        [
            ':guildId' => $_GET['id']
        ]
    );
    $guildLoggingResult = $guildLogging->fetch(PDO::FETCH_ASSOC);

    return $guildLoggingResult;
}

// Fetch the logging database
$guildLoggingResult = fetchLogging();

// If guild can't be find in the logging database
if (!$guildLoggingResult) {
    $guildLoggingCreate = DB->prepare("INSERT INTO loggings (guildId) VALUES (:guildId)");
    $guildLoggingCreate->execute(
        [
            ':guildId' => $_GET['id']
        ]
    );

    $guildLoggingResult = fetchLogging();
}

$currentLanguage = $guildLoggingResult['language'];

// Switch the way the response is shown to the user
switch ($currentLanguage) {
    case 'fr':
        $currentLanguage = 'français';
        break;
    default:
        $currentLanguage = 'english';
        break;
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <title>
        Cheryl - Editing <?= $guildName ?>
    </title>
    <meta content="Cheryl - Editing <?= $guildName ?>" property="og:title" />
    <meta content="Edit <?= $guildName ?> guild settings." property="og:description" />
    <?php
    require '../private_html/all.php';
    ?>
</head>

<body>
    <?php
    require '../private_html/assets/php/nav-bar.php';
    ?>
    <main>
        <h1 class="windowInfo">
            Editing &thinsp;<span style="color: #f1c40f"><?= $guildName ?></span>'s server
        </h1>

        <nav class="filterSettings">
            <input type="button" value="Bot Settings" onclick="loadOption(event)">
            <input type="button" value="Admin" onclick="loadOption(event)">
            <input type="button" value="Moderation" onclick="loadOption(event)">
            <input type="button" value="Fun" onclick="loadOption(event)">
            <input type="button" value="Utilites" onclick="loadOption(event)">
        </nav>

        <div class="settings">
            <div id="settings_bot" class="settings" style="display: none;">
                <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">
                    <div class="settingsBot_language">
                        The current language of the bot is
                        <span class="currentValue" style="color: rgb(0, 199, 0)"><b><?= $currentLanguage ?></b></span>. You want to
                        <input type="button" class="optionStart" id="settingsOption_select" name="language" value="change language?" onclick="openMenuToggle()">
                        <div id="settingsBotLanguage_menu" style="display: none">
                            <input type="submit" id="optionLanguage" name="language" value="English">
                            <input type="submit" class="optionEnd" id="optionLanguage" name="language" value="Français">
                        </div>
                    </div>
                </form>
            </div>
            <div id="settings_admin" class="settings" style="display: none;">
                <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">

                </form>
            </div>
            <div id="settings_mod" class="settings" style="display: none;">
                <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">

                </form>
            </div>
            <div id="settings_fun" class="settings" style="display: none;">
                <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">

                </form>
            </div>
            <div id="settings_util" class="settings" style="display: none;">
                <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">

                </form>
            </div>
        </div>
        <?php
        require('../private_html/assets/php/bottom.php');
        ?>
    </main>
</body>

</html>