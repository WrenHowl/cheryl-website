<!DOCTYPE html>

<?php
$pageDesc = 'View the guidelines of Cheryl. Including blacklist information.';

require 'all.php';
?>

<body>
    <?php
    require 'essential/header.php';
    ?>
    <main id="page" onscroll="scrollAlert()">
        <div class="guidelinesOption">
            <h1>
                Community Guidelines
            </h1>
            <p>
                Users that misuse or violate these guidelines may have their accounts removed from the platform.
                <br>
                Moderators have the final say on any punishment given. For any complaints, speak to <u>@wrenhowl</u> privately on Discord.
                <br>
                These guidelines are subject to change in the future, any modifications will be fully disclosed.

            </p>
            <div class="guidelinesOption_list">
                <ol>
                    <li class="guidelinesOption_numbered">
                        Do not use hate speech or any other hateful contents.
                        <ul>
                            <li>
                                Example: <u>racism</u> or <u>homophobic slurs</u>.
                            </li>
                        </ul>
                    </li>
                    <li class="guidelinesOption_numbered">
                        Do not resell or publish content that you do not own.
                        <ul>
                            <li>
                                Example: <u>posting a picture of someone else's creation without the creator's consent.</u>.
                            </li>
                        </ul>
                    </li>
                    <li class="guidelinesOption_numbered">
                        Do not publish NSFW (Not Safe For Work) content.
                        <ul>
                            <li>
                                Example: <u>pornographic content</u>.
                            </li>
                            <li>
                                Note: <u>suggestive content is allowed</u>.
                            </li>
                        </ul>
                    </li>
                    <li class="guidelinesOption_numbered">
                        Do not use this website to distribute harmful content, scam or illegal content.
                        <ul>
                            <li>
                                Example: <u>distributing malware</u>.
                            </li>
                        </ul>
                    </li>
                </ol>
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
        require 'essential/footer.php';
        ?>
    </main>
</body>

</html>