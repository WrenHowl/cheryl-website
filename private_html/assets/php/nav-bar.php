<?php
if (array_key_exists('userId', $_SESSION)) {
    $userId = $_SESSION['userId'];
    $findUser = DB->prepare("SELECT userName, globalName FROM users WHERE userId=:userId");
    $findUser->execute(
        [
            ':userId' => $userId
        ]
    );
    $findUserResult = $findUser->fetch();

    $userName = $findUserResult['userName'];
    $globalName = $findUserResult['globalName'];

    $loginRename = 'Sign Out';
    $dashboard = "/dashboard/servers";
} else {
    $loginRename = 'Login';
    $dashboard = REDIRECT_LOGIN;
}

?>

<nav class="navBar">
    <ul class="navBar_ul">
        <li class="navBar_left"><a href="/">
                <img class="navBar_cherylLogo" src="/assets/images/all/cheryl-logo.jpg">
            </a>
        </li>
        <li class="navBar_left">
            <a href="<?= $dashboard ?>">
                Dashboard
            </a>
        </li>
        <li class="navBar_left"><a href="/commands">
                Commands
            </a>
        </li>
        <li class="navBar_left"><a href="/commissions">
                Commissions
            </a>
        </li>
        <li class="navBar_right">
            <img class="navMenu" src="/assets/images/all/menu-icon.png" onclick="dropDown()">
            <div id="dropDown" style="display: none">
                <a class="dropDown_option" href="/settings">Settings</a>
                <a class="dropDown_option" href=<?= REDIRECT_ADDBOT ?>>Add Bot</a>
                <?php
                if ($loginRename == 'Sign Out') {
                ?>
                    <a class="dropDown_option" onclick="logSign()"><?= $loginRename ?></a>
                <?php
                }
                ?>
            </div>
        </li>
    </ul>
</nav>