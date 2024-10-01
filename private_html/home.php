<?php
if (array_key_exists('userId', $_SESSION)) {
    $userId = $_SESSION['userId'];

    $user = DB->prepare("SELECT `role`, `globalName` FROM users WHERE userId=:userId");
    $user->execute(
        [
            ':userId' => $userId,
        ],
    );
    $userResult = $user->fetch(PDO::FETCH_ASSOC);

    $role = $userResult['role'];
    $globalName = $userResult['globalName'];

    switch ($role) {
        case (1):
            $color = '#ff1e25';
            break;
        case (2):
            $color = '#ff5b5b';
            break;
        case (3):
            $color = '#1668ff';
            break;
        case (4):
            $color = '#3b80ff';
            break;
        case (5):
            $color = '#FFD700';
            break;
        default:
            $color = 'white';
            break;
    };
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <title>
        Cheryl | Home
    </title>
    <meta content="Cheryl | Home" property="og:title" />
    <meta content="Moderation & Utility Bot. A lot of customization and simple to use!" property="og:description" />
    <?php
    require 'all.php';
    ?>
</head>

<body>
    <?php
    require 'assets/php/nav-bar.php';
    ?>
    <main>
        <div class="windowInfo">
            CHERYL
        </div>
        <div class="windowInfo_login">
            <?php
            if (isset($userName)) {
                $globalName == null ?
                    $name = $userName :
                    $name = $globalName;
            ?>
                <img src="assets/images/all/512px1.png" alt="Bad Dwagon Wave">
                <p>Welcome back, <b><span style="color: <?= $color ?>"><?= $name ?></span></b>!</p>
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
        <div class="introduction">
            <div class="introductionB" style="text-align: left;">
                <img class="introductionB_img" src="assets/images/all/512px7.png">
                <div class="introductionB_text">
                    What is Cheryl?
                    <p>
                        Cheryl is a website and discord bot for artists looking to advertise themselves.
                    </p>
                </div>
            </div>
            <div class="introductionB" style="text-align: right;">
                <div class="introductionB_text">
                    What does Cheryl offer?
                    <p>
                        Cheryl provides a wide range of commands and customization. From action command for roleplaying activities, you can stop bad actors from joining your server with a fully functional blacklist system.
                    </p>
                </div>
                <img class="introductionB_img" src="assets/images/all/512px7.png">
            </div>
        </div>
        <?php
        require('assets/php/bottom.php');
        ?>
    </main>
</body>

</html>