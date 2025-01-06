<?php
//
// Check if user is logged in.
if (!array_key_exists('userId', $_SESSION)) {
    header('location: /');
    die;
}

$user = DB->prepare("SELECT nextRefresh, accessToken FROM users WHERE userId=?");
$user->execute([
    $_SESSION['userId']
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

//
// Request info about the user guild roles.
$url = API_ENDPOINT . 'guilds/' . $_GET['id'] . '/roles';
$request = curl_init();

curl_setopt($request, CURLOPT_URL, $url);
curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
curl_setopt($request, CURLOPT_HTTPHEADER, [
    'Authorization: Bot ' . BOT_TOKEN,
]);
$response = curl_exec($request);
$decodeResponse = json_decode($response, true);

usort($decodeResponse, fn($a, $b) => $b['position'] <=> $a['position']);

//
// Check if the guild exist in the database
$guildFind = DB->prepare("SELECT guildName, guildId, guildIcon FROM guilds WHERE guildId=?");
$guildFind->execute([
    $_GET['id']
]);
$guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

//
// Return to the server list if the guild doesn't exist
if (!$guildFindResult) {
    header('Location: /dashboard/servers');
    die;
}

$guildName = $guildFindResult['guildName'];
$guildId = $guildFindResult['guildId'];
$guildIcon = $guildFindResult['guildIcon'];

//
// Check if the user requesting has permission to edit the guild
$guildPermission = DB->prepare("SELECT userId, guildId FROM permissionsuserguilds WHERE guildId=? and userId=?");
$guildPermission->execute([
    $_GET['id'],
    $userId,
]);
$guildPermissionResult = $guildPermission->fetch(PDO::FETCH_ASSOC);

//
// Return to the server list if he doesn't have access to edit the server
if (!$guildPermissionResult) {
    header('Location: /dashboard/servers');
    die;
}

$guildLogging = DB->prepare("SELECT `language` FROM loggings WHERE guildId=?");
$guildLogging->execute([
    $_GET['id'],
]);
$guildLoggingResult = $guildLogging->fetch(PDO::FETCH_ASSOC);

//
// If guild can't be find in the logging database
if (!$guildLoggingResult) {
    $guildLoggingCreate = DB->prepare("INSERT INTO loggings (guildId) VALUES (?)");
    $guildLoggingCreate->execute([
        $_GET['id']
    ]);
}

$language = $guildLoggingResult['language'];

$colorOff = 'red';
$translateToOff = '0px';
$colorOn = 'rgb(0, 208, 0)';
$translateToOn = '25px';

$pageDesc = "Editing $guildName settings.";
?>

<!DOCTYPE html>

<?php
require '../private_html/all/all.php';
require '../private_html/all/style.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main id="page">
        <h1>
            Editing <?= $guildName ?>
        </h1>
        <div class="page">
            <nav class="filterSettings">
                <div class="filterSettings_list">
                    <input type="button" id="button_staff" value="Bot Settings" onclick="loadOption(event)">
                    <input type="button" id="button_admin" value="Admin" onclick="loadOption(event)">
                    <input type="button" id="button_fun" value="Fun" onclick="loadOption(event)">
                </div>
            </nav>
            <div id="listSetting">
                <h2 id="noSettingSelected">
                    Select a setting to modify
                </h2>
                <form method="POST" enctype="application/x-www-form-urlencoded" action="" onsubmit="postSubmit(event)">
                    <div class="setting_Category" id="settingType_Bot" style="display: none;">
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
                        <div class="command" onclick="toggleMenu(event)">
                            <div class="command_option">
                                <h3>
                                    Language
                                </h3>
                                <h4 id="settingCommand_Current">
                                    <?= $language ?>
                                </h4>
                            </div>
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
                    </div>
                    <div class="setting_Category" id="settingType_Admin" style="display: none;">
                        <h4>
                            Welcome
                        </h4>
                        <div class="settingCategory_Command">
                            <div class="command">
                                <div class="command_option">
                                    <h3>
                                        Enable
                                    </h3>
                                    <div id="enable_switch">
                                        <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="action_status" id="toggle" data="1" value="" onclick="switchToggle(event)">
                                    </div>
                                </div>
                                <p>
                                    Enable/Disable the welcome feature to be triggered on the server.
                                </p>
                            </div>
                            <div class="command">
                                <div class="command_option">
                                    <h3>
                                        Auto-Role
                                    </h3>
                                </div>
                                <div onclick="expandMenu(event)">
                                    <p>
                                        Select roles to automatically give to new members when they join the server.
                                    </p>
                                    <br><br>
                                    <p class="command_expand">
                                        Click to expand
                                    </p>
                                </div>
                                <div class="command_roles" style="display: none">
                                    <?php
                                    foreach ($decodeResponse as $key => $val) {
                                        if (isset($val['tags']['bot_id'])) continue;
                                        if ($val['position'] == 0) continue;

                                        $color = dechex($val['color']);
                                    ?>
                                        <input type="submit" name="role" style="color: #<?= $color ?>" value="<?= $val['name'] ?>" onclick="buttonSelect()">
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <h4>
                            Leaving
                        </h4>
                        <div class="settingCategory_Command">
                            <div class="command">
                                <div class="command_option">
                                    <h3>
                                        Enable
                                    </h3>
                                    <div id="enable_switch">
                                        <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="action_status" id="toggle" data="1" value="" onclick="switchToggle(event)">
                                    </div>
                                </div>
                                <p>
                                    Enable/Disable the leaving feature to be triggered on the server.
                                </p>
                            </div>
                        </div>
                        <h4>
                            Bump Reminder
                        </h4>
                        <div class="settingCategory_Command">
                            <div class="command">
                                <div class="command_option">
                                    <h3>
                                        Enable
                                    </h3>
                                    <div id="enable_switch">
                                        <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="action_status" id="toggle" data="1" value="" onclick="switchToggle(event)">
                                    </div>
                                </div>
                                <p>
                                    Enable/Disable the bump reminder.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="setting_Category" id="settingType_Fun" style="display: none;">
                        <?php
                        $loggingFind = DB->prepare("SELECT * FROM loggings WHERE guildId=?");
                        $loggingFind->execute([
                            $_GET['id'],
                        ]);
                        $loggingFindResult = $loggingFind->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <h4>
                            Action
                        </h4>
                        <div class="settingCategory_Command">
                            <div class="command">
                                <div class="command_option">
                                    <h3>
                                        Enable
                                    </h3>
                                    <div id="enable_switch">
                                        <?php
                                        if ($loggingFindResult['action_status'] > 0) {
                                        ?>
                                            <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="action_status" id="toggle" data="1" value="" onclick="switchToggle(event)">
                                        <?php
                                        } else {
                                        ?>
                                            <input type="submit" style="transform: translate(<?= $translateToOff ?>); background: <?= $colorOff ?>" name="action_status" id="toggle" data="0" value="" onclick="switchToggle(event)">
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p>
                                    Enable/Disable the action command to be used in this server.
                                </p>
                            </div>
                            <div class="command">
                                <div class="command_option">
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
                                </div>
                                <p>
                                    Enable/Disable the NSFW actions to be used on the server.
                                    <br><br>
                                    By default, the channel needs to be labeled as age-restricted for the NSFW option to be used.
                                </p>
                            </div>
                            <div class="command">
                                <div class="command_option">
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
                                </div>
                                <p>
                                    Enable/Disable the random message when the action command is executed.
                                </p>
                            </div>
                            <div class="command">
                                <div class="command_option">
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
                                </div>
                                <p>
                                    Enable/Disable the random image when the action command is executed.
                                </p>
                            </div>
                        </div>
                        <h4>
                            Level
                        </h4>
                        <div class="settingCategory_Command">
                            <div class="command">
                                <div class="command_option">
                                    <h3>
                                        Enable
                                    </h3>
                                    <div id="enable_switch">
                                        <?php
                                        if ($loggingFindResult['action_status'] > 0) {
                                        ?>
                                            <input type="submit" style="transform: translate(<?= $translateToOn ?>); background: <?= $colorOn ?>" name="level_status" data-name="toggle" data-status="1" value="" onclick="switchToggle(event)">
                                        <?php
                                        } else {
                                        ?>
                                            <input type="submit" style="transform: translate(<?= $translateToOff ?>); background: <?= $colorOff ?>" name="level_status" data-name="toggle" data-status="0" value="" onclick="switchToggle(event)">
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <p>
                                    Enable/Disable the level system in the server.
                                </p>
                            </div>
                            <div class="information">
                                <h3>
                                    Level Information
                                </h3>
                                <p>
                                    User level and XP rate cannot be changed since they are also used globally. Here's the current rate :
                                    <br><br>
                                    <strong>5 XP</strong> per message
                                </p>
                            </div>
                        </div>
                    </div>
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