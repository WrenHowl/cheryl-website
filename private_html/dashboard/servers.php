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
if (!$userResult['nextRefresh'] < time()) {
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
    $arrPerm = array_filter($decodeResponse, function ($permission) {
        return ($permission['permissions'] & 0x20) === 0x20;
    });

    //
    // Function to be executed when requesting the server informations 
    function apiGet($an, $userId)
    {
        $guildName = $an['name'];
        $guildId = $an['id'];
        $guildIcon = $an['icon'];

        $guildFind = DB->prepare("SELECT guildId FROM guilds WHERE guildId=?");
        $guildFind->execute([
            $guildId
        ]);
        $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

        //
        // Update the guild information to make it up-to-date with Discord.
        if ($guildFindResult) {
            $guildUpdate = DB->prepare("UPDATE guilds SET guildName=?, guildIcon=? WHERE guildId=?");
            $guildUpdate->execute([
                $guildName,
                $guildIcon,
                $guildId,
            ]);
        }

        $guildPermissionFind = DB->prepare("SELECT guildId, userId FROM permissionsuserguilds WHERE guildId=? and userId=?");
        $guildPermissionFind->execute([
            $guildId,
            $userId
        ]);
        $guildPermissionFindResult = $guildPermissionFind->fetch(PDO::FETCH_ASSOC);

        //
        // Update the permission of the user to make it up-to-date with Discord.
        if ($guildPermissionFindResult) {
            $guildUpdate = DB->prepare("UPDATE permissionsuserguilds SET guildId=:guildId, userId=:userId, permissionInt=:permInt WHERE guildId=:guildId AND userId=:userId");
            $guildUpdate->execute([
                'permInt' => $an['permissions'],
                ':guildId' => $guildId,
                ':userId' => $userId,
            ]);
        } else {
            $guildInsert = DB->prepare(
                "INSERT INTO permissionsuserguilds (guildId, userId, permissionInt) VALUES (?, ?, ?)"
            );
            $guildInsert->execute([
                $guildId,
                $userId,
                $an['permissions'],
            ]);
        }

        return [$guildId, $guildName, $guildIcon];
    }
} else {
    //
    // Permission Guild Database
    $guild = DB->prepare("SELECT userId, guildId FROM permissionsuserguilds WHERE userId=?");
    $guild->execute([
        $_SESSION['userId']
    ]);
    $arrPerm = $guild->fetchAll(PDO::FETCH_ASSOC);
    exit;
    function dbGet($an)
    {
        $guildId = $an['guildId'];

        //
        // Guild Database
        $guildSelect = DB->prepare("SELECT guildName, guildId, guildIcon FROM guilds WHERE guildId=? AND botIn=?");
        $guildSelect->execute([
            $guildId,
            1
        ]);
        $guildSelectResult = $guildSelect->fetch(PDO::FETCH_ASSOC);

        //
        // Return the correct result
        $guildSelectResult ?
            $guildId = $guildSelectResult['guildId'] :
            $guildId = null;
        $guildSelectResult ?
            $guildId = $guildSelectResult['guildName'] :
            $guildName = null;
        $guildSelectResult ?
            $guildId = $guildSelectResult['guildIcon'] :
            $guildIcon = null;

        return [$guildSelectResult, $guildId, $guildName, $guildIcon];
    }
}

$pageDesc = 'Select the server to edit...';
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
    <main id="page">
        <h1>
            Select a server
        </h1>
        <div id="servers">
            <?php
            if ($arrPerm) {
                foreach ($arrPerm as $an) {
                    function_exists("apiGet") ?
                        [$guildId, $guildName, $guildIcon] = apiGet($an, $userId) :
                        [$guildSelectResult, $guildId, $guildName, $guildIcon] = dbGet($an);

                    //
                    // Check if the server has an icon.
                    if ($guildIcon == null) {
                        $url = '/assets/images/external_logos/discord.png';
                        continue;
                    }

                    //
                    // Set the format of the icon (gif or png)
                    $format = str_starts_with($guildIcon, 'a_') ?
                        '.gif' :
                        '.png';

                    $url = "https://cdn.discordapp.com/icons/$guildId/$guildIcon$format";
            ?>
                    <a class="server_info" href="/dashboard/guild?id=<?= $guildId ?>">
                        <p class="server_name">
                            <?= $guildName ?>
                        </p>
                        <img class="server_icon" src="<?= $url ?>">
                    </a>
                <?php
                }
            } else {
                ?>
                <p id="noServer">
                    You currently manage <b><span style="color: red">0</span> servers</b>
                </p>
            <?php
            }
            ?>
        </div>
        <?php
        require('../private_html/essential/footer.php');
        ?>
    </main>
</body>

</html>