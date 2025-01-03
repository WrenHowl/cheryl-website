<?php
if (array_key_exists('userId', $_SESSION)) {
    $userId = $_SESSION['userId'];
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
    <div class="navBar_left">
        <a href="/" class="navBarLeft_img">
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
    <div class="navBar_right">
        <?php
        if ($role == 1) {
        ?>
            <a href="/admin">
                <img src="/assets/images/moderation/ban-hammer.png" alt="Admin">
            </a>
        <?php
        }
        ?>
    </div>
</header>

<div id="alert" style="background: <?= $color ?>">
    <img src="/assets/images/all/information-icon.png" alt="Information Icon">
    <p>
        <?= $alertTimestamp ?> → <?= $alertMessage ?>
    </p>
</div>