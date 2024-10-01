<?php
$role = 0;

if (array_key_exists('userId', $_SESSION)) {
    $userId = $_SESSION['userId'];
    $user = DB->prepare("SELECT `role` FROM users WHERE userId=:userId");
    $user->execute(
        [
            ':userId' => $userId,
        ],
    );
    $userResult = $user->fetch(PDO::FETCH_ASSOC);

    $role = $userResult['role'];
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
    <title>
        Cheryl | Commands
    </title>
    <meta content="Cheryl | Commands" property="og:title" />
    <meta content="Get information about all the commands available." property="og:description" />
    <?php
    require 'all.php';
    ?>
</head>

<body>
    <?php
    require 'assets/php/nav-bar.php';
    ?>
    <main>
        <div class="windowInfo">
        </div>
        <div class="filterCommand">
            <div class="filterCommand_list">
                <?php
                if ($role >= 1) {
                ?>
                    <li class="command" style="background-color: #272b2d;">
                        <button id="button_staff" onclick="showNewCommands(this.id)">
                            Dev/Staff Only
                        </button>
                    </li>
                <?php
                }
                ?>
                <li class="command" style="background-color: #272b2d;">
                    <button id="button_mod" onclick="showNewCommands(this.id)">
                        Moderation
                    </button>
                </li>
                <li class="command" style="background-color: #272b2d;">
                    <button id="button_fun" onclick="showNewCommands(this.id)">
                        Fun
                    </button>
                </li>
                <li class="command" style="background-color: #272b2d;">
                    <button id="button_util" onclick="showNewCommands(this.id)">
                        Utilites
                    </button>
                </li>
            </div>
        </div>
        <div class="commandMenu">
            <p id="noCommandSelected">
                Select the type of command you want to see
            </p>
            <?php
            if ($role >= 1) {
            ?>
                <div id="staff_only_cmd" class="commandCategory" style="display: none;">
                    <div class="commandInfo_right">
                        <p class="commandName">
                            blacklist
                        </p>
                        <p class="commandDesc">
                            Add/remove someone from the blacklist
                        </p>
                    </div>
                    <div class="commandInfo_left">
                        <p class="commandName">
                            verify
                        </p>
                        <p class="commandDesc">
                            Verify someone's age and add them to the profile database
                        </p>
                    </div>
                </div>
            <?php
            }
            ?>
            <div id="mod_cmd" class="commandCategory" style="display: none;">
                <div class="commandInfo_right">
                    <p class="commandName">
                        ban
                    </p>
                    <p class="commandDesc">
                        Ban someone from the server
                    </p>
                </div>
                <div class="commandInfo_left">
                    <p class="commandName">
                        kick
                    </p>
                    <p class="commandDesc">
                        Kick someone from the server
                    </p>
                </div>
                <div class="commandInfo_right">
                    <p class="commandName">
                        lock
                    </p>
                    <p class="commandDesc">
                        Lock the channel the command is executed in
                    </p>
                </div>
                <div class="commandInfo_left">
                    <p class="commandName">
                        unlock
                    </p>
                    <p class="commandDesc">
                        Unlock the channel the command is executed in
                    </p>
                </div>
            </div>
            <div id="fun_cmd" class="commandCategory" style="display: none;">
                <div class="commandInfo_right">
                    <p class="commandName">
                        avatar
                    </p>
                    <p class="commandDesc">
                        Show your or someone else avatar
                    </p>
                </div>
                <div class="commandInfo_left">
                    <p class="commandName">
                        action
                    </p>
                    <p class="commandDesc">
                        Do an action on someone or yourself
                    </p>
                </div>
            </div>
            <div id="util_cmd" class="commandCategory" style="display: none;">
                <div class="commandInfo_right">
                    <p class="commandName">
                        ping
                    </p>
                    <p class="commandDesc">
                        Show the current ping of the bot
                    </p>
                </div>
                <div class="commandInfo_left">
                    <p class="commandName">
                        profile
                    </p>
                    <p class="commandDesc">
                        Show all the information the bot has about a user
                    </p>
                </div>
                <div class="commandInfo_right">
                    <p class="commandName">
                        serverinfo
                    </p>
                    <p class="commandDesc">
                        Show all the information about the server
                    </p>
                </div>
                <div class="commandInfo_left">
                    <p class="commandName">
                        staff
                    </p>
                    <p class="commandDesc">
                        See if a user is a staff of Cheryl
                    </p>
                </div>
            </div>
        </div>
        <?php
        require('assets/php/bottom.php');
        ?>
    </main>
</body>

</html>