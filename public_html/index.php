<?php
require '../private_html/essential/discord.php';
require '../private_html/essential/secret.php';

session_start();

if (array_key_exists('user_id', $_SESSION)) $user_id = $_SESSION['user_id'];

preg_match('~((^.*)/guild)/(\d+)$~', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $guildMatches);
preg_match('~((^.*)/user)/(\d+)$~', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $userMatches);

switch (true) {
    case isset($guildMatches[1]):
        $requestedUrl = $guildMatches[2];
        break;
    case isset($userMatches[1]):
        $requestedUrl = $userMatches[2];
        break;
    default:
        $requestedUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        break;
};

$version = '?v=1.2.19';
$language = [
    'en' => 'English',
    'fr' => 'Français',
];
$rank = [
    0 => [
        'name' => 'Default',
        'color' => 'gray',
    ],
    1 => [
        'name' => 'Lead Developer',
        'color' => '#ff1e25',
    ],
    2 => [
        'name' => 'Developer',
        'color' => '#ff5b5b'
    ],
    3 => [
        'name' => 'Administrator',
        'color' => '#1668ff'
    ],
    4 => [
        'name' => 'Moderator',
        'color' => '#3b80ff'
    ],
    5 => [
        'name' => 'Helper',
        'color' => '#FFD700'
    ],
];

$error = false;

switch ($requestedUrl) {
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
        isset($guildMatches[3]) ?
            require "../private_html/dashboard/guild.php" :
            require "../private_html/dashboard.php";
        break;
    case '/leaderboard':
        require "../private_html/leaderboard.php";
        break;
    case '/login':
        require "../private_html/api/login.php";
        break;
    case '/logout':
        require "../private_html/api/logout.php";
        break;
    case '/browse':
        require "../private_html/browse.php";
        break;
    case '/api/moderation':
        require "../private_html/api/moderation.php";
        break;
    case '/api/admin':
        require "../private_html/api/admin.php";
        break;
    case '/api/browse':
        require('../private_html/api/browse.php');
        break;
    case '/api/settings':
        isset($guildMatches[3]) || isset($userMatches[3]) ?
            require "../private_html/api/settings.php" :
            $error = true;
        break;
    case '/api':
        isset($guildMatches[3]) ?
            require "../private_html/api/guild.php" :
            $error = true;
    default:
        $error = true;
        break;
}

if ($error === true) require "../private_html/error.php";
