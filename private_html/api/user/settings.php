<?php
$requestBody = file_get_contents('php://input');
$request = (array) json_decode($requestBody);

$response = [
    'status' => 'Failed'
];

var_dump($request);
exit;

if (!empty($request)) {
    $listValid = [
        'action_status' => 0,
        'level_rankup' => 0,
        'data_messageContent' => 0
    ];

    foreach ($request as $key => $value) {
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
    ]);
}

header("Content-Type: application/json");
die;
