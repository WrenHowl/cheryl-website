<?php
function apiRequest($httpField, $url)
{
    $request = curl_init();

    curl_setopt($request, CURLOPT_URL, $url);
    curl_setopt($request, CURLOPT_POST, 1);
    curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($httpField));
    curl_setopt($request, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($request);
    curl_close($request);

    return $decodeResponse = json_decode($response, true);
};

function expireAt()
{
    if (array_key_exists('expires_at', $_SESSION) and $_SESSION['expires_at'] < time()) {
        $url = API_ENDPOINT . 'oauth2/token';
        $httpField = [
            'grant_type' => 'refresh_token',
            'client_id' => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'refresh_token' => $_SESSION['refresh_token']
        ];

        apiRequest($httpField, $url);
    };
}
