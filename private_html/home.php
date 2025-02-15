<?php
if (array_key_exists('user_id', $_SESSION)) {
    $user = DB->prepare("SELECT * FROM users WHERE id=?");
    $user->execute([
        $user_id,
    ]);
    $userResult = $user->fetch(PDO::FETCH_ASSOC);

    $role = $userResult['role'];
    $globalName = $userResult['global_name'];

    switch ($role) {
        case 1:
            $usernameColor = '#ff1e25';
            break;
        case 2:
            $usernameColor = '#ff5b5b';
            break;
        case 3:
            $usernameColor = '#1668ff';
            break;
        case 4:
            $usernameColor = '#3b80ff';
            break;
        case 5:
            $usernameColor = '#FFD700';
            break;
        default:
            $usernameColor = 'black';
            break;
    };
}

//
// Alert Select
$alert = DB->prepare("SELECT message, timestamp, importance FROM alert ORDER BY timestamp DESC");
$alert->execute();
$alertResult = $alert->fetch(PDO::FETCH_ASSOC);

$alertTimestamp = $alertResult['timestamp'];
$alertMessage = $alertResult['message'];
$alertLevel = $alertResult['importance'];

switch ($alertLevel) {
    case 1:
        $color = '#ffff00';
        break;
    case 2:
        $color = '#ff0000';
        break;
    default:
        $color = '#00ff00';
        break;
}

$pageDesc = 'Moderation & Utility Bot. A lot of customization and simple to use!';
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
        <div class="cheryl">
            <img src="assets/images/cheryl/favicon.png" alt="Logo of Cheryl.">
            <p title="<?= $alertTimestamp ?>" style="color: <?= $color ?>">
                <?php
                echo $alertMessage
                ?>
            </p>
        </div>
        <!--<div class="login">
            <div class="infinite-background"></div>
            <?php
            if (isset($user_id)) {
                $name = isset($global_name) ?
                    $userName :
                    $globalName;
            ?>
                <img src="assets/images/all/wave.png" alt="Wave">
                <span>
                    Welcome back, &#8203; <b style="color: <?= $usernameColor ?>"> <?= $name ?> </b>!
                </span>
            <?php
            } else {
            ?>
                <a href="<?= REDIRECT_LOGIN ?>">
                    Login with Discord
                </a>
            <?php
            }
            ?>
        </div>
        <div class="intro list">
            <div class="intro single">
                <h2>
                    What is Cheryl?
                </h2>
                <p>
                    Cheryl is a Discord bot with a lot of customisation options for server owners and members.
                </p>
            </div>
            <div class="intro single">
                <h2>
                    What command does Cheryl have?
                </h2>
                <p>
                    Here are some examples of Cheryl's commands in action:
                </p>
                <div class="intro img">
                    <div>
                        <img src="/assets/images/home/action-command.gif" alt="The bot is being huged by WrenHowl with the action command in a gif.">
                        <p>
                            Action Command
                        </p>
                    </div>
                    <div>
                        <p>
                            Levelling System
                        </p>
                        <img src="/assets/images/home/level.jpg" alt="The bot is showing the level of WrenHowl with the /level command.">
                    </div>
                </div>
            </div>
        </div>-->
        <div class="servers background">
            <h2>
                Trusted by
            </h2>
            <div class="servers list">
                <?php
                $guildFind = DB->prepare("SELECT * FROM guilds WHERE bot_in=? ORDER BY members DESC LIMIT 5");
                $guildFind->execute([
                    1
                ]);
                $guildFindResult = $guildFind->fetchAll(PDO::FETCH_ASSOC);

                foreach ($guildFindResult as $guild) {
                    $id = $guild['id'];
                    $name = $guild['name'];
                    $icon = $guild['avatar'];
                    $memberCount = $guild['members'];

                    if (!isset($icon)) {
                        $url = '/assets/images/all/error.png';
                        continue;
                    }

                    $format = str_starts_with($icon, 'a_') ?
                        '.gif' :
                        '.png';

                    $url = "https://cdn.discordapp.com/icons/$id/$icon$format";
                ?>
                    <div class="guild background">
                        <img class="guild img" src="<?= $url ?>">
                        <div>
                            <span>
                                <?= $name ?>
                            </span>
                            <span class="guild members">
                                <img src="/assets/images/home/members.png"><?= $memberCount ?>
                            </span>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>