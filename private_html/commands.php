<!DOCTYPE html>

<?php
$role = 0;

if (array_key_exists('userId', $_SESSION)) {
    $userId = $_SESSION['userId'];
    $user = DB->prepare("SELECT `role` FROM users WHERE userId=?");
    $user->execute([
        $userId,
    ]);
    $userResult = $user->fetch(PDO::FETCH_ASSOC);

    $role = $userResult['role'];
}

$pageDesc = 'Get information about all the commands available.';

require 'all.php';
?>

<body>
    <?php
    require 'essential/header.php';
    ?>
    <main id="page" onscroll="scrollAlert()">
        <div class="page">
            <div class="filterCommand">
                <div class="filterCommand_list">
                    <?php
                    if ($role >= 1) {
                    ?>
                        <input type="button" id="button_staff" value="Dev/Staff Only" onclick="showNewCommands(this.id)">
                    <?php
                    }
                    ?>
                    <input type="button" id="button_mod" value="Moderation" onclick="showNewCommands(this.id)">
                    <input type="button" id="button_fun" value="Fun" onclick="showNewCommands(this.id)">
                    <input type="button" id="button_util" value="Utilites" onclick="showNewCommands(this.id)">
                </div>
            </div>
            <div id="listCommand">
                <h2 id="noCommandSelected">
                    Select the type of command you want to see
                </h2>
                <?php
                if ($role >= 1) {
                ?>
                    <div id="staff_only_cmd" class="commandCategory" style="display: none;">
                        <div class="command">
                            <div class="commandDetail">
                                <h1>
                                    <span>/</span> blacklist
                                </h1>
                                <p>
                                    Add or remove someone from the blacklist.
                                </p>
                            </div>
                        </div>
                        <div class="command">
                            <div class="commandDetail">
                                <h1>
                                    <span>/</span> verify
                                </h1>
                                <p>
                                    N/A
                                </p>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
                <div id="mod_cmd" class="commandCategory" style="display: none;">
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> ban
                            </h1>
                            <p>
                                Ban someone from the server.
                            </p>
                        </div>
                        <div class="commandPermission">
                            <p class="permissionsDetails" title="Required Permission">
                                Ban Members
                            </p>
                        </div>
                    </div>
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> kick
                            </h1>
                            <p>
                                Kick someone from the server.
                            </p>
                        </div>
                        <div class="commandPermission">
                            <p class="permissionsDetails" title="Required Permission">
                                Kick Members
                            </p>
                        </div>
                    </div>
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> lock
                            </h1>
                            <p>
                                Lock the current channel.
                            </p>
                        </div>
                        <div class="commandPermission">
                            <p class="permissionsDetails" title="Required Permission">
                                Manage Messages
                            </p>
                        </div>
                    </div>
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> unlock
                            </h1>
                            <p>
                                Unlock the current channel.
                            </p>
                        </div>
                        <div class="commandPermission">
                            <p class="permissionsDetails" title="Required Permission">
                                Manage Messages
                            </p>
                        </div>
                    </div>
                </div>
                <div id="fun_cmd" class="commandCategory" style="display: none;">
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> avatar
                            </h1>
                            <p>
                                Show your or someone else avatar.
                            </p>
                        </div>
                    </div>
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> action
                            </h1>
                            <p>
                                Do an action on someone or yourself.
                            </p>
                        </div>
                    </div>
                </div>
                <div id="util_cmd" class="commandCategory" style="display: none;">
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> ping
                            </h1>
                            <p>
                                Show the current ping of the bot.
                            </p>
                        </div>
                    </div>
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> profile
                            </h1>
                            <p>
                                Show all the information the bot has on a user or yourself.
                            </p>
                        </div>
                    </div>
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> serverinfo
                            </h1>
                            <p>
                                Show all the information about the server.
                            </p>
                        </div>
                    </div>
                    <div class="command">
                        <div class="commandDetail">
                            <h1>
                                <span>/</span> staff
                            </h1>
                            <p>
                                Check to see if a user is Cheryl's staff.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        require 'essential/footer.php';
        ?>
    </main>
</body>

</html>