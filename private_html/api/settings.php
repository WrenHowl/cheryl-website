<?php
if (!array_key_exists('userId', $_SESSION)) {
    header('location: /');
}

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
    function postSend($status, $postName, $accountId)
    {
        $userCommission = DB->prepare("UPDATE commissions SET $postName=:postValue WHERE `accountId`=:accountId");
        $userCommission->execute(
            [
                ':postValue' => $status,
                ':accountId' => $accountId,
            ]
        );
    }

    // Commission Result
    switch ($_SERVER['REQUEST_METHOD']) {
        case ('POST'):
            switch ($_POST) {
                case isset($_POST['commissionStatus']):
                    $status = intval($_POST['commissionStatus']);

                    postSend($status, 'status', $accountId);
                    break;
                case isset($_POST['pricingStatus']):
                    $status = intval($_POST['pricingStatus']);

                    postSend($status, 'pricing', $accountId);
                    break;
                case isset($_POST['slot']):
                    $status = intval($_POST['slot']);

                    postSend($status, 'slot', $accountId);
                    break;
                case isset($_POST['maxSlot']):
                    $status = intval($_POST['maxSlot']);

                    postSend($status, 'maxSlot', $accountId);
                    break;
            }
            break;
        case ('GET'):
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
