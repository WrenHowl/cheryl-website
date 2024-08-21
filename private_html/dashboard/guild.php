<?php
expireAt();

if (!array_key_exists('userId', $_SESSION)) {
    toDashboard();
}

$guildFind = DB->prepare("SELECT guildName, guildId, guildIcon FROM guilds WHERE guildId=:guildId");
$guildFind->execute([':guildId' => $_GET['id']]);
$guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

if (!$guildFindResult) {
    header("location: /dashboard/servers");
    die;
}

$guildName = $guildFindResult['guildName'];
$guildId = $guildFindResult['guildId'];
$guildIcon = $guildFindResult['guildIcon'];

?>

<!DOCTYPE html>

<html lang="en-US">

<head>
    <title>
        Cheryl - Editing <?= $guildName ?>
    </title>
    <meta content="Cheryl - Editing <?= $guildName ?>" property="og:title" />
    <meta content="Edit <?= $guildName ?> guild settings." property="og:description" />
    <?php
    require '../private_html/all.php';
    ?>
</head>

<body>
    <?php
    require '../private_html/assets/php/nav-bar.php';
    ?>
    <main>
        <h1 class="windowInfo">
            Editing <span style="color: #f1c40f"><?= $guildName ?></span>'s server
        </h1>
        <div class="filterCommand">
            <li class="command">
                <button id="button_staff" onclick="showNewCommands(this.id)">
                    Bot Settings
                </button>
            </li>
            <li class="command">
                <button id="button_mod" onclick="showNewCommands(this.id)">
                    Admin
                </button>
            </li>
            <li class="command">
                <button id="button_mod" onclick="showNewCommands(this.id)">
                    Moderation
                </button>
            </li>
            <li class="command">
                <button id="button_fun" onclick="showNewCommands(this.id)">
                    Fun
                </button>
            </li>
            <li class="command">
                <button id="button_util" onclick="showNewCommands(this.id)">
                    Utilites
                </button>
            </li>
        </div>
        <?php
        require 'guild/bot-settings.php';
        ?>
    </main>
</body>

</html>