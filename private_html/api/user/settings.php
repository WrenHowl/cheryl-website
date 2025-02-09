<?php
if (!array_key_exists('userId', $_SESSION)) {
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
        'action_enabled' => 0,
        'action_nsfw' => 0,
        'level_rankup' => 0,
        'data_messageContent' => 0
    ];

    foreach ($_POST as $key => $value) {
        if (!array_key_exists($key, $listValid)) continue;
        if ($value === 'on') $value = 1;

        if (isset($listValid[$key])) $listValid[$key] = $value;
    }

    $data = [];
    $sets = [];

    foreach ($listValid as $key => $value) {
        if (!array_key_exists($key, $listValid)) continue;

        $data[] = "$key=?";
        $sets[] = $value;
    }

    $guildDataCreate = DB->prepare("UPDATE user_settings SET " . implode(', ', $data) . " WHERE id=?");
    $guildDataCreate->execute([
        ...$sets,
        $userMatches[3]
    ]);
}

header("Content-Type: application/json");
header('Location: /settings');
die;
