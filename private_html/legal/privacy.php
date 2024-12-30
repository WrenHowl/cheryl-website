<!DOCTYPE html>

<?php
$pageDesc = 'View the privacy policy of Cheryl.';

require '../private_html/all.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main id="page" onscroll="scrollAlert()">
        <div class="guidelinesOption">
            <div class="guidelinesOption_title">
                <h1>
                    PRIVACY POLICY
                </h1>
                <p>
                    These policy are subject to change in the future, any modifications will be fully disclosed.
                </p>
            </div>
            <div class="guidelinesOption_list">
                <h4>
                    Message Content Data Tracking
                </h4>
                <div class="guidelinesOption_numbered">
                    <p>
                        1. How do we use your information?
                    </p>
                    <ul>
                        We never store sensitive information in our database. We only save the following to improve the user experience :
                        <br>
                        <ul>
                            <li>
                                User Name
                            </li>
                            <li>
                                User ID
                            </li>
                            <li>
                                Display Name
                            </li>
                            <li>
                                Global Avatar
                            </li>
                        </ul>
                    </ul>
                </div>
                <div class="guidelinesOption_numbered">
                    <p>
                        2. How to opt-out?
                    </p>
                    <ul>
                        If you wish to opt-out of this feature, you will need to privately message the
                        Lead Developer of Cheryl on the <a href="https://discord.gg/j7wy8jQaRA"> support server</a> and supply the following :
                        <ul>
                            <li>
                                User Name
                            </li>
                            <li>
                                User ID
                            </li>
                            <li>
                                Do you wish to have your information completely removed from our database?
                            </li>
                            <li>
                                Why do you wish to opt-out of this feature? (optional)
                            </li>
                        </ul>
                        <br>
                        Opting out of this feature limits your ability to use this bot and website. Certain commands may not work as expected. We will only save your user ID to ensure that we do not collect information from you again.
                    </ul>
                </div>
            </div>
        </div>
        <?php
        require '../private_html/essential/footer.php';
        ?>
    </main>
</body>

</html>