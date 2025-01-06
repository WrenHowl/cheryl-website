<?php
if (!array_key_exists('userId', $_SESSION)) {
    header('location: /');
    die;
}

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "ERROR: The request isn't a POST.";
    return;
}

//
// Check for POST request.
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $listValid = [
        'action_enabled',
        'action_nsfw',
        'level_rankup'
    ];

    if (empty($_POST)) {
        foreach ($listValid as $value) {
            $userDataCreate = DB->prepare("UPDATE user_settings SET `$value`=? WHERE userId=?");
            $userDataCreate->execute([
                0,
                $userId
            ]);
        }
    } else {
        $tempArray = [];

        foreach ($_POST as $key => $value) {
            $name = htmlspecialchars($key);
            if (!in_array($name, $listValid)) return;

            $value = htmlspecialchars($value);
            $value == 'on' ?
                $value = 1 :
                $value = 0;

            array_push($tempArray, $key);

            $userDataCreate = DB->prepare("UPDATE user_settings SET `$name`=? WHERE userId=?");
            $userDataCreate->execute([
                $value,
                $userId
            ]);
        }

        $tempArray = array_diff($listValid, $tempArray);

        if ($tempArray == true) {
            foreach ($tempArray as $value) {
                $userDataCreate = DB->prepare("UPDATE user_settings SET `$value`=? WHERE userId=?");
                $userDataCreate->execute([
                    0,
                    $userId
                ]);
            }
        }
    }
}

echo 'Success.';
header('Location: /settings');
die;
