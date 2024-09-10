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
    // Commission Result
    switch ($_SERVER['REQUEST_METHOD']) {
        case 'POST':
            $name = strval($_POST['name']);
            $value = intval($_POST['value']);

            $userCommission = DB->prepare("UPDATE commissions SET $name=:postValue WHERE `accountId`=:accountId");
            $userCommission->execute(
                [
                    ':postValue' => $value,
                    ':accountId' => $accountId,
                ]
            );

            break;
        case 'GET':
            function comUpdate($accountId)
            {
                $userCommission = DB->prepare("SELECT `status`, `pricing` FROM commissions WHERE accountId=:accountId");
                $userCommission->execute(
                    [
                        ':accountId' => $accountId,
                    ]
                );
                return $userComissionResult = $userCommission->fetch(PDO::FETCH_ASSOC);
            }

            $userComissionResult = comUpdate($accountId);

            if ($userComissionResult) {
                $status = $userComissionResult['status'];
                $pricing = $userComissionResult['pricing'];
            } else {
                $userCommission = DB->prepare("INSERT INTO commissions (accountId) VALUES (:accountId)");
                $userCommission->execute(
                    [
                        ':accountId' => $accountId,
                    ]
                );

                $userComissionResult = comUpdate($accountId);
            }

            // Response
            $jsonData = array(
                'accountId' => $accountId,
                'status' => $status,
                'pricing' => $pricing,
            );

            header('Content-Type: application/json');
            echo json_encode($jsonData);
            break;
    }
}
