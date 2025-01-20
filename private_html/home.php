<?php
if (array_key_exists('userId', $_SESSION)) {
    $user = DB->prepare("SELECT `role`, `globalName` FROM users WHERE userId=?");
    $user->execute([
        $userId,
    ]);
    $userResult = $user->fetch(PDO::FETCH_ASSOC);

    $role = $userResult['role'];
    $globalName = $userResult['globalName'];

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
require 'all/all.php';
require 'all/style.php';
?>

<body>
    <?php
    require 'essential/header.php';
    ?>
    <main>
        <div class="cheryl">
            <img src="assets/images/logo/favicon.png">
            <p title="<?= $alertTimestamp ?>" style="color: <?= $color ?>">
                <?php
                echo $alertMessage
                ?>
            </p>
        </div>
        <div class="login">
            <?php
            if (isset($userName)) {
                $globalName == null ?
                    $name = $userName :
                    $name = $globalName;
            ?>
                <img src="assets/images/all/wave.png" alt="Bad Dwagon Wave">
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
        <div class="introductions">
            <div>
                <h2>
                    What is Cheryl?
                </h2>
                <p>
                    Cheryl is widely known as a Discord Bot.
                </p>
            </div>
            <div>
                <h2>
                    What does Cheryl offer?
                </h2>
                <p>
                    Cheryl provides a wide range of commands and customization. From action command for roleplaying activities, you can stop bad actors from joining your server with a fully functional blacklist system.
                </p>
            </div>
        </div>
    </main>
    <?php
    require 'essential/footer.php';
    ?>
</body>

</html>