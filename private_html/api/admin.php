<?php
//
// User Result
$user = DB->prepare("SELECT * FROM users WHERE user_id=?");
$user->execute([
    $user_id
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

$role = $userResult['role'];

//
// Check if the user requesting is a developer.
if (!array_key_exists('user_id', $_SESSION) && $role != 1 || !$userResult) {
    header('Location: /');
    die;
}

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = htmlspecialchars($_POST['message']);

    if (strpos($message, 'url')) {
        //
        // a href
        $message = str_replace('url=', 'a href=', $message);
        $message = str_replace('/url', '/a', $message);
    }

    $alertFind = DB->prepare("SELECT * FROM alert WHERE message=?");
    $alertFind->execute([
        $message
    ]);
    $alertFindResult = $alertFind->fetch(PDO::FETCH_ASSOC);

    if (!$alertFindResult) {
        $alert = DB->prepare("INSERT INTO alert (message, user_id, importance) VALUES (?, ?, ?)");
        $alert->execute([
            $message,
            $user_id,
            $_POST['importance']
        ]);
    }
}

header("Content-Type: application/json");
header('Location: /admin');
die;
