<?php
if (!array_key_exists('user_id', $_SESSION)) {
    header('location: /');
    die;
}

$findUser = DB->prepare("SELECT * FROM users WHERE user_id=?");
$findUser->execute([
    $user_id
]);
$findUserResult = $findUser->fetch();

$url = API_ENDPOINT . 'oauth2/token/revoke';
$httpField = [
    'client_id' => CLIENT_ID,
    'client_secret' => CLIENT_SECRET,
    'token' => $findUserResult['accessToken'],
    'token_type_hint' => 'access_token'
];

$decodeResponse = apiRequest($httpField, $url);

unset($_SESSION['user_id']);
header('Location: /');
die;
