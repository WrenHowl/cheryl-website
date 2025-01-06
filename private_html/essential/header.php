<?php
if (array_key_exists('userId', $_SESSION)) {
    $findUser = DB->prepare("SELECT userName, globalName, role FROM users WHERE userId=?");
    $findUser->execute([
        $userId
    ]);
    $findUserResult = $findUser->fetch();

    $userName = $findUserResult['userName'];
    $globalName = $findUserResult['globalName'];
    $role = $findUserResult['role'];

    $loginRename = 'Sign Out';
    $dashboard = "/dashboard/servers";
} else {
    $loginRename = 'Login';
    $dashboard = REDIRECT_LOGIN;
    $role = 0;
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

?>

<header>
    <div class="navbar-left">
        <a href="/" class="navbar-img">
            <img src="/assets/images/logo/favicon.png" alt="Cheryl Logo">
        </a>
        <a href="<?= $dashboard ?>">
            Dashboard
        </a>
        <a href="/commands">
            Commands
        </a>
        <a href="/leaderboard">
            Leaderboard
        </a>
    </div>
    <div class="navbar-right">
        <?php
        if (isset($userName)) {
            $globalName == null ?
                $name = $userName :
                $name = $globalName;
        ?>
            <div class="navbar-account">
                <div id="navbar-account-idle" onclick="accountDropDown()">
                    <p>
                        <?= $name ?>
                    </p>
                    <img id="navbar-arrow" src="/assets/images/all/arrow.png">
                </div>
                <div id="navbar-dropdown" style="display: none;">
                    <a href="/settings">
                        Account Settings
                    </a>
                    <?php
                    if ($role >= 1) {
                    ?>
                        <a href="/admin">
                            Admin Panel
                        </a>
                    <?php
                    }
                    ?>
                    <a href="/api/logout">
                        Log Out
                    </a>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</header>
<div id="navbar-alert" style="background: <?= $color ?>">
    <img src="/assets/images/all/information-icon.png" alt="Information Icon">
    <p>
        <?= $alertTimestamp ?> → <?= $alertMessage ?>
    </p>
</div>