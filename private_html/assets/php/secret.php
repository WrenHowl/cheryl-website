<?php
define('API_ENDPOINT', "https://discord.com/api/");
define('CLIENT_ID', "940369423125061633");
define('CLIENT_SECRET', "OXpCUHoZja270nsz4bxl0JpZBQvIgL3S");
define('DATABASE_HOST', "localhost");
define('DATABASE_USERNAME', "cherylbo_admin");
define('DATABASE_PASSWORD', "yE9cfeSWtwzK95m");
define('DATABASE_NAME', "cherylbo_servers");

define('DB', new PDO("mysql:host=" . DATABASE_HOST . "; dbname=" . DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD));
DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$domain = $_SERVER['HTTP_HOST'];

if ($domain == 'localhost') {
    $protocole = 'http';
} else {
    $protocole = 'https';
}

define('REDIRECT_ADDBOT', "https://discord.com/oauth2/authorize?client_id=940369423125061633&permissions=1634705210583&response_type=code&redirect_uri=$protocole%3A%2F%2F$domain%2Fdashboard%2Fapi&integration_type=0&scope=bot+guilds+identify");
define('REDIRECT_URL', "$protocole://$domain/dashboard/api");
