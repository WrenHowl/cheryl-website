<?php
$user = DB->prepare("SELECT * FROM users WHERE id=?");
$user->execute([
    $user_id
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

if (!$userResult || $userResult['role'] >= 1 || $_SERVER['REQUEST_METHOD'] !== "POST") {

    foreach ($_POST as $key => $value) {
        $guildDataCreate = DB->prepare("UPDATE guilds SET review_status=? WHERE id=?");
        $guildDataCreate->execute([
            $value,
            $key
        ]);
    }
}

header("Content-Type: application/json");
header('Location: /settings');
die;
