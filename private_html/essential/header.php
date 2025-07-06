<?php
if (array_key_exists('user_id', $_SESSION)) {
    $findUser = DB->prepare("SELECT * FROM users WHERE id=?");
    $findUser->execute([
        $user_id
    ]);
    $findUserResult = $findUser->fetch(PDO::FETCH_ASSOC);

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
            <img src="/assets/images/cheryl/favicon.png" alt="Cheryl Logo">
        </a>
        <div class="navbar seperator">
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
        <div class="navbar seperator">
            <button>
                Browse
                <div class="navbar dropdown">
                    <div>
                        <span class="navbar line">
                            ├
                        </span>
                        <a href="/browse/servers">
                            Servers
                        </a>
                    </div>
                    <div>
                        <span class="navbar line end">
                            └
                        </span>
                        <a href="/browse/commissions">
                            Commissions
                        </a>
                    </div>
                </div>
            </button>
        </div>
    </div>
    <div class="navbar bottom">
        <?php
        if (isset($user_id)) {
            $iconStatus = 'href="/settings"';
            $textStatus = 'Log Out';
            $textRef = '/logout';
        } else {
            $iconStatus = '';
            $textStatus = 'Log In';
            $textRef = REDIRECT_LOGIN;
        }
        ?>
        <a href="<?= $textRef ?>">
            <?= $textStatus ?>
        </a>
        <a class="navbar account seperator" <?= $iconStatus ?>>
            <img src="/assets/images/all/account.png" alt="Account Icon">
        </a>
    </div>
</header>