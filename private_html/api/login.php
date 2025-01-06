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

$userName = $userResponse['user']['username'];
$userId = $userResponse['user']['id'];
$avatar = $userResponse['user']['avatar'];
$userGlobalName = $userResponse['user']['global_name'];

$findUser = DB->prepare("SELECT * FROM users WHERE userId=?");
$findUser->execute([
    $userId
]);
$findUserResult = $findUser->fetchColumn();

if (!$findUserResult) {
    $createUser = DB->prepare("INSERT INTO users (userName, userId, accessToken, refreshToken, expireAt, globalName, nextRefresh, avatar) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $createUser->execute(
        [
            $userName,
            $userId,
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
    SET userName=?, accessToken=?, refreshToken=?, expireAt=?, globalName=?, nextRefresh=?, avatar=? 
    WHERE userId=?");
    $createUser->execute(
        [
            $userName,
            $decodeResponse['access_token'],
            $decodeResponse['refresh_token'],
            $decodeResponse['expires_in'] + time(),
            $userGlobalName,
            time() + 60,
            $avatar,
            $userId
        ]
    );
}

$_SESSION['userId'] = $userId;

header('Location: /');
die;
