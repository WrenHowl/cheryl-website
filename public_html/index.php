<?php
require '../private_html/essential/discord.php';
require '../private_html/essential/secret.php';

session_start();

if (array_key_exists('userId', $_SESSION)) $userId = $_SESSION['userId'];

preg_match('~((^.*)/guild)/(\d+)$~', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $matches);

isset($matches[1]) ?
    $url = $matches[1] :
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$version = '?v=1.2.14';
$language = [
    'en' => 'English',
    'fr' => 'Français',
];

$error = false;

// General
switch ($url) {
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
    case '/staff':
        require "../private_html/staff.php";
        break;
    case '/guidelines':
        require "../private_html/legal/guidelines.php";
        break;
    case '/privacy':
        require "../private_html/legal/privacy.php";
        break;
    case '/dashboard':
        require "../private_html/dashboard.php";
        break;
    case '/dashboard/guild':
        isset($matches[3]) ?
            require "../private_html/dashboard/guild.php" :
            $error = true;
        break;
    case '/leaderboard':
        require "../private_html/leaderboard.php";
        break;
    case '/leaderboard/guild':
        isset($matches[3]) ?
            require "../private_html/leaderboard.php" :
            $error = true;
        break;
    case '/api/user/settings':
        require "../private_html/api/user/settings.php";
        break;
    case '/login':
        require "../private_html/api/login.php";
        break;
    case '/logout':
        require "../private_html/api/logout.php";
        break;
    case '/api/moderation':
        require "../private_html/api/moderation.php";
        break;
    case '/api/guild':
        isset($matches[3]) ?
            require "../private_html/api/dashboard/guild.php" :
            $error = true;
        break;
    case '/api/admin':
        require "../private_html/api/admin.php";
        break;
    default:
        $error = true;
        break;
}

if ($error === true) require "../private_html/error/error.php";
