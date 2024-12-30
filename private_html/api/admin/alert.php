<?php
//
// Check if user is logged in.
array_key_exists('userId', $_SESSION) ?
    $userId = $_SESSION['userId'] :
    header('Location: /');

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
if ($role != 1) header('Location: /');

if ($userResult['nextRefresh'] > time()) return;

switch ($_SERVER['REQUEST_METHOD']) {
    case 'POST':
        $user = DB->prepare("INSERT INTO alert (message, userId) VALUES (?, ?)");
        $user->execute([
            $_POST['message'],
            $userId
        ]);

        break;
}
