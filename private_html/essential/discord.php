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
