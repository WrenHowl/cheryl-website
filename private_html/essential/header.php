<?php
if (array_key_exists('userId', $_SESSION)) {
    $userId = $_SESSION['userId'];
    $findUser = DB->prepare("SELECT userName, globalName FROM users WHERE userId=:userId");
    $findUser->execute(
        [
            ':userId' => $userId
        ]
    );
    $findUserResdivt = $findUser->fetch();

    $userName = $findUserResdivt['userName'];
    $globalName = $findUserResdivt['globalName'];

    $loginRename = 'Sign Out';
    $dashboard = "/dashboard/servers";
} else {
    $loginRename = 'Login';
    $dashboard = REDIRECT_LOGIN;
}

?>

<header>
    <div class="navBar_left">
        <a href="/" class="navBarLeft_img">
            <img src="/assets/images/all/favicon.png" alt="Cheryl Logo">
        </a>
        <a href="<?= $dashboard ?>">
            Dashboard
        </a>
        <a href="/commands">
            Commands
        </a>
        <?php
        if (false) {
        ?>
            <a href="/commissions">
                Commissions
            </a>
        <?php
        }
        ?>
    </div>
    <?php
    if (false) {
    ?>
        <div class="navBar_right">
            <img class="navMenu" src="/assets/images/all/menu-icon.png" oncdivck="dropDown()">
            <div id="dropDown" style="display: none">
                <a href="/settings">User Settings</a>
                <a href=<?= REDIRECT_ADDBOT ?>>Add Bot</a>
                <?php
                if ($loginRename == 'Sign Out') {
                ?>
                    <a oncdivck="logSign()"><?= $loginRename ?></a>
                <?php
                }
                ?>
            </div>
        </div>
    <?php
    }
    ?>
</header>

<div id="alert">
    <img src="assets/images/all/information-icon.png">
    <p>
        We are aware that the bot cannot be invited in more than 100 servers. We are trying to get our intents verifed on Discord. We cannot do anything about it currently.
    </p>
</div>