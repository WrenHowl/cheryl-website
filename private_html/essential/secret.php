<?php

// Discord API
define('API_ENDPOINT', "https://discord.com/api/");
define('CLIENT_ID', "940369423125061633");
define('CLIENT_SECRET', "OXpCUHoZja270nsz4bxl0JpZBQvIgL3S");

// Database Connection
define('DATABASE_HOST', "localhost");
define('DATABASE_USERNAME', "cherylbo_site");
define('DATABASE_PASSWORD', "aUiJ@W9sKVF6thd");
define('DATABASE_NAME', "cherylbo_servers");

define('DB', new PDO("mysql:host=" . DATABASE_HOST . "; dbname=" . DATABASE_NAME, DATABASE_USERNAME, DATABASE_PASSWORD));
DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Redirect Definition
define('DOMAIN', $_SERVER['HTTP_HOST']);

DOMAIN === 'localhost' ?
    $protocole = 'http' :
    $protocole = 'https';

define('PROTOCOLE', $protocole);
define('REDIRECT_ADDBOT', "https://discord.com/oauth2/authorize?client_id=940369423125061633&permissions=1634705210583&response_type=code&redirect_uri=" . PROTOCOLE . "%3A%2F%2F" . DOMAIN . "%2Fapi%2Flogin&integration_type=0&scope=bot+guilds+identify");
define('REDIRECT_LOGIN', "https://discord.com/oauth2/authorize?client_id=940369423125061633&response_type=code&redirect_uri=" . PROTOCOLE . "%3A%2F%2F" . DOMAIN . "%2Fapi%2Flogin&scope=guilds+identify");
define('REDIRECT_URL', PROTOCOLE . "://" . DOMAIN . "/api/login");
