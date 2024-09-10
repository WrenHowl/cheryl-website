<?php
$userId = $_SESSION['userId'];

// User Result
$user = DB->prepare("SELECT accountId, nextRefresh FROM users WHERE userId=:userId");
$user->execute(
    [
        ':userId' => $userId
    ]
);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

$accountId = $userResult['accountId'];

if ($userResult['nextRefresh'] < time()) {
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $allowedName = [
                'status',
                'pricing'
            ];

            $name = strval($_POST['name']);
            if (!in_array($name, $allowedName)) return;
            $value = intval($_POST['value']);

            $userCommission = DB->prepare("UPDATE loggings SET $name=:postValue WHERE guildId=:guildId");
            $userCommission->execute(
                [
                    ':postValue' => $value,
                    ':guildId' => $accountId,
                ]
            );

            break;
    }
}
