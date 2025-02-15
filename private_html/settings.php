<?php
if (!array_key_exists('user_id', $_SESSION)) {
    header('Location: /');
    die;
}

$findUser = DB->prepare("SELECT * FROM users WHERE id=?");
$findUser->execute([
    $user_id
]);
$findUserResult = $findUser->fetch(PDO::FETCH_ASSOC);

$role = $findUserResult['role'];

$findUser = DB->prepare("SELECT * FROM user_settings WHERE id=?");
$findUser->execute([
    $user_id
]);
$findUserSetting = $findUser->fetch(PDO::FETCH_ASSOC);

if (!$findUserSetting) {
    $userDataCreate = DB->prepare("INSERT INTO user_settings (id) VALUES (?)");
    $userDataCreate->execute([
        $user_id
    ]);
};

[$guildFind] = discordServers($user_id);

$validServer = [];

foreach ($guildFind as $guild) {
    $validServer[] = $guild['guild_id'];
}

$guildSelect = DB->prepare("SELECT * FROM guilds WHERE id IN (" . rtrim(str_repeat('?,', count($validServer)), ',') . ")");
$guildSelect->execute([
    ...$validServer,
]);
$guildSelectResult = $guildSelect->fetchAll(PDO::FETCH_ASSOC);

$guildTags = DB->prepare("SELECT * FROM guild_tags");
$guildTags->execute();
$guildTagsResult = $guildTags->fetchAll(PDO::FETCH_ASSOC);

$pageDesc = 'Change your account settings.';
?>

<!DOCTYPE html>

<?php
require '../private_html/essential/head.php';
?>

<body>
    <main>
        <nav>
            <div class="nav top">
                <img src="assets/images/cheryl/cheryl.jpg" alt="Logo of Cheryl.">
                <button class="nav seperation" id="user-settings">
                    Account Settings
                </button>
                <button id="bot-settings">
                    Bot Settings
                </button>
                <button class="nav seperation" id="server-settings">
                    Your Servers
                </button>
                <?php
                if ($role >= 1) {
                ?>
                    <button class="nav seperation" id="admin-settings">
                        Admin
                    </button>
                <?php
                }
                ?>
            </div>
            <div class="nav bottom">
                <a href="/">
                    ← <span>Back</span>
                </a>
            </div>
        </nav>
        <div class="setting list">
            <div class="setting type padding" id="user-settings">
                <form class="setting form" method="POST" enctype="application/x-www-form-urlencoded" action="/api/settings/user/<?= $user_id ?>">
                    <h4>
                        Data Tracking
                    </h4>
                    <div class="setting content">
                        <div class="setting info">
                            <div class="setting description">
                                <div>
                                    <h4>
                                        Message Content
                                    </h4>
                                    <label class="switch">
                                        <?php
                                        $valueChange = $findUserSetting['data_messageContent'] === 1 ?
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
                </form>
            </div>
            <div class="setting type padding" id="bot-settings">
                <form class="setting form" method="POST" enctype="application/x-www-form-urlencoded" action="/api/settings/user/<?= $user_id ?>">
                    <?php
                    $actionType = [
                        0 => 'Disabled',
                        1 => 'SFW Actions Only',
                        2 => 'SFW & NSFW Actions',
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
                                        Status
                                    </h4>
                                    <p>
                                        <?= $actionType[$findUserSetting['action_status']] ?>
                                    </p>
                                </div>
                                <p>
                                    Select your status for the action command.
                                </p>
                            </div>
                            <div class="setting option">
                                <button type="button">
                                    <img src="/assets/images/all/arrow.png" alt="Arrow">
                                </button>
                                <div class="setting submenu">
                                    <?php
                                    foreach ($actionType as $key => $value) {
                                        $isDisabled = '';
                                        if ($findUserSetting['action_status'] === $key) {
                                            $isDisabled = 'disabled';
                                        }
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
                    </div>
                    <h4>
                        Level
                    </h4>
                    <div class="setting content">
                        <div class="setting info">
                            <div class="setting description">
                                <div>
                                    <h4>
                                        Level Up
                                    </h4>
                                    <label class="switch">
                                        <?php
                                        $valueChange = $findUserSetting['level_rankup'] === 1 ?
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
                </form>
            </div>
            <div class="setting type center" id="server-settings">
                <div class="servers">
                    <?php
                    foreach ($guildSelectResult as $guild) {
                        $id = $guild['id'];
                        $name = $guild['name'];
                        $avatar = $guild['avatar'];

                        $isNsfw = $guild['nsfw'] ?
                            ' blur' :
                            '';

                        if (!isset($avatar)) {
                            $url = '/assets/images/all/error-square.jpg';
                        } else {
                            // Set the format of the icon (gif or png)
                            $format = str_starts_with($avatar, 'a_') ?
                                '.gif' :
                                '.png';

                            $url = "https://cdn.discordapp.com/icons/$id/$avatar$format";
                        }
                    ?>
                        <img class="guild icon<?= $isNsfw ?>" src="<?= $url ?>" data-name="<?= $guild['name'] ?>" data-id="<?= $guild['id'] ?>">
                    <?php
                    }
                    ?>
                </div>
                <form method="POST" enctype="application/x-www-form-urlencoded" class="guild modify">
                    <h4 class="guild padding">
                        Modify
                    </h4>
                    <div class="guild column padding">
                        <input type="hidden" name="id">
                        <label for="guild textarea">
                            Description :
                        </label>
                        <textarea id="guild textarea" placeholder="Enter the description of your server to be shown publicly." name="description"></textarea>
                        <label for="guild tags">
                            Tag(s) :
                        </label>
                        <input type="text" id="guild tags" placeholder="Tags that describe your server." name="tag" disabled>
                        <div class="guild options">
                            <input type="checkbox" id="guild nsfw" name="nsfw">
                            <label for="guild public">
                                NSFW
                            </label>
                        </div>
                        <span class="guild note">
                            When enabling this, you affirm that your server has any type of NSFW content in the server.
                        </span>
                        <div class="guild options">
                            <input type="checkbox" id="guild public" name="public">
                            <label for="guild public">
                                Public
                            </label>
                        </div>
                        <span class="guild note">
                            When enabling this, you agree that this server will be displayed publicly in the server list.
                        </span>
                        <input type="submit" value="Save">
                    </div>
                    <span class="guild close">
                        X
                    </span>
                </form>
                <span class="tips">
                    To modify your server information, click on the icon of the server of your choice.
                </span>
            </div>
            <?php
            if ($role >= 1) {
            ?>
                <div class="setting type padding" id="admin-settings">
                    <div class="admin incoming">
                        <?php
                        $allGuild = DB->prepare("SELECT * FROM guilds WHERE review_status=?");
                        $allGuild->execute([
                            2
                        ]);
                        $allGuildResult = $allGuild->fetchAll(PDO::FETCH_ASSOC);

                        if ($allGuildResult) {
                            echo '<span class="review status">';
                            echo 'There is servers to review! (<span class="review amount">' . count($allGuildResult) . '</span>)';
                            echo '</span>';
                        }
                        ?>
                    </div>
                    <form method="POST" enctype="application/x-www-form-urlencoded" action="/api/admin/review" class="admin review">
                        <h4 class="review padding">
                            Server Review
                        </h4>
                        <div class="review list">
                            <?php
                            foreach ($allGuildResult as $guild) {
                                $id = $guild['id'];
                                $name = $guild['name'];
                                $avatar = $guild['avatar'];

                                if (!isset($avatar)) {
                                    $url = '/assets/images/all/error-square.jpg';
                                } else {
                                    // Set the format of the icon (gif or png)
                                    $format = str_starts_with($avatar, 'a_') ?
                                        '.gif' :
                                        '.png';

                                    $url = "https://cdn.discordapp.com/icons/$id/$avatar$format";
                                }
                            ?>
                                <div class="review column">
                                    <div class="review top">
                                        <img class="review icon" src="<?= $url ?>">
                                        <div class="review info">
                                            <span class="review name">
                                                <?= $name ?>
                                            </span>
                                            <span class="review members">
                                                <img src="/assets/images/home/members.png"><?= $guild['members'] ?>
                                            </span>
                                            <div class="review tags">
                                                <?php
                                                if ($guild['nsfw'] === 1) {
                                                ?>
                                                    <span class="nsfw">
                                                        NSFW
                                                    </span>
                                                <?php
                                                }

                                                foreach ($guildTagsResult as $tags) {
                                                    if ($tags['id'] !== $guild['id']) continue;
                                                ?>
                                                    <span class="normal">
                                                        <?= $tags['tag'] ?>
                                                    </span>
                                                <?php
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="review center">
                                        <span class="review description">
                                            <?php
                                            $description = isset($guild['description']) ?
                                                $guild['description'] :
                                                "Currently no description available.";
                                            echo $description;
                                            ?>
                                        </span>
                                        <button class="review button" type="button">
                                            <img src="/assets/images/all/arrow.png">
                                        </button>
                                    </div>
                                    <div class="review bottom">
                                        <input type="checkbox" id="accept" name="review_status" value="1">
                                        <label for="accept">
                                            Accept
                                        </label>
                                        <input type="checkbox" id="deny" name="review_status" value="3">
                                        <label for="deny">
                                            Deny
                                        </label>
                                    </div>
                                </div>
                            <?php
                            }
                            ?>
                        </div>
                        <span class="review close">
                            X
                        </span>
                    </form>
                </div>
            <?php
            }
            ?>
        </div>
    </main>
</body>

</html>