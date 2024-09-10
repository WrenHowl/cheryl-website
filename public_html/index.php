<?php
require '../private_html/assets/php/discord.php';
require '../private_html/assets/php/secret.php';

session_start();

expireAt();

// General
switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case ('/'):
        require "../private_html/home.php";
        break;
    case ('/guidelines'):
        require "../private_html/guidelines.php";
        break;
    case ('/tos'):
        require "../private_html/tos.php";
        break;
    case ('/privacy'):
        require "../private_html/privacy.php";
        break;
    case ('/commands'):
        require "../private_html/commands.php";
        break;
    case ('/commissions'):
        require "../private_html/commissions.php";
        break;
    case ('/settings'):
        require "../private_html/settings.php";
        break;
}

// Dashboard
switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case ('/dashboard/guild'):
        require "../private_html/dashboard/guild.php";
        break;
    case ('/dashboard/servers'):
        require "../private_html/dashboard/servers.php";
        break;
    case ('/dashboard/guild'):
        require "../private_html/dashboard/guild.php";
        break;
}

// API
switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case ('/api/settings'):
        require "../private_html/api/settings.php";
        break;
    case ('/api/login'):
        require "../private_html/api/login.php";
        break;
    case ('/api/moderation'):
        require "../private_html/api/moderation.php";
        break;
    case ('/api/dashboard/guild'):
        require "../private_html/api/dashboard/guild.php";
        break;
}

//  Commission
switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case ('/commissions/user'):
        require "../private_html/commissions/user.php";
        break;
}
