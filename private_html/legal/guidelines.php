<!DOCTYPE html>

<?php
$pageDesc = 'View the guidelines of Cheryl. Including blacklist information.';

require '../private_html/all.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main id="page" onscroll="scrollAlert()">
        <div class="guidelinesOption">
            <h1>
                Community Guidelines
            </h1>
            <p>
                Users that misuse or violate these guidelines may have their accounts removed from the platform or can have their account limited.
                <br>
                These guidelines could change in the future, any modifications will be fully disclosed.
            </p>
            <br>
            <div class="guidelinesOption_list">
                <div class="guidelinesOption_numbered">
                    <h4>
                        1. Do not use hate speech or any other hateful contents.
                    </h4>
                    <ul>
                        Example: <u>racism</u> or <u>homophobic slurs</u>.
                    </ul>
                </div>
                <div class="guidelinesOption_numbered">
                    <h4>
                        2. Do not resell or publish content that you do not own.
                    </h4>
                    <ul>
                        Example: posting a picture of someone else's creation <u>without</u> the creator's <u>consent</u>.
                        <br>
                        Note: This <u>does not</u> apply when suggesting an image with the <u>action command</u>.
                    </ul>
                </div>
                <div class="guidelinesOption_numbered">
                    <h4>
                        3. Do not publish NSFW (Not Safe For Work) content.
                    </h4>
                    <ul>
                        Example: <u>pornographic content</u>.
                        <br>
                        Note: <u>suggestive content is allowed</u>.
                    </ul>
                </div>
                <div class="guidelinesOption_numbered">
                    <h4>
                        4. Do not use this bot or/and website to distribute harmful content, scam or illegal content.
                    </h4>
                    <ul>
                        Example: <u>distributing malware</u>.
                    </ul>
                </div>
            </div>
            <?php
            if (false) {
            ?>
                <h1>
                    Punishment Guidelines
                </h1>
            <?php
            }
            ?>
        </div>
        <?php
        require '../private_html/essential/footer.php';
        ?>
    </main>
</body>

</html>