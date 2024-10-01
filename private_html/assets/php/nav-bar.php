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
    <div class="navBar">
        <div class="navBar_left">
            <a href="/" class="navBarLeft_img">
                <img src="/assets/images/all/cheryl.jpg" alt="Cheryl Logo">
            </a>
            <a href="<?= $dashboard ?>">
                Dashboard
            </a>
            <a href="/commands">
                Commands
            </a>
            <a href="/commissions">
                Commissions
            </a>
        </div>
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
    </div>
</header>