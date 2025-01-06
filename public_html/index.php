<?php
require '../private_html/essential/discord.php';
require '../private_html/essential/secret.php';

session_start();

if (array_key_exists('userId', $_SESSION)) $userId = $_SESSION['userId'];

// General
switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case '/':
        require "../private_html/home.php";
        break;
    case '/commands':
        require "../private_html/commands.php";
        break;
    case '/settings':
        require "../private_html/settings.php";
        break;
    case '/admin':
        require "../private_html/admin.php";
        break;
    case '/guidelines':
        require "../private_html/legal/guidelines.php";
        break;
    case '/privacy':
        require "../private_html/legal/privacy.php";
        break;
    case '/dashboard/guild':
        require "../private_html/dashboard/guild.php";
        break;
    case '/dashboard/servers':
        require "../private_html/dashboard/servers.php";
        break;
    case '/dashboard/guild':
        require "../private_html/dashboard/guild.php";
        break;
    case '/leaderboard':
        require "../private_html/leaderboard.php";
        break;
    case '/api/user/settings':
        require "../private_html/api/user/settings.php";
        break;
    case '/api/login':
        require "../private_html/api/login.php";
        break;
    case '/api/logout':
        require "../private_html/api/logout.php";
        break;
    case '/api/moderation':
        require "../private_html/api/moderation.php";
        break;
    case '/api/dashboard/guild':
        require "../private_html/api/dashboard/guild.php";
        break;
    case '/api/admin/alert':
        require "../private_html/api/admin/alert.php";
        break;
    default:
        require "../private_html/error/error.php";
        break;
}
