<?php
if (!array_key_exists('userId', $_SESSION)) {
    header('Location: /');
    die;
}

//
// Grab the user settings in the database.
$userFind = DB->prepare("SELECT * FROM user_settings WHERE userId=?");
$userFind->execute([
    $userId
]);
$userFindResult = $userFind->fetch(PDO::FETCH_ASSOC);

if (!$userFindResult) {
    $userDataCreate = DB->prepare("INSERT INTO user_settings (userId) VALUES (?)");
    $userDataCreate->execute([
        $userId
    ]);
};

$pageDesc = 'Change your account settings.';
?>

<!DOCTYPE html>

<?php
require 'all/all.php';
require 'all/style.php';
?>

<body>
    <header>
        <a href="/">
            ← Go Back
        </a>
    </header>
    <main>
        <nav>
            <button id="user-settings">
                Account Settings
            </button>
            <button id="bot-settings">
                Bot Settings
            </button>
        </nav>
        <h2 class="default-message">
            SELECT A SETTING TO UPDATE
        </h2>
        <form method="POST" enctype="application/x-www-form-urlencoded" action="/api/user/settings">
            <div class="setting-type" id="user-settings" style="display: none;">
                <h4>
                    Data Tracking
                </h4>
                <div class="settings">
                    <div class="setting">
                        <div class="setting-parameter">
                            <h4>
                                Message Content
                            </h4>
                            <label class="switch">
                                <?php
                                $valueChange = $userFindResult['data_messageContent'] === 1 ?
                                    'checked' :
                                    '';
                                ?>
                                <input type="checkbox" name="data_messageContent" <?= $valueChange ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <p>
                            Enable/Disable the usage of your message to allow you to use message commands, level tracking, etc.
                        </p>
                    </div>
                </div>
            </div>
            <div class="setting-type" id="bot-settings" style="display: none;">
                <h4>
                    Action
                </h4>
                <div class="settings">
                    <div class="setting">
                        <div class="setting-parameter">
                            <h4>
                                Enabled
                            </h4>
                            <label class="switch">
                                <?php
                                $valueChange = $userFindResult['action_enabled'] === 1 ?
                                    'checked' :
                                    '';
                                ?>
                                <input type="checkbox" name="action_enabled" <?= $valueChange ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <p>
                            Enable/Disable the action command to be targeting you.
                        </p>
                    </div>
                    <div class="setting">
                        <div class="setting-parameter">
                            <h4>
                                NSFW
                            </h4>
                            <label class="switch">
                                <?php
                                $valueChange = $userFindResult['action_nsfw'] === 1 ?
                                    'checked' :
                                    ''
                                ?>
                                <input type="checkbox" name="action_nsfw" <?= $valueChange ?>>
                                <span class="slider round"></span>
                            </label>
                        </div>
                        <p>
                            Enable/Disable the NSFW actions to be targeting you.
                        </p>
                    </div>
                </div>
                <h4>
                    Level
                </h4>
                <div class="settings">
                    <div class="setting">
                        <div class="setting-parameter">
                            <h4>
                                Level Up
                            </h4>
                            <label class="switch">
                                <?php
                                $valueChange = $userFindResult['level_rankup'] === 1 ?
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
            <div class="save-button-zone" style="display: none;">
                <input class="save-button" type="submit" value="Save">
            </div>
        </form>
    </main>
</body>

</html>