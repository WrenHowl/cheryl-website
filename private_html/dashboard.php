<?php
//
// Check if user is logged in.
if (!array_key_exists('user_id', $_SESSION)) {
    header('location: /');
    die;
}

//
// Get the refresh and access token
$user = DB->prepare("SELECT * FROM users WHERE id=?");
$user->execute([
    $_SESSION['user_id']
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

//
// Check if the next refresh is allowed to get fresh data from discord
if ($userResult['api_cooldown'] < time()) {
    //
    // Update the next refresh to limit the user again
    $refreshCooldown = DB->prepare("UPDATE users SET api_cooldown=? WHERE id=?");
    $refreshCooldown->execute([
        time() + 60,
        $user_id,
    ]);

    //
    // Request info about the user guilds
    $url = API_ENDPOINT . 'users/@me/guilds';
    $request = curl_init();

    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $userResult['token_access'],
    ]);
    $response = curl_exec($request);
    $decodeResponse = json_decode($response, true);

    //
    // Get information about his permissions on the guilds
    $arrayPerm = array_filter($decodeResponse, function ($permission) {
        return ($permission['permissions'] & 0x20) === 0x20;
    });

    foreach ($arrayPerm as $guild) {
        $name = $guild['name'];
        $id = $guild['id'];
        $avatar = $guild['icon'];

        //
        // Update the guild information to make it up-to-date with Discord.
        $guildFind = DB->prepare("SELECT * FROM guilds WHERE id=?");
        $guildFind->execute([
            $id
        ]);
        $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

        if ($guildFindResult) {
            $guildUpdate = DB->prepare("UPDATE guilds SET name=?, avatar=? WHERE id=?");
            $guildUpdate->execute([
                $name,
                $avatar,
                $id,
            ]);
        } else {
            $guildUpdate = DB->prepare("INSERT INTO guilds (name, avatar, id) VALUES (?, ?, ?)");
            $guildUpdate->execute([
                $name,
                $avatar,
                $id,
            ]);
        }

        //
        // Update the permission of the user to make it up-to-date with Discord.
        $guildPermissionFind = DB->prepare("SELECT * FROM guild_userPermission WHERE guild_id=? and user_id=?");
        $guildPermissionFind->execute([
            $id,
            $user_id
        ]);
        $guildPermission = $guildPermissionFind->fetch(PDO::FETCH_ASSOC);

        if ($guildPermission) {
            $guildUpdate = DB->prepare("UPDATE guild_userPermission SET permissions=? WHERE guild_id=? AND user_id=?");
            $guildUpdate->execute([
                $guild['permissions'],
                $id,
                $user_id,
            ]);
        } else {
            $guildInsert = DB->prepare("INSERT INTO guild_userPermission (id, user_id, permissions) VALUES (?, ?, ?)");
            $guildInsert->execute([
                $id,
                $user_id,
                $an['permissions'],
            ]);
        }
    }
}

//
// Permission Guild Database
$guild = DB->prepare("SELECT * FROM guild_userPermission WHERE user_id=?");
$guild->execute([
    $_SESSION['user_id']
]);
$guildFind = $guild->fetchAll(PDO::FETCH_ASSOC);

$pageDesc = 'Select a server to modify.';
?>

<!DOCTYPE html>

<?php
require '../private_html/all/all.php';
require '../private_html/all/style.php';
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

                    //
                    // Guild Database
                    $guildSelect = DB->prepare("SELECT * FROM guilds WHERE id=?");
                    $guildSelect->execute([
                        $id,
                    ]);
                    $guildSelectResult = $guildSelect->fetch(PDO::FETCH_ASSOC);

                    //
                    // Return the correct result
                    $id = $guildSelectResult['id'] ?? 0;
                    $name = $guildSelectResult['name'] ?? null;
                    $avatar = $guildSelectResult['avatar'] ?? null;
                    $botIn = $guildSelectResult['bot_in'] ?? 0;

                    //
                    // Check if the server has an icon.
                    if ($avatar == null) {
                        $url = '/assets/images/external_logos/discord.png';
                        continue;
                    }

                    //
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
                    You currently manage <b><span style="color: red">0</span> servers</b>
                </p>
            <?php
            }
            ?>
        </div>
    </main>
    <?php
    require('../private_html/essential/footer.php');
    ?>
</body>

</html>