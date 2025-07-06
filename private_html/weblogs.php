<?php
if (!array_key_exists('user_id', $_SESSION)) {
    header('location: /');
    die;
}

// User Result
$user = DB->prepare("SELECT * FROM users WHERE id=?");
$user->execute([
    $user_id
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

$role = $userResult['role'];

// Check if the user requesting is a developer.
if ($role != 1 || !$userResult) header('Location: /');

$guildLogs = DB->prepare("SELECT * FROM guild_deletedMessages WHERE guild_id=?");
$guildLogs->execute([
    $guildMatches[3],
]);
$guildLogsResult = $guildLogs->fetchAll(PDO::FETCH_ASSOC);

$pageDesc = 'Online logs of a guild.';
?>

<!DOCTYPE html>

<?php
require '../private_html/essential/head.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main>
        <div class="seperation">

        </div>
        <div class="logs">
            <?php
            $i = 0;

            foreach ($guildLogsResult as $log) {
                $i++;

                if ($i > 15) break;

                switch ($i) {
                    case 1:
                        $spotLog = ' first';
                        break;
                    case count($guildLogsResult):
                        $spotLog = ' last';
                        break;
                    default:
                        $spotLog = '';
                        break;
                }

                $type = str_starts_with($log['message_content'], 'http') ?
                    1 :
                    0;
            ?>
                <div class="log<?= $spotLog ?>" id="case-<?= $log['id'] ?>">
                    <div id="log-description">
                        <?php
                        $actionLog = $type === 1 ?
                            'Image' :
                            'Message';
                        ?>
                        <span id="log-action">
                            <?= $actionLog ?> Deleted
                        </span>
                        <span id="log-arrow">
                            →
                        </span>
                        <?php
                        if ($type === 0) {
                        ?>
                            <span id="log-content">
                                <?= $log['message_content'] ?>
                            </span>
                        <?php
                        } else {
                        ?>
                            <img src="<?= $log['message_content'] ?>">
                        <?php
                        }
                        ?>
                    </div>
                    <span id="log-timestamp">
                        <?= $log['timestamp'] ?>
                    </span>
                </div>
            <?php
            }
            ?>
        </div>
    </main>
</body>

</html>