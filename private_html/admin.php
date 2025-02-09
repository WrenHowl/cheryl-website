<?php
if (!array_key_exists('user_id', $_SESSION)) {
    header('location: /');
    die;
}

//
// User Result
$user = DB->prepare("SELECT * FROM users WHERE id=?");
$user->execute([
    $user_id
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

$role = $userResult['role'];

//
// Check if the user requesting is a developer.
if ($role != 1 || !$userResult) header('Location: /');

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
    <main>
        <form method="POST" enctype="application/x-www-form-urlencoded" action="/api/admin">
            <div class="setting-list">
                <div class="setting">
                    Alert Message
                    <textarea name="message" rows="5" placeholder="Type a new alert to be displayed for everyone on top of the screen." required></textarea>
                    <div class="importance-options">
                        <label for="lowImportance">
                            <input type="radio" id="lowImportance" name="importance" value="0" required>
                            Low
                        </label>
                        <label for="mediumImportance">
                            <input type="radio" id="mediumImportance" name="importance" value="1">
                            Medium
                        </label>
                        <label for="highImportance">
                            <input type="radio" id="highImportance" name="importance" value="2">
                            High
                        </label>
                    </div>
                </div>
            </div>
            <div class="save-button-zone">
                <input class="save-button" type="submit" value="Save">
            </div>
        </form>
    </main>
</body>

</html>