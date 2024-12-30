<?php
$userId = $_SESSION["userId"];

//
// User Result.
//
$user = DB->prepare("SELECT accountId, nextRefresh FROM users WHERE userId=?");
$user->execute([
    $userId
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);
$accountId = $userResult["accountId"];

// Check if the user that requested it is on cooldown.
// Check if the request is a POST.
// If the array is empty, it return.
//if ($userResult["nextRefresh"] < time()) return;
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "ERROR: The request isn't a POST.";
    return;
} else if (empty($_POST)) {
    echo "ERROR: The request is empty.";
    return;
}

$listValid = [
    'language',
    'action_status',
    'action_nsfw'
];

$name = $_POST['name'];
$value = $_POST['value'];
$guildId = $_POST['guildId'];

if (!in_array($name, $listValid)) return;

$loggingUpdate = DB->prepare("UPDATE loggings SET `$name`=? WHERE guildId=?");
$loggingUpdate->execute([
    $value,
    $guildId,
]);

header("Content-Type: application/json");
echo 'Success.';
