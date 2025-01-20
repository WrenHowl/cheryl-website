<?php
$pageDesc = 'View the guidelines of Cheryl. Including blacklist information.';
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
                    Community Guidelines
                </h1>
                <p>
                    Users that misuse or violate these guidelines may have their accounts removed from the platform or can have their account limited.
                    <br>
                    These guidelines could change in the future, any modifications will be fully disclosed.
                </p>
            </div>
            <div class="guidelines">
                <h3>
                    1. Do not use hate speech or any other hateful contents.
                </h3>
                <p>
                    Example: <u>racism</u> or <u>homophobic slurs</u>.
                </p>
                <h3>
                    2. Do not resell or publish content that you do not own.
                </h3>
                <p>
                    Example: posting a picture of someone else's creation <u>without</u> the creator's <u>consent</u>.
                    <br>
                    Note: This <u>does not</u> apply when suggesting an image with the <u>action command</u>.
                </p>
                <h3>
                    3. Do not publish NSFW (Not Safe For Work) content.
                </h3>
                <p>
                    Example: <u>pornographic content</u>.
                    <br>
                    Note: <u>suggestive content is allowed</u>.
                </p>
                <h3>
                    4. Do not use this bot or/and website to distribute harmful content, scam or illegal content.
                </h3>
                <p>
                    Example: <u>distributing malware</u>.
                </p>
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
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>