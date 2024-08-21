<?php
if (!array_key_exists('userId', $_SESSION)) {
    toDashboard();
}

$userId = $_SESSION['userId'];

$user = DB->prepare("SELECT nextRefresh, accessToken FROM users WHERE userId=:userId");
$user->execute([':userId' => $_SESSION['userId']]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

if ($userResult['nextRefresh'] < time()) {
    $refreshCooldown = DB->prepare("UPDATE users SET nextRefresh=:nextRefresh WHERE userId=:userId ");
    $refreshCooldown->execute([':userId' => $userId, ':nextRefresh' => time() + 60]);

    $url = API_ENDPOINT . 'users/@me/guilds';
    $request = curl_init();

    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $userResult['accessToken'],
    ]);
    $response = curl_exec($request);
    $decodeResponse = json_decode($response, true);

    $arrPerm = array_filter($decodeResponse, function ($permission) {
        return ($permission['permissions'] & 0x20) === 0x20;
    });

    function apiGet($an, $userId)
    {
        $guildName = $an['name'];
        $guildId = $an['id'];
        $guildIcon = $an['icon'];

        $guildFind = DB->prepare("SELECT guildId FROM guilds WHERE guildId=:guildId");
        $guildFind->execute(
            [
                ':guildId' => $guildId
            ],
        );
        $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

        if ($guildFindResult) {
            $guildUpdate = DB->prepare("UPDATE guilds SET guildName=:guildName, guildIcon=:guildIcon WHERE guildId=:guildId");
            $guildUpdate->execute(
                [
                    ':guildName' => $guildName,
                    ':guildId' => $guildId,
                    ':guildIcon' => $guildIcon,
                ],
            );
        } else {
            $guildInsert = DB->prepare("INSERT INTO guilds (guildName, guildId, guildIcon) VALUES (:guildName, :guildId, :guildIcon)");
            $guildInsert->execute(
                [
                    ':guildName' => $guildName,
                    ':guildId' => $guildId,
                    ':guildIcon' => $guildIcon,
                ],
            );
        }

        $guildPermissionFind = DB->prepare("SELECT guildId, userId FROM permissionsuserguilds WHERE guildId=:guildId and userId=:userId");
        $guildPermissionFind->execute(
            [
                ':guildId' => $guildId,
                ':userId' => $userId
            ]
        );
        $guildPermissionFindResult = $guildPermissionFind->fetch(PDO::FETCH_ASSOC);

        if ($guildPermissionFindResult) {
            $guildUpdate = DB->prepare("UPDATE 
            permissionsuserguilds 
            SET 
            guildId=:guildId, userId=:userId, permissionInt=:permissionInt 
            WHERE 
            guildId=:guildId and userId=:userId");
            $guildUpdate->execute(
                [
                    ':guildId' => $guildId,
                    ':userId' => $userId,
                    ':permissionInt' => $an['permissions'],
                ],
            );
        } else {
            $guildInsert = DB->prepare("INSERT INTO permissionsuserguilds (guildId, userId, permissionInt) VALUES (:guildId, :userId, :permissionInt)");
            $guildInsert->execute(
                [
                    ':guildId' => $guildId,
                    ':userId' => $userId,
                    ':permissionInt' => $an['permissions'],
                ],
            );
        }

        return [$guildId, $guildName, $guildIcon];
    }
} else {
    $guild = DB->prepare("SELECT userId, guildId FROM permissionsuserguilds WHERE userId=:userId");
    $guild->execute(
        [
            ':userId' => $_SESSION['userId']
        ]
    );
    $arrPerm = $guild->fetchAll(PDO::FETCH_ASSOC);

    function dbGet($an)
    {
        $guildId = $an['guildId'];

        $guildSelect = DB->prepare("SELECT guildName, guildId, guildIcon FROM guilds WHERE guildId=:guildId");
        $guildSelect->execute(
            [
                ':guildId' => $guildId
            ]
        );
        $guildSelectResult = $guildSelect->fetch(PDO::FETCH_ASSOC);

        $guildId = $guildSelectResult['guildId'];
        $guildName = $guildSelectResult['guildName'];
        $guildIcon = $guildSelectResult['guildIcon'];

        return [$guildId, $guildName, $guildIcon];
    }
}

?>

<!DOCTYPE html>

<html lang="en-US">

<head>
    <title>
        Cheryl - Servers
    </title>
    <meta content="Cheryl - Servers" property="og:title" />
    <meta content="Select the server to edit." property="og:description" />
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
            Select server
        </h1>
        <div id="servers">
            <div id="server_icon_list">
                <?php
                foreach ($arrPerm as $an) {
                    if (function_exists("apiGet")) {
                        [$guildId, $guildName, $guildIcon] = apiGet($an, $userId);
                    } else {
                        [$guildId, $guildName, $guildIcon] = dbGet($an);
                    }

                    if (!$guildIcon) {
                        $url = '/assets/images/discord-logo.png';
                    } else {
                        if (str_starts_with($guildIcon, 'a_')) {
                            $format = '.gif';
                        } else {
                            $format = '.png';
                        }

                        $url = "https://cdn.discordapp.com/icons/$guildId/$guildIcon$format";
                    }
                ?>
                    <a class="server_info" href="/dashboard/guild?id=<?= $guildId ?>">
                        <p class="server_name">
                            <?= $guildName ?>
                        </p>
                        <img class="server_icon" src="<?= $url ?>">
                    </a>
                <?php
                }
                ?>
            </div>
        </div>
        <div id="space"></div>
    </main>
</body>

</html>