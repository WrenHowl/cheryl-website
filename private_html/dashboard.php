<?php
//
// Check if user is logged in.
if (!array_key_exists('userId', $_SESSION)) {
    header('location: /');
    die;
}

//
// Get the refresh and access token
$user = DB->prepare("SELECT nextRefresh, accessToken FROM users WHERE userId=?");
$user->execute([
    $_SESSION['userId']
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

//
// Check if the next refresh is allowed to get fresh data from discord
if ($userResult['nextRefresh'] < time()) {
    //
    // Update the next refresh to limit the user again
    $refreshCooldown = DB->prepare("UPDATE users SET nextRefresh=? WHERE userId=?");
    $refreshCooldown->execute([
        time() + 60,
        $userId,
    ]);

    //
    // Request info about the user guilds
    $url = API_ENDPOINT . 'users/@me/guilds';
    $request = curl_init();

    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $userResult['accessToken'],
    ]);
    $response = curl_exec($request);
    $decodeResponse = json_decode($response, true);

    //
    // Get information about his permissions on the guilds
    $arrayPerm = array_filter($decodeResponse, function ($permission) {
        return ($permission['permissions'] & 0x20) === 0x20;
    });

    foreach ($arrayPerm as $guild) {
        $guildName = $guild['name'];
        $guildId = $guild['id'];
        $guildIcon = $guild['icon'];

        //
        // Update the guild information to make it up-to-date with Discord.
        $guildFind = DB->prepare("SELECT guildId FROM guilds WHERE guildId=?");
        $guildFind->execute([
            $guildId
        ]);
        $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

        if ($guildFindResult) {
            $guildUpdate = DB->prepare("UPDATE guilds SET guildName=?, guildIcon=? WHERE guildId=?");
            $guildUpdate->execute([
                $guildName,
                $guildIcon,
                $guildId,
            ]);
        } else {
            $guildUpdate = DB->prepare("INSERT INTO guilds (guildName, guildIcon, guildId) VALUES (?, ?, ?)");
            $guildUpdate->execute([
                $guildName,
                $guildIcon,
                $guildId,
            ]);
        }

        //
        // Update the permission of the user to make it up-to-date with Discord.
        $guildPermissionFind = DB->prepare("SELECT guildId, userId FROM guild_userPermission WHERE guildId=? and userId=?");
        $guildPermissionFind->execute([
            $guildId,
            $userId
        ]);
        $guildPermission = $guildPermissionFind->fetch(PDO::FETCH_ASSOC);

        if ($guildPermission) {
            $guildUpdate = DB->prepare("UPDATE guild_userPermission SET permissionInt=? WHERE guildId=? AND userId=?");
            $guildUpdate->execute([
                $guild['permissions'],
                $guildId,
                $userId,
            ]);
        } else {
            $guildInsert = DB->prepare("INSERT INTO guild_userPermission (guildId, userId, permissionInt) VALUES (?, ?, ?)");
            $guildInsert->execute([
                $guildId,
                $userId,
                $an['permissions'],
            ]);
        }
    }
}

//
// Permission Guild Database
$guild = DB->prepare("SELECT userId, guildId FROM guild_userPermission WHERE userId=?");
$guild->execute([
    $_SESSION['userId']
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
                    $guildId = $guild['guildId'];

                    //
                    // Guild Database
                    $guildSelect = DB->prepare("SELECT * FROM guilds WHERE guildId=?");
                    $guildSelect->execute([
                        $guildId,
                    ]);
                    $guildSelectResult = $guildSelect->fetch(PDO::FETCH_ASSOC);

                    //
                    // Return the correct result
                    $guildId = $guildSelectResult['guildId'] ?? 0;
                    $guildName = $guildSelectResult['guildName'] ?? null;
                    $guildIcon = $guildSelectResult['guildIcon'] ?? null;
                    $botIn = $guildSelectResult['botIn'] ?? 0;

                    //
                    // Check if the server has an icon.
                    if ($guildIcon == null) {
                        $url = '/assets/images/external_logos/discord.png';
                        continue;
                    }

                    //
                    // Set the format of the icon (gif or png)
                    str_starts_with($guildIcon, 'a_') ?
                        $format = '.gif' :
                        $format = '.png';

                    $url = "https://cdn.discordapp.com/icons/$guildId/$guildIcon$format";

                    $botIn == 1 ?
                        $href = "/dashboard/guild/$guildId" :
                        $href =  "https://discord.com/oauth2/authorize?client_id=940369423125061633&guild_id=$guildId&permissions=1634705210583&response_type=code&redirect_uri=" . PROTOCOLE . "%3A%2F%2F" . DOMAIN . "%2Fapi%2Flogin&integration_type=0&scope=bot+guilds+identify";
            ?>
                    <a class="server" href="<?= $href ?>">
                        <p class="server_name">
                            <?= $guildName ?>
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