<!DOCTYPE html>

<?php
// Check if user is logged in
array_key_exists('userId', $_SESSION) ?
    $userId = $_SESSION['userId'] :
    header('Location: /');

//
// Check if the guild exist in the database
//
$guildFind = DB->prepare("SELECT guildName, guildId, guildIcon FROM guilds WHERE guildId=:guildId");
$guildFind->execute(
    [
        ':guildId' => $_GET['id']
    ]
);
$guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);
// Return to the server list if the guild doesn't exist
if (!$guildFindResult) header('Location: /dashboard/servers');

$guildName = $guildFindResult['guildName'];
$guildId = $guildFindResult['guildId'];
$guildIcon = $guildFindResult['guildIcon'];

//
// Check if the user requesting has permission to edit the guild
//
$guildPermission = DB->prepare("SELECT userId, guildId FROM permissionsuserguilds WHERE guildId=:guildId and userId=:userId");
$guildPermission->execute(
    [
        ':guildId' => $_GET['id'],
        ':userId' => $userId,
    ]
);
$guildPermissionResult = $guildPermission->fetch(PDO::FETCH_ASSOC);

// Return to the server list if he doesn't have access to edit the server
if (!$guildPermissionResult) header('Location: /dashboard/servers');

$guildLogging = DB->prepare("SELECT `language` FROM loggings WHERE guildId=:guildId");
$guildLogging->execute(
    [
        ':guildId' => $_GET['id'],
    ]
);
$guildLoggingResult = $guildLogging->fetch(PDO::FETCH_ASSOC);

// If guild can't be find in the logging database
if (!$guildLoggingResult) {
    $guildLoggingCreate = DB->prepare("INSERT INTO loggings (guildId) VALUES (:guildId)");
    $guildLoggingCreate->execute(
        [
            ':guildId' => $_GET['id']
        ]
    );
}

$language = $guildLoggingResult['language'];

$colorOff = 'red';
$translateToOff = '0px';
$colorOn = 'rgb(0, 208, 0)';
$translateToOn = '25px';

$pageDesc = "Editing $guildName settings.";

require '../private_html/all.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main id="page" onscroll="scrollAlert()">
        <h1>
            Editing <?= $guildName ?>
        </h1>
        <div class="page">
            <nav class="filterSettings">
                <div class="filterSettings_list">
                    <input type="button" id="button_staff" value="Bot Settings" onclick="loadOption(event)">
                    <input type="button" id="button_admin" value="Admin" onclick="loadOption(event)">
                    <input type="button" id="button_mod" value="Moderation" onclick="loadOption(event)">
                    <input type="button" id="button_fun" value="Fun" onclick="loadOption(event)">
                    <input type="button" id="button_util" value="Utilites" onclick="loadOption(event)">
                </div>
            </nav>
            <div id="listSetting">
                <h2 id="noSettingSelected">
                    Select a setting to modify
                </h2>
                <div class="setting_Category" id="settingType_Bot" style="display: none;">
                    <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">
                        <?php
                        $listLanguage = [
                            'Français',
                            'English'
                        ];

                        switch ($language) {
                            case 'fr':
                                $language = "Français";
                                break;
                            default:
                                $language = "English";
                                break;
                        }
                        ?>
                        <div class="setting_Command" onclick="toggleMenu(event)">
                            <h3>
                                Language
                            </h3>
                            <h4 id="settingCommand_Current">
                                <?= $language ?>
                            </h4>
                            <p>
                                Change the language of the bot on this server.
                            </p>
                            <div id="settingCommand_Menu" style="display: none;">
                                <h4>
                                    Select a new language
                                </h4>
                                <?php
                                foreach ($listLanguage as $language) {
                                ?>
                                    <input type="submit" name="language" value="<?= $language ?>">
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="setting_Category" id="settingType_Admin" style="display: none;">
                    <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">

                    </form>
                </div>
                <div class="setting_Category" id="settingType_Mod" style="display: none;">
                    <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">

                    </form>
                </div>
                <div class="setting_Category" id="settingType_Fun" style="display: none;">
                    <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">
                        <?php
                        $loggingFind = DB->prepare("SELECT * FROM loggings WHERE guildId=:guildId");
                        $loggingFind->execute(
                            [
                                ':guildId' => $_GET['id'],
                            ]
                        );
                        $loggingFindResult = $loggingFind->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <p>
                            Action
                        </p>
                        <div class="settingCommand_Category">
                            <div class="setting_Command">
                                <h3>
                                    Enable
                                </h3>
                                <div id="enable_switch">
                                    <?php
                                    if ($loggingFindResult['action_status'] > 0) {
                                    ?>
                                        <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="action_status" id="toggle" value="" onclick="switchToggle(event)">
                                    <?php
                                    } else {
                                    ?>
                                        <input type="submit" style="transform: translate(<?= $translateToOff ?>); background: <?= $colorOff ?>" name="action_status" id="toggle" value="" onclick="switchToggle(event)">
                                    <?php
                                    }
                                    ?>
                                </div>
                                <p>
                                    Enable/Disable the action command to be used in this server.
                                </p>
                            </div>
                            <div class="setting_Command">
                                <h3>
                                    NSFW
                                </h3>
                                <div id="enable_switch">
                                    <?php
                                    if ($loggingFindResult['action_nsfw'] == 1) {
                                    ?>
                                        <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="action_nsfw" value="" onclick="switchToggle(event)">
                                    <?php
                                    } else {
                                    ?>
                                        <input type="submit" style="transform: translate(<?= $translateToOff ?>); background: <?= $colorOff ?>" name="action_nsfw" value="" onclick="switchToggle(event)">
                                    <?php
                                    }
                                    ?>
                                </div>
                                <p>
                                    Enable/Disable the NSFW action to be used.
                                </p>
                            </div>
                            <div class="setting_Command">
                                <h3>
                                    Random Message
                                </h3>
                                <div id="enable_switch">
                                    <?php
                                    if ($loggingFindResult['action_status'] == 1 || $loggingFindResult['action_status'] == 3) {
                                    ?>
                                        <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="action_status" id="message" value="" onclick="switchToggle(event)">
                                    <?php
                                    } else {
                                    ?>
                                        <input type="submit" style="transform: translate(<?= $translateToOff ?>); background: <?= $colorOff ?>" name="action_status" id="message" value="" onclick="switchToggle(event)">
                                    <?php
                                    }
                                    ?>
                                </div>
                                <p>
                                    Enable/Disable the random message when the action command is executed.
                                </p>
                            </div>
                            <div class="setting_Command">
                                <h3>
                                    Random Image
                                </h3>
                                <div id="enable_switch">
                                    <?php
                                    if ($loggingFindResult['action_status'] >= 2) {
                                    ?>
                                        <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="action_status" id="image" value="" onclick="switchToggle(event)">
                                    <?php
                                    } else {
                                    ?>
                                        <input type="submit" style="transform: translate(<?= $translateToOff ?>); background: <?= $colorOff ?>" name="action_status" id="image" value="" onclick="switchToggle(event)">
                                    <?php
                                    }
                                    ?>
                                </div>
                                <p>
                                    Enable/Disable the random image when the action command is executed.
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="setting_Category" id="settingType_Util" style="display: none;">
                    <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">

                    </form>
                </div>
            </div>
        </div>
        <?php
        require '../private_html/essential/footer.php';
        ?>
    </main>
</body>

</html>