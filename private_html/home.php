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
        Cheryl - Home
    </title>
    <meta content="Cheryl - Home" property="og:title" />
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
            <img class="windowInfo_logo" src="assets/images/all/cheryl-logo.jpg">
            <div class="windowInfo_text">
                Cheryl
            </div>
        </div>
        <div class="windowInfo_login">
            <?php if (isset($userName)) {
                $name = $globalName;
                if ($globalName == null) $name = $userName;
            ?>
                <span>
                    <img src="assets/images/all/512px1.png">
                    <br><br>
                    Welcome back, <b><span style="color: <?= $color ?>"><?= $name ?></span></b>!
                </span>
            <?php
            } else { ?>
                <div class="buttonLogin">
                    <a href="<?= REDIRECT_LOGIN ?>">
                        Login with Discord
                    </a>
                </div>
            <?php
            } ?>
            </p>
        </div>
        <div class="introduction">
            <div class="introductionB_left" id="introductionB">
                <img class="introductionB_img" src="assets/images/all/512px7.png">
                <div class="introductionB_text">
                    What is Cheryl?
                    <p>
                        Cheryl is a discord bot and website for artist that wishes to promote themselves.
                    </p>
                </div>
            </div>
            <div class="introductionB_right" id="introductionB">
                <div class="introductionB_text">
                    What does Cheryl offer?
                    <p>
                        Cheryl offers a variety of commands and customisation. From action command to roleplay actions to a fully working blacklist system to prevent bad actor to join your server.
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