<?php
if (array_key_exists('userId', $_SESSION)) {
    $userId = $_SESSION['userId'];
    $findUser = DB->prepare("SELECT globalName FROM users WHERE userId=:userId");
    $findUser->execute(
        [
            ':userId' => $userId
        ]
    );
    $userName = $findUser->fetchColumn();

    $loginRename = 'Sign Out';
    $dashboard = "/dashboard/servers";
} else {
    $domain = $_SERVER['HTTP_HOST'];
    if ($domain == 'localhost') {
        $protocole = 'http';
    } else {
        $protocole = 'https';
    }
    $loginRename = 'Login';
    $dashboard = "https://discord.com/oauth2/authorize?client_id=940369423125061633&response_type=code&redirect_uri=<?= $protocole ?>%3A%2F%2F<?= $domain ?>%2Fdashboard%2Fapi&scope=guilds+identify";
}

?>

<div class="navBar">
    <ul class="navBar_ul">
        <li class="navBar_left"><a href="/">
                <img class="navBar_cherylLogo" src="/assets/images/cheryl-logo.png">
            </a></li>
        <li class="navBar_left">
            <a href="<?= $dashboard ?>">
                Dashboard
            </a>
        </li>
        <li class="navBar_left"><a href="/commands">
                Commands
            </a></li>
        <li class="navBar_left"><a href="/commissions">
                Commissions
            </a></li>
        <li class="navBar_right">
            <img class="navMenu" src="/assets/images/menu-icon.png" onclick="dropDown()">
            <div id="dropDown" style="display: none">
                <a class="dropDown_option" href="/settings">Settings</a>
                <?php
                if ($loginRename == 'Sign Out') {
                ?>
                    <a class="dropDown_option" onclick="logSign()"><?= $loginRename ?></a>
                <?php
                }
                ?>
                <a class="dropDown_option" href=<?= REDIRECT_ADDBOT ?>>Add Bot</a>
            </div>
        </li>
    </ul>
</div>