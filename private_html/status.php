<?php
$status = DB->prepare("SELECT * FROM status");
$status->execute();
$statusData = $status->fetchAll(PDO::FETCH_ASSOC);

$statusAlert = time() + 10 < strtotime($statusData[0]['timestamp'] . ' +2 hours') ?
    true :
    false;

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
        <?php
        if ($statusAlert === true) {
        ?>
            <span>
                <img src="/assets/images/discord/online.png"> Online
            </span>
        <?php
        } else {
        ?>
            <span>
                <img src="/assets/images/discord/idle.png"> Not Responding
            </span>
        <?php
        }
        ?>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>