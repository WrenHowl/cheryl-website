<?php
// Check if user is logged in.
if (!array_key_exists('user_id', $_SESSION)) {
    header('location: /');
    die;
}

[$guildFind] = discordServers($user_id);

foreach ($guildFind as $guild) {
    var_dump($guild['id']);
}

$guildSelect = DB->prepare("SELECT * FROM guilds");
$guildSelect->execute([
    $id,
]);
$guildSelectResult = $guildSelect->fetchAll(PDO::FETCH_ASSOC);

$pageDesc = 'Select a server to modify.';
?>

<!DOCTYPE html>

<?php
require '../private_html/essential/head.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main>
        <h1>
            Select a server
        </h1>
        <div class="servers">
            <?php
            if ($guildFind) {
                foreach ($guildFind as $guild) {
                    $id = $guild['guild_id'];

                    // Return the correct result
                    $id = $guildSelectResult['id'] ?? 0;
                    $name = $guildSelectResult['name'] ?? null;
                    $avatar = $guildSelectResult['avatar'] ?? null;
                    $botIn = $guildSelectResult['bot_in'] ?? 0;

                    // Check if the server has an icon.
                    if ($avatar == null) {
                        $url = '/assets/images/external_logos/discord.png';
                        continue;
                    }

                    // Set the format of the icon (gif or png)
                    $format = str_starts_with($avatar, 'a_') ?
                        '.gif' :
                        '.png';

                    $url = "https://cdn.discordapp.com/icons/$id/$avatar$format";

                    $href = $botIn === 1 ?
                        "/dashboard/guild/$id" :
                        "https://discord.com/oauth2/authorize?client_id=940369423125061633&guild_id=$id&permissions=1634705210583&response_type=code&redirect_uri=" . PROTOCOLE . "%3A%2F%2F" . DOMAIN . "%2Flogin&integration_type=0&scope=bot+guilds+identify";
            ?>
                    <a class="server" href="<?= $href ?>">
                        <p class="server_name">
                            <?= $name ?>
                        </p>
                        <img class="server_icon" src="<?= $url ?>">
                    </a>
                <?php
                }
            } else {
                ?>
                <p class="no-server">
                    You currently manage<b><span style="color: red">&nbsp; 0</span> servers</b>
                </p>
            <?php
            }
            ?>
        </div>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>