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
    <div class="navbar bottom">
        <?php
        if (isset($userName)) {
        ?>
            <div class="navbar account">
                <p>
                    <?= $userName ?>
                </p>
                <img src="/assets/images/all/arrow.png" alt="Arrow">
                <div class="navbar dropdown" style="display: none;">
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