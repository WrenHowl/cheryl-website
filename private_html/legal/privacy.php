<?php
$pageDesc = 'View the privacy policy of Cheryl.';
?>

<!DOCTYPE html>

<?php
require '../private_html/all/all.php';
require '../private_html/all/style.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main>
        <div class="guidelines-options">
            <div class="guidelines-informations">
                <h1>
                    PRIVACY POLICY
                </h1>
                <p>
                    These policy are subject to change in the future, any modifications will be fully disclosed.
                </p>
            </div>
            <div class="guidelines">
                <h2>
                    Message Content Data Tracking
                </h2>
                <h3>
                    1. How do we use your information?
                </h3>
                <p>
                    We never store sensitive information in our database. We only save the following to improve the user experience :
                    <br>
                <ul>
                    <li>
                        Display Name
                    </li>
                    <li>
                        Username
                    </li>
                    <li>
                        User ID
                    </li>
                    <li>
                        User Avatar
                    </li>
                </ul>
                </p>
                <h3>
                    2. How to opt-out?
                </h3>
                <p>
                    To opt-out of this feature, you will need to go in your <a href="/settings">account settings</a> and simply disable it.
                    Opting out of this feature limits your ability to use the bot. The feature that you will lose when opting-out :
                <ul>
                    <li>
                        Using message commands
                    </li>
                    <li>
                        Using the level system
                    </li>
                </ul>
                </p>
            </div>
        </div>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>