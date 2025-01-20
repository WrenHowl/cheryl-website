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
    $dashboard = "/dashboard";
} else {
    $loginRename = 'Login';
    $dashboard = REDIRECT_LOGIN;
    $role = 0;
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
                <p>
                    <?= $name ?>
                </p>
                <img src="/assets/images/all/arrow.png" alt="Arrow">
                <div class="navbar-dropdown" style="display: none;">
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
                    <a href="/logout">
                        Log Out
                    </a>
                </div>
            </div>
        <?php
        }
        ?>
    </div>
</header>