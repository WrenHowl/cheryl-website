<!DOCTYPE html>

<?php
if (array_key_exists('userId', $_SESSION)) {
    $userId = $_SESSION['userId'];

    $user = DB->prepare("SELECT `role`, `globalName` FROM users WHERE userId=?");
    $user->execute([
        $userId,
    ]);
    $userResult = $user->fetch(PDO::FETCH_ASSOC);

    $role = $userResult['role'];
    $globalName = $userResult['globalName'];

    switch ($role) {
        case 1:
            $color = '#ff1e25';
            break;
        case 2:
            $color = '#ff5b5b';
            break;
        case 3:
            $color = '#1668ff';
            break;
        case 4:
            $color = '#3b80ff';
            break;
        case 5:
            $color = '#FFD700';
            break;
        default:
            $color = 'black';
            break;
    };
}

$pageDesc = 'Moderation & Utility Bot. A lot of customization and simple to use!';

require 'all.php';
?>

<body>
    <?php
    require 'essential/header.php';
    ?>
    <main id="page" onscroll="scrollAlert()">
        <div class="cheryl">
            <img src="assets/images/logo/favicon.png">
        </div>
        <div class="login">
            <?php
            if (isset($userName)) {
                $globalName == null ?
                    $name = $userName :
                    $name = $globalName;

            ?>
                <p>
                    <img src="assets/images/all/wave.png" alt="Bad Dwagon Wave">
                    Welcome back,
                    <b style="color: <?= $color ?>"> <?= $name ?></b>!
                </p>
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
            <div class="introduction-text">
                <h2>
                    What is Cheryl?
                </h2>
                <p>
                    Cheryl is widely known as a Discord Bot.
                </p>
            </div>
            <div class="introduction-text">
                <h2>
                    What does Cheryl offer?
                </h2>
                <p>
                    Cheryl provides a wide range of commands and customization. From action command for roleplaying activities, you can stop bad actors from joining your server with a fully functional blacklist system.
                </p>
            </div>
        </div>
        <?php
        require 'essential/footer.php';
        ?>
    </main>
</body>

</html>