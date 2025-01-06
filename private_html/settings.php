<?php
if (!array_key_exists('userId', $_SESSION)) {
    header('Location: /');
    die;
}

//
// User Result
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
    <?php
    require 'essential/header.php';
    ?>
    <main id="page">
        <form class="page" method="POST" enctype="application/x-www-form-urlencoded" action="/api/user/settings">
            <div class="filter-setting">
                <div class="filter-setting-list">
                    <input type="button" id="user-settings" value="Account Settings" onclick="toggleSetting(this.id)">
                </div>
            </div>
            <h2 class="noSettingSelected">
                SELECT A SETTING TO UPDATE
            </h2>
            <div class="all-settings">
                <div id="user-settings" style="display: none;">
                    <div class="setting-list">
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
                                        $valueChange = $userFindResult['action_enabled'] ?
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
                                        $valueChange = $userFindResult['action_nsfw'] ?
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
                                        $valueChange = $userFindResult['level_rankup'] ?
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
            </div>
            <div class="save-button-zone">
                <input class="save-button" type="submit" value="Save">
            </div>
        </form>
        <?php
        require 'essential/footer.php';
        ?>
    </main>
</body>

</html>