<?php
require '../private_html/assets/php/discord.php';
require '../private_html/assets/php/secret.php';

session_start();

expireAt();

switch (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)) {
    case ('/'):
        require "../private_html/home.php";
        break;
    case ('/dashboard/api'):
        require "../private_html/dashboard/api.php";
        break;
    case ('/dashboard/guild'):
        require "../private_html/dashboard/guild.php";
        break;
    case ('/dashboard/servers'):
        require "../private_html/dashboard/servers.php";
        break;
    case ('/dashboard/guild'):
        require "../private_html/dashboard/guild.php";
        break;
    case ('/commands'):
        require "../private_html/commands.php";
        break;
    case ('/commissions'):
        require "../private_html/commissions.php";
        break;
    case ('/commissions/user'):
        require "../private_html/commissions/user.php";
        break;
    case ('/settings'):
        require "../private_html/settings.php";
        break;
}
