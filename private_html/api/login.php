<?php
if (!array_key_exists('code', $_GET)) {
    toDashboard();
} else if (array_key_exists('access_token', $_SESSION) and $_SESSION['access_token'] != null) {
    header('Location: /dashboard/servers');
}

$url = API_ENDPOINT . 'oauth2/token';
$httpField = [
    'grant_type' => 'authorization_code',
    'client_id' => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'code' => $_GET["code"],
    'redirect_uri' => REDIRECT_URL
];

$decodeResponse = apiRequest($httpField, $url);

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

$findUser = DB->prepare("SELECT userId FROM users WHERE userId=:userId");
$findUser->execute(
    [
        ':userId' => $userId
    ]
);
$findUserResult = $findUser->fetchColumn();

if (!$findUserResult) {
    $createUser = DB->prepare("INSERT INTO 
    users (userName, userId, accessToken, refreshToken, expireAt, globalName, nextRefresh, avatar) 
    VALUES 
    (:userName, :userId, :accessToken, :refreshToken, :expireAt, :globalName, :nextRefresh, :avatar) 
    ");
    $createUser->execute(
        [
            ':userName' => $userName,
            ':userId' => $userId,
            ':accessToken' => $decodeResponse['access_token'],
            ':refreshToken' => $decodeResponse['refresh_token'],
            ':expireAt' => $decodeResponse['expires_in'] + time(),
            ':globalName' => $userGlobalName,
            ':nextRefresh' => time() + 60,
            ':avatar' => $avatar,
        ],
    );
} else {
    $createUser = DB->prepare("UPDATE 
    users 
    SET 
    userName=:userName, userId=:userId, accessToken=:accessToken, refreshToken=:refreshToken, expireAt=:expireAt, globalName=:globalName, nextRefresh=:nextRefresh, avatar=:avatar 
    WHERE 
    userId=:userId ");
    $createUser->execute(
        [
            ':userName' => $userName,
            ':userId' => $userId,
            ':accessToken' => $decodeResponse['access_token'],
            ':refreshToken' => $decodeResponse['refresh_token'],
            ':expireAt' => $decodeResponse['expires_in'] + time(),
            ':globalName' => $userGlobalName,
            ':nextRefresh' => time() + 60,
            ':avatar' => $avatar,
        ]
    );
}

$_SESSION['userId'] = $userId;

header('location: /');
die;
