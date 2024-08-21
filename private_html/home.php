<!DOCTYPE html>

<html lang="en-US">

<head>
    <title>
        Cheryl - Home
    </title>
    <meta content="Cheryl - Home" property="og:title" />
    <meta content="Moderation & Utility Bot. A lot of customization and simple to use!" property="og:description" />
    <?php
    require 'all.php';
    ?>
</head>

<body>
    <?php
    require 'assets/php/nav-bar.php';
    ?>
    <main>
        <h1 class="windowInfo">
            <div>
                <img src="assets/images/cheryl-logo.png">
            </div>
            <div>
                <p>
                    Cheryl
                </p>
            </div>
        </h1>
        <div class="seperation_Login">
            <?php if (isset($userName)) {
            ?>
                <span class="loggedIn">
                    Welcome back, <span style="color: lightblue"><?= $userName ?></span>!
                </span>
            <?php
            } else { ?>
                <div class="buttonLogin">
                    <a class="buttonLoginText" href="https://discord.com/oauth2/authorize?client_id=940369423125061633&response_type=code&redirect_uri=<?= $protocole ?>%3A%2F%2F<?= $domain ?>%2Fdashboard%2Fapi&scope=identify+guilds">
                        <img class="buttonLoginLogo" src="assets/images/discord-logo.png">
                        Login with Discord
                    </a>
                </div>
            <?php
            } ?>
            </p>
        </div>
        <?php
        require('assets/php/bottom.php');
        ?>
    </main>
</body>

</html>