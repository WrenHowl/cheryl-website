<?php
//
// User Result
$user = DB->prepare("SELECT role, nextRefresh FROM users WHERE userId=?");
$user->execute([
    $userId
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

$role = $userResult['role'];

//
// Check if the user requesting is a developer.
if (!array_key_exists('userId', $_SESSION) && $role != 1 || !$userResult) {
    header('Location: /');
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
        $alert = DB->prepare("INSERT INTO alert (message, userId, importance) VALUES (?, ?, ?)");
        $alert->execute([
            $message,
            $userId,
            $_POST['importance']
        ]);
    }
}

header("Content-Type: application/json");
header('Location: /admin');
die;
