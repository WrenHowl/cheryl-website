<?php
function apiRequest($httpField, $url)
{
    $request = curl_init();

    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_POST, 1);
    curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($httpField));
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($request, CURLOPT_HTTPHEADER, [
        'Content-Type' => 'application/x-www-form-urlencoded'
    ]);

    $response = curl_exec($request);
    curl_close($request);

    return $decodeResponse = json_decode($response, true);
};

function discordServers($user_id)
{
    // Get the refresh and access token
    $user = DB->prepare("SELECT * FROM users WHERE id=?");
    $user->execute([
        $_SESSION['user_id']
    ]);
    $userResult = $user->fetch(PDO::FETCH_ASSOC);

    // Check if the next refresh is allowed to get fresh data from discord
    if ($userResult['api_cooldown'] < time()) {
        // Update the next refresh to limit the user again
        $refreshCooldown = DB->prepare("UPDATE users SET api_cooldown=? WHERE id=?");
        $refreshCooldown->execute([
            time() + 60,
            $user_id,
        ]);

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

        // Get information about his permissions on the guilds
        $arrayPerm = array_filter($decodeResponse, function ($permission) {
            return ($permission['permissions'] & 0x20) === 0x20;
        });

        foreach ($arrayPerm as $guild) {
            $id = $guild['id'];

            // Update the guild information to make it up-to-date with Discord.
            $guildUpdate = DB->prepare("INSERT INTO guilds (`name`, `avatar`, `id`) VALUES (:username, :avatar, :id) ON DUPLICATE KEY UPDATE `name`=:username, `avatar`=:avatar");
            $guildUpdate->execute([
                ":id" => $id,
                ":username" => $guild['name'],
                ":avatar" => $guild['icon'],
            ]);

            // Update the permission of the user to make it up-to-date with Discord.
            $guildInsert = DB->prepare("INSERT INTO guild_userPermission (`guild_id`, `user_id`, `permissions`) VALUES (:guild_id, :user_id, :permissions) ON DUPLICATE KEY UPDATE `permissions`=:permissions");
            $guildInsert->execute([
                ":guild_id" => $id,
                ":user_id" => $user_id,
                ":permissions" => $guild['permissions'],
            ]);
        }
    }

    // Permission Guild Database
    $guild = DB->prepare("SELECT * FROM guild_userPermission WHERE user_id=?");
    $guild->execute([
        $user_id
    ]);
    $guildFind = $guild->fetchAll(PDO::FETCH_ASSOC);

    return [$guildFind];
}
