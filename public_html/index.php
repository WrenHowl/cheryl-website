<?php
require '../private_html/essential/discord.php';
require '../private_html/essential/secret.php';

session_start();

if (array_key_exists('user_id', $_SESSION)) $user_id = $_SESSION['user_id'];

preg_match('~((^.*)/guild)/(\d+)$~', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $guildMatches);
preg_match('~((^.*)/user)/(\d+)$~', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $userMatches);
$requestedUrl = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) ?? null;
$file = substr(preg_replace('/\/[0-9]+/', '', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)), 1) ?? null;

switch (true) {
    case empty($requestedUrl):
        $requestedUrl = '/home';
        $pageTitle = 'Home';
        $file = 'home';

        break;
    case isset($guildMatches[3]):
        $requestedUrl = $guildMatches[2];

        $guild = DB->prepare("SELECT * FROM guilds WHERE id=?");
        $guild->execute([
            $guildMatches[3]
        ]);
        $guildFind = $guild->fetch(PDO::FETCH_ASSOC);

        $pageTitle = ucfirst(substr($guildMatches[2], 1)) . " → " . $guildFind["name"];

        break;
    case isset($userMatches[3]):
        $requestedUrl = $userMatches[2];

        $user = DB->prepare("SELECT * FROM users WHERE id=?");
        $user->execute([
            $userMatches[3]
        ]);
        $userFind = $user->fetch(PDO::FETCH_ASSOC);

        $pageTitle = ucfirst(substr($guildMatches[2], 1)) . " → " . $guildFind["name"];

        break;
    default:
        $pageTitle = ucwords(preg_replace('/\//', ' ', $file));
        $requestedUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        break;
}

$version = '?v=1.2.25';
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
    case '/home':
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
    case '/weblogs':
        isset($guildMatches[3]) ?
            require "../private_html/weblogs.php" :
            $error = true;
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
        /*switch (implode("/", array_slice($requestedUrl, 1))) {
            case 'servers':
                require "../private_html/browse/servers.php";
                break;
            case 'commissions':
                require "../private_html/browse/commissions.php";
                break;
            default:
                $error = true;
                break;
        }*/

        break;
    case '/api':
        /*switch (implode("/", array_slice($requestedUrl, 1))) {
            case 'admin/review':
                require "../private_html/api/admin/review.php";
                break;
            case 'user/guild':
                require "../private_html/api/user/guild.php";
                break;
            case 'user/settings':
                require "../private_html/api/user/settings.php";
                break;
            case 'browse':
                require('../private_html/api/browse.php');
                break;
            default:
                $error = true;
                break;
        }*/

        break;
    default:
        $error = true;
        break;
}

if ($error === true) require "../private_html/error.php";
