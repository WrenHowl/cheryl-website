<?php
if (!array_key_exists('code', $_GET)) {
    header('Location: /');
} else if (array_key_exists('access_token', $_SESSION) and $_SESSION['access_token'] != null) {
    header('Location: /dashboard/servers');
}

$rHash = bin2hex(random_bytes(18));

$url = API_ENDPOINT . 'oauth2/token';
$httpField = [
    'grant_type' => 'authorization_code',
    'client_id' => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'code' => $_GET["code"],
    'redirect_uri' => REDIRECT_URL,
    'state' => bin2hex(random_bytes(18)),
];

$decodeResponse = apiRequest($httpField, $url);

//
// Check user info
$url = API_ENDPOINT . 'oauth2/@me';
$request = curl_init();

curl_setopt($request, CURLOPT_URL, $url);
curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
curl_setopt($request, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $decodeResponse['access_token'],
]);
$response = curl_exec($request);
$userResponse = json_decode($response, true);

$user_name = $userResponse['user']['username'];
$user_id = $userResponse['user']['id'];
$avatar = $userResponse['user']['avatar'];
$userGlobalName = $userResponse['user']['global_name'];

$findUser = DB->prepare("SELECT * FROM users WHERE id=?");
$findUser->execute([
    $user_id
]);
$findUserResult = $findUser->fetchColumn();

if (!$findUserResult) {
    $createUser = DB->prepare("INSERT INTO users (name, id, token_access, token_refresh, token_expireAt, global_name, api_cooldown, avatar) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $createUser->execute(
        [
            $user_name,
            $user_id,
            $decodeResponse['access_token'],
            $decodeResponse['refresh_token'],
            $decodeResponse['expires_in'] + time(),
            $userGlobalName,
            time() + 60,
            $avatar,
        ],
    );
} else {
    $createUser = DB->prepare("UPDATE users 
    SET name=?, token_access=?, token_refresh=?, token_expireAt=?, global_name=?, api_cooldown=?, avatar=? 
    WHERE id=?");
    $createUser->execute(
        [
            $user_name,
            $decodeResponse['access_token'],
            $decodeResponse['refresh_token'],
            $decodeResponse['expires_in'] + time(),
            $userGlobalName,
            time() + 60,
            $avatar,
            $user_id
        ]
    );
}

$_SESSION['user_id'] = $user_id;

header('Location: /');
die;
