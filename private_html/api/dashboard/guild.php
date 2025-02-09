<?php
if (!array_key_exists('user_id', $_SESSION)) {
    header('location: /');
    die;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "ERROR: The request isn't a POST.";
    return;
}

//
// Check for POST request.
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $listValid = [
        'language' => 'en',
        'action_status' => 0,
        'action_nsfw' => 0,
        'blacklist_status' => 0,
        'level_status' => 0,
        'level_rankup' => 0,
        'welcome_channelDestination' => 0,
        'leaving_channelDestination' => 0
    ];

    $skipOverwrite = [
        'blacklist_status',
        'action_status',
        'welcome_channelDestination',
        'leaving_channelDestination'
    ];

    foreach ($_POST as $key => $value) {
        if (!array_key_exists($key, $listValid)) continue;
        if ($value === 'on' && !array_key_exists($key, $skipOverwrite)) $value = 1;

        if (isset($listValid[$key])) $listValid[$key] = $value;
    }

    $data = [];
    $sets = [];

    foreach ($listValid as $key => $value) {
        if (!array_key_exists($key, $listValid)) continue;

        $data[] = "$key=?";
        $sets[] = $value;
    }

    $guildDataCreate = DB->prepare("UPDATE guild_settings SET " . implode(', ', $data) . " WHERE id=?");
    $guildDataCreate->execute([
        ...$sets,
        $guildMatches[3]
    ]);
}

header("Content-Type: application/json");
header("Location: /dashboard/guild/$guildMatches[3]");
die;
