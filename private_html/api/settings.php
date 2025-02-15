<?php
header("Content-Type: application/json");

// Check for POST request.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    var_dump([
        "error" => "The request isn't a POST."
    ]);
    die;
}

// Check if the settings is for the server or user.
switch (true) {
    case isset($guildMatches[3]):
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
        $settingType = 'guild_settings';
        $id = $guildMatches[3];
        $location = "/dashboard/guild/$guildMatches[3]";

        break;
    case isset($userMatches[3]):
        $listValid = [
            'action_status' => 0,
            'level_rankup' => 0,
            'data_messageContent' => 0
        ];
        $skipOverwrite = [
            'action_status'
        ];
        $settingType = 'user_settings';
        $id = $userMatches[3];
        $location = "/settings";

        break;
}

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

$guildDataCreate = DB->prepare("UPDATE $settingType SET " . implode(', ', $data) . " WHERE id=?");
$guildDataCreate->execute([
    ...$sets,
    $id
]);

header("Location: $location");
die;
