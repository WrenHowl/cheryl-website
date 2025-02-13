<?php
if (array_key_exists('user_id', $_SESSION)) {
    $findUser = DB->prepare("SELECT * FROM users WHERE id=?");
    $findUser->execute([
        $user_id
    ]);
    $findUserResult = $findUser->fetch();

    $userName = $findUserResult['name'];
    $globalName = $findUserResult['global_name'] ?? $userName;
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
    <div class="navbar top">
        <a href="/">
            <img src="/assets/images/logo/favicon.png" alt="Cheryl Logo">
        </a>
        <a class="navbar seperator" href="<?= $dashboard ?>">
            Dashboard
        </a>
        <a href="/commands">
            Commands
        </a>
        <a href="/leaderboard">
            Leaderboard
        </a>
        <a class="navbar seperator" href="/browse">
            Browse
        </a>
    </div>
    <div class="navbar bottom">
        <?php
        if (isset($userName)) {
        ?>
            <div class="navbar account">
                <span>
                    <?= $globalName ?>
                </span>
                <img src="/assets/images/all/account.png" alt="Account Icon">
                <div class="navbar dropdown">
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