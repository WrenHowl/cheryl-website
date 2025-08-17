<?php
$status = DB->prepare("SELECT * FROM status");
$status->execute();
$statusData = $status->fetchAll(PDO::FETCH_ASSOC);

$statusType = [
    ['Online', '#2eb02e', '/assets/images/discord/online.png'],
    ['Not Responding', '#fba71a', '/assets/images/discord/idle.png'],
    ['Offline', '#747f8e', '/assets/images/discord/offline.png']
];

$statusAlert = time() + 10 < strtotime($statusData[0]['timestamp'] . ' +2 hours') ?
    $statusType[0] :
    $statusType[1];

$pageDesc = 'The current and past status of Cheryl.';
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
        <div class="status">
            <img src="<?= $statusAlert[2] ?>">
            <span style="color: <?= $statusAlert[1] ?>">
                <?= $statusAlert[0] ?>
            </span>
        </div>
        <span>
            The bot updates every 10 seconds. If it doesn't update the website it is no longer responding and most of the commands will not work.
        </span>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>