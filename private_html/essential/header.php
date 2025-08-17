<?php
if (array_key_exists('user_id', $_SESSION)) {
    $user = DB->prepare("SELECT * FROM users WHERE id=?");
    $user->execute([
        $user_id
    ]);
    $userData = $user->fetch(PDO::FETCH_ASSOC);

    $iconStatus = 'href="/settings"';
    $textStatus = 'Log Out';
    $logRedirect = '/logout';

    $dashboard = "/dashboard";
} else {
    $iconStatus = '';
    $textStatus = 'Log In';
    $logRedirect = REDIRECT_LOGIN;

    $dashboard = REDIRECT_LOGIN;
}

$userName = $userData['name'] ?? '';
$globalName = $userData['global_name'] ?? $userName;
$role = $userData['role'] ?? 0;

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
        <a href="<?= $logRedirect ?>">
            <?= $textStatus ?>
        </a>
        <a class="navbar account seperator" <?= $iconStatus ?>>
            <span>
                <?= $globalName ?>
            </span>
            <img src="/assets/images/all/account.png" alt="Account Icon">
        </a>
    </div>
</header>