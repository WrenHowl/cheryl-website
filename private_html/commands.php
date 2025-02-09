<?php
$role = 0;

if (array_key_exists('user_id', $_SESSION)) {
    $user = DB->prepare("SELECT * FROM users WHERE id=?");
    $user->execute([
        $user_id,
    ]);
    $userResult = $user->fetch(PDO::FETCH_ASSOC);

    $role = $userResult['role'];
}

$pageDesc = 'Get information about all the commands available.';
?>

<!DOCTYPE html>

<?php
require 'all/all.php';
require 'all/style.php';
?>

<body>
    <?php
    require 'essential/header.php';
    ?>
    <main>
        <nav>
            <?php
            if ($role >= 1) {
            ?>
                <button id="staff-commands">
                    Dev/Staff Only
                </button>
            <?php
            }
            ?>
            <button id="mod-commands">
                Moderation
            </button>
            <button id="fun-commands">
                Fun
            </button>
            <button id="util-commands">
                Utilites
            </button>
        </nav>
        <h2 class="default-message">
            Select the type of command you want to see
        </h2>
        <div class="command-list">
            <?php
            if ($role >= 1) {
            ?>
                <div class="command-type" id="staff-commands" style="display: none;">
                    <div class="command">
                        <h1>
                            <span>/</span> blacklist
                        </h1>
                        <p>
                            Add or remove someone from the blacklist.
                        </p>
                    </div>
                    <div class="command">
                        <h1>
                            <span>/</span> verify
                        </h1>
                        <p>
                            N/A
                        </p>
                    </div>
                </div>
            <?php
            }
            ?>
            <div class="command-type" id="mod-commands" style="display: none;">
                <div class="command">
                    <div>
                        <h1>
                            <span>/</span> ban
                        </h1>
                        <p>
                            Ban someone from the server.
                        </p>
                    </div>
                    <div class="command-permission" title="Required Permission">
                        Ban Members
                    </div>
                </div>
                <div class="command">
                    <div>
                        <h1>
                            <span>/</span> kick
                        </h1>
                        <p>
                            Kick someone from the server.
                        </p>
                    </div>
                    <div class="command-permission" title="Required Permission">
                        Kick Members
                    </div>
                </div>
                <div class="command">
                    <div>
                        <h1>
                            <span>/</span> lock
                        </h1>
                        <p>
                            Lock the current channel.
                        </p>
                    </div>
                    <div class="command-permission" title="Required Permission">
                        Manage Messages
                    </div>
                </div>
                <div class="command">
                    <div>
                        <h1>
                            <span>/</span> unlock
                        </h1>
                        <p>
                            Unlock the current channel.
                        </p>
                    </div>
                    <div class="command-permission" title="Required Permission">
                        Manage Messages
                    </div>
                </div>
            </div>
            <div class="command-type" id="fun-commands" style="display: none;">
                <div class="command">
                    <h1>
                        <span>/</span> avatar
                    </h1>
                    <p>
                        Show your or someone else avatar.
                    </p>
                </div>
                <div class="command">
                    <h1>
                        <span>/</span> action
                    </h1>
                    <p>
                        Do an action on someone or yourself.
                    </p>
                </div>
            </div>
            <div class="command-type" id="util-commands" style="display: none;">
                <div class="command">
                    <h1>
                        <span>/</span> ping
                    </h1>
                    <p>
                        Show the current ping of the bot.
                    </p>
                </div>
                <div class="command">
                    <h1>
                        <span>/</span> profile
                    </h1>
                    <p>
                        Show all the information the bot has on a user or yourself.
                    </p>
                </div>
                <div class="command">
                    <h1>
                        <span>/</span> serverinfo
                    </h1>
                    <p>
                        Show all the information about the server.
                    </p>
                </div>
                <div class="command">
                    <h1>
                        <span>/</span> staff
                    </h1>
                    <p>
                        Check to see if a user is Cheryl's staff.
                    </p>
                </div>
            </div>
        </div>
    </main>
    <?php
    require 'essential/footer.php';
    ?>
</body>

</html>