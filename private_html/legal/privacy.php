<?php
$pageDesc = 'View the privacy policy of Cheryl.';
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
        <div class="guidelines top">
            <h1>
                PRIVACY POLICY
            </h1>
            <span class="guidelines info">
                These policy are subject to change in the future, any modifications will be fully disclosed.
            </span>
        </div>
        <div class="guidelines bottom">
            <h2>
                Message Content Data Tracking
            </h2>
            <div class="guidelines rules">
                <span class="guidelines name">
                    1. How do we use your information?
                </span>
                <div class="guidelines note list">
                    <span>
                        We never store sensitive information in our database. We only save the following to improve the user experience :
                    </span>
                    <span class="list padding">
                        → Display Name
                        <br>
                        → Username
                        <br>
                        → User ID
                        <br>
                        → User Avatar
                    </span>
                </div>
                <span class="guidelines name">
                    2. How to opt-out?
                </span>
                <div class="guidelines note list">
                    <span>
                        To opt-out of this feature, you will need to go in your <a href="/settings">account settings</a> and simply disable it.
                        Opting out of this feature limits your ability to use the bot. The feature that you will lose when opting-out :
                    </span>
                    <span class="list padding">
                        → Using message commands
                        <br>
                        → Using the level system
                    </span>
                </div>
            </div>
        </div>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>