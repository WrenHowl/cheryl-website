<?php
//
// Check if user is logged in.
if (!array_key_exists('user_id', $_SESSION)) {
    header('location: /');
    die;
}

$user = DB->prepare("SELECT * FROM users WHERE id=?");
$user->execute([
    $_SESSION['user_id']
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

//
// Request info about the user guild roles.
$url = API_ENDPOINT . 'guilds/' . $guildMatches[3] . '/channels';
$request = curl_init();

curl_setopt($request, CURLOPT_URL, $url);
curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
curl_setopt($request, CURLOPT_HTTPHEADER, [
    'Authorization: Bot ' . BOT_TOKEN,
]);
$response = curl_exec($request);
$decodeResponse = json_decode($response, true);

if (isset($decodeResponse['code']) && $decodeResponse['code'] === 10004) {
    header('Location: /dashboard');
    die;
}

usort($decodeResponse, fn($a, $b) => $a['position'] <=> $b['position']);

//
// Check if the guild exist in the database
$guildFind = DB->prepare("SELECT * FROM guilds WHERE id=?");
$guildFind->execute([
    $guildMatches[3]
]);
$guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

//
// Check if the user requesting has permission to edit the guild
$guildPermission = DB->prepare("SELECT * FROM guild_userPermission WHERE guild_id=? and user_id=?");
$guildPermission->execute([
    $guildMatches[3],
    $user_id,
]);
$guildPermissionResult = $guildPermission->fetch(PDO::FETCH_ASSOC);

//
// Return to the server list if the guild doesn't exist
if (!$guildFindResult || !$guildPermissionResult) {
    header('Location: /dashboard/servers');
    die;
}

$guildName = $guildFindResult['name'];
$guildId = $guildFindResult['id'];
$guildAvatar = $guildFindResult['avatar'];

$guildSettings = DB->prepare("SELECT * FROM guild_settings WHERE id=?");
$guildSettings->execute([
    $guildMatches[3],
]);
$guildSettingsResult = $guildSettings->fetch(PDO::FETCH_ASSOC);

//
// If guild can't be find in the logging database
if (!$guildSettingsResult) {
    $guildLoggingCreate = DB->prepare("INSERT INTO guild_settings (id) VALUES (?)");
    $guildLoggingCreate->execute([
        $guildMatches[3]
    ]);
}

$pageDesc = "Editing $guildName settings.";
?>

<!DOCTYPE html>

<?php
require '../private_html/essential/head.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main>
        <h1>
            <span>Editing →</span><?= $guildName ?>
        </h1>
        <nav>
            <button id="bot-settings">
                Bot Settings
            </button>
            <button id="admin-settings">
                Admin
            </button>
            <button id="mod-settings">
                Mod
            </button>
            <button id="fun-settings">
                Fun
            </button>
        </nav>
        <h2 class="default-message">
            SELECT A SETTING TO UPDATE
        </h2>
        <form method="POST" enctype="application/x-www-form-urlencoded" action="/api/settings/guild/<?= $guildMatches[3] ?>" class="setting list">
            <div class="setting type" id="bot-settings">
                <h4>
                    General
                </h4>
                <div class="setting content">
                    <div class="setting info">
                        <div class="setting description">
                            <div>
                                <h4>
                                    Language
                                </h4>
                                <p>
                                    <?= $language[$guildSettingsResult['language']] ?>
                                </p>
                            </div>
                            <p>
                                Enable/Disable the usage of your message to allow you to use message commands, level tracking, etc.
                            </p>
                        </div>
                        <div class="setting option">
                            <button type="button">
                                <img src="/assets/images/all/arrow.png" alt="Arrow">
                            </button>
                            <div class="setting submenu">
                                <?php
                                foreach ($language as $key => $value) {
                                    $isDisabled = $guildSettingsResult['language'] === $key ?
                                        'disabled' :
                                        '';
                                ?>
                                    <label for="language-<?= $key ?>">
                                        <input type="checkbox" id="language-<?= $key ?>" name="language" value="<?= $key ?>" <?= $isDisabled ?>>
                                        <?php
                                        echo $value;
                                        ?>
                                    </label>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="setting type" id="admin-settings">
                <?php
                $blacklistType = [
                    1 => 'Disabled',
                    2 => 'High risk',
                    3 => 'Medium & High risk',
                    4 => 'Low, Medium & High risk'
                ];
                ?>
                <h4>
                    Blacklist
                </h4>
                <div class="setting content">
                    <div class="setting info">
                        <div class="setting description">
                            <div>
                                <h4>
                                    Enabled
                                </h4>
                                <label class="switch">
                                    <?php
                                    $valueChange = $guildSettingsResult['blacklist_status'] >= 1     ?
                                        'checked' :
                                        ''
                                    ?>
                                    <input type="checkbox" name="blacklist_status" <?= $valueChange ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <p>
                                Enable/Disable the blacklist system.
                            </p>
                        </div>
                    </div>
                    <div class="setting info">
                        <div class="setting description">
                            <div>
                                <h4>
                                    Auto-Ban
                                </h4>
                                <p>
                                    <?php
                                    $blacklist = $guildSettingsResult['blacklist_status'] <= 1 ?
                                        1 :
                                        $guildSettingsResult['blacklist_status'];

                                    echo $blacklistType[$blacklist];
                                    ?>
                                </p>
                            </div>
                            <p>
                                Enabled/Disable the auto-ban feature. This will ban members has they join the server and alert staff members of the their previous offenses.
                            </p>
                        </div>
                        <div class="setting option">
                            <button type="button">
                                <img src="/assets/images/all/arrow.png" alt="Arrow">
                            </button>
                            <div class="setting submenu">
                                <?php
                                foreach ($blacklistType as $key => $value) {
                                    $isDisabled = '';
                                    if (($guildSettingsResult['blacklist_status'] <= 1 && $key <= 1) || $guildSettingsResult['blacklist_status'] === $key) {
                                        $isDisabled = 'disabled';
                                    }
                                ?>
                                    <label for="action-<?= $key ?>">
                                        <input type="checkbox" id="action-<?= $key ?>" name="blacklist_status" value="<?= $key ?>" <?= $isDisabled ?>>
                                        <?php
                                        echo $value;
                                        ?>
                                    </label>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <h4>
                    Welcome
                </h4>
                <div class="setting content">
                    <div class="setting info">
                        <div class="setting description">
                            <div>
                                <h4>
                                    Channel
                                </h4>
                            </div>
                            <p>
                                Setup a welcome channel.
                            </p>
                        </div>
                        <div class="setting option">
                            <button type="button">
                                <img src="/assets/images/all/arrow.png" alt="Arrow">
                            </button>
                            <div class="setting submenu">
                                <?php
                                foreach ($decodeResponse as $key => $value) {
                                    if ($value['type'] === 2 || $value['type'] === 4) continue;
                                    $color = $value['nsfw'] === true ?
                                        'channel_text_nsfw.png' :
                                        'channel_text.png';
                                    $isChecked = $guildSettingsResult['welcome_channelDestination'] === $value['id'] ?
                                        'checked' :
                                        '';
                                ?>
                                    <div class="channel">
                                        <img src="/assets/images/discord/<?= $color ?>">
                                        <label for="<?= $value['id'] ?>">
                                            <input type="checkbox" id="<?= $value['id'] ?>" name="welcome_channelDestination" value="<?= $value['id'] ?>" <?= $isChecked ?>>
                                            <?= $value['name'] ?>
                                        </label>
                                    </div>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="setting type" id="mod-settings">
                <p class="no-setting">
                    There is no setting available currently for this category.
                </p>
            </div>
            <div class="setting type" id="fun-settings">
                <?php
                $actionType = [
                    0 => 'Disabled',
                    1 => 'Image Only',
                    2 => 'Message Only',
                    3 => 'Image and Message'
                ];
                ?>
                <h4>
                    Action
                </h4>
                <div class="setting content">
                    <div class="setting info">
                        <div class="setting description">
                            <div>
                                <h4>
                                    Enabled
                                </h4>
                                <p>
                                    <?= $actionType[$guildSettingsResult['action_status']] ?>
                                </p>
                            </div>
                            <p>
                                Enable/Disable the action command to be used in the server and modify the message being sent by the bot.
                            </p>
                        </div>
                        <div class="setting option">
                            <button type="button">
                                <img src="/assets/images/all/arrow.png" alt="Arrow">
                            </button>
                            <div class="setting submenu">
                                <?php
                                foreach ($actionType as $key => $value) {
                                    $isDisabled = $guildSettingsResult['action_status'] === $key ?
                                        'disabled' :
                                        '';
                                ?>
                                    <label for="action-<?= $key ?>">
                                        <input type="checkbox" id="action-<?= $key ?>" name="action_status" value="<?= $key ?>" <?= $isDisabled ?>>
                                        <?php
                                        echo $value;
                                        ?>
                                    </label>
                                <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="setting info">
                        <div class="setting description">
                            <div>
                                <h4>
                                    NSFW
                                </h4>
                                <label class="switch">
                                    <?php
                                    $valueChange = $guildSettingsResult['action_nsfw'] === 1 ?
                                        'checked' :
                                        ''
                                    ?>
                                    <input type="checkbox" name="action_nsfw" <?= $valueChange ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <p>
                                Enable/Disable the NSFW actions to be used in the server.
                            </p>
                        </div>
                    </div>
                </div>
                <h4>
                    Level
                </h4>
                <div class="setting content">
                    <div class="setting info">
                        <div class="setting description">
                            <div>
                                <h4>
                                    Enabled
                                </h4>
                                <label class="switch">
                                    <?php
                                    $valueChange = $guildSettingsResult['level_status'] === 1 ?
                                        'checked' :
                                        '';
                                    ?>
                                    <input type="checkbox" name="level_status" <?= $valueChange ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <p>
                                Enable/Disable the levelling system.
                            </p>
                        </div>
                    </div>
                    <div class="setting info">
                        <div class="setting description">
                            <div>
                                <h4>
                                    Level Up
                                </h4>
                                <label class="switch">
                                    <?php
                                    $valueChange = $guildSettingsResult['level_rankup'] ?
                                        'checked' :
                                        '';
                                    ?>
                                    <input type="checkbox" name="level_rankup" <?= $valueChange ?>>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                            <p>
                                Enable/Disable the level up message.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="setting save" style="display: none">
                <input type="submit" value="Save">
            </div>
        </form>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>