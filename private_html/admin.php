<?php
if (!array_key_exists('userId', $_SESSION)) {
    header('location: /');
    die;
}

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
if ($role != 1 || !$userResult) header('Location: /');

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $message = htmlspecialchars($_POST['message']);

    if (strpos($message, '<url=')) {
        $message = str_replace('<url=', '<a href=', $message);
        $message = str_replace('</url>', '</a>', $message);
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

$pageDesc = 'Secret admin panel.';
?>

<!DOCTYPE html>

<?php
require 'all/all.php';
require 'all/style.php';
?>

<body>
    <header>
        <a href="/">
            ← Go Back
        </a>
    </header>
    <main id="page">
        <form method="POST" enctype="application/x-www-form-urlencoded">
            <div class="setting-list">
                <div class="setting">
                    Alert Message
                    <textarea name="message" rows="5" placeholder="Type a new alert to be displayed for everyone on top of the screen."></textarea>
                    <div>
                        <input type="radio" id="lowImportance" name="importance" value="0">
                        <label for="lowImportance">
                            Low
                        </label>
                        <input type="radio" id="mediumImportance" name="importance" value="1">
                        <label for="lowImportance">
                            Medium
                        </label>
                        <input type="radio" id="highImportance" name="importance" value="2">
                        <label for="lowImportance">
                            High
                        </label>
                    </div>
                </div>
            </div>
            <input type="submit" value="Send">
        </form>
    </main>
</body>

</html>