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

// Category
// 0 = Dev Only  1 = Admin  2 = Moderation  3 = Utilities  4 = Fun

// Type
// 0 = Message Command  1 = Slash Command  2 = Other Command 

$permissions = [
    0 => "Everyone",
    1 => "Kick Members",
    2 => "Ban Members",
    3 => "Manage Messages",
    999 => "Developer Only"
];

$categories = [
    [
        "category" => 0,
        "short" => 'staff',
        "full" => 'Staff'
    ],
    /*[
        "category" => 1,
        "short" => 'admin',
        "full" => 'Admin',
    ],*/
    [
        "category" => 2,
        "short" => 'mod',
        "full" => 'Moderation',
    ],
    [
        "category" => 3,
        "short" => 'fun',
        "full" => 'Fun',
    ],
    [
        "category" => 4,
        "short" => 'util',
        "full" => 'Utilities',
    ]
];

$commands = [
    [
        "category" => 0,
        "name" => "Verify",
        "description" => "Verify the age of a user.",
        "permission" => $permissions[999],
        "type" => 2,
    ],
    [
        "category" => 0,
        "name" => "View Details",
        "description" => "View some information about a user message.",
        "permission" => $permissions[999],
        "type" => 2,
    ],
    [
        "category" => 2,
        "name" => "ban",
        "description" => "Ban someone from the server.",
        "permission" => $permissions[2],
        "type" => 1,
    ],
    [
        "category" => 2,
        "name" => "kick",
        "description" => "Kick someone from the server.",
        "permission" => $permissions[1],
        "type" => 1,
    ],
    [
        "category" => 3,
        "name" => "avatar",
        "description" => "Show your or someone else profile picture.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
    [
        "category" => 4,
        "name" => "action",
        "description" => "Do an action on someone or yourself.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
    [
        "category" => 4,
        "name" => "level",
        "description" => "Lookup the level of a user or yourself.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
    [
        "category" => 4,
        "name" => "coinflip",
        "description" => "Conflip some XP.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
    [
        "category" => 4,
        "name" => "leaderboard",
        "description" => "Lookup the leaderboard of the server.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
    [
        "category" => 4,
        "name" => "confess",
        "description" => "Confess to something anonymously in the server.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
    [
        "category" => 3,
        "name" => "profile",
        "description" => "Show all the information the bot has on a user or yourself.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
    [
        "category" => 3,
        "name" => "staff",
        "description" => "Check to see if a user is Cheryl's staff.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
    [
        "category" => 3,
        "name" => "help",
        "description" => "Get redirected to this website.",
        "permission" => $permissions[0],
        "type" => 1,
    ],
];

$pageDesc = 'Get information about all the commands available.';
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
        <nav>
            <?php
            foreach ($categories as $category) {
            ?>
                <button id="<?= $category['short'] ?>-commands">
                    <?= $category['full'] ?>
                </button>
            <?php
            }
            ?>
        </nav>
        <h2 class="default-message">
            Select the type of command you want to see
        </h2>
        <div class="command-list">
            <?php
            foreach ($categories as $category) {
            ?>
                <div class="command-type" id="<?= $category['short'] ?>-commands" style="display: none;">
                    <?php
                    foreach ($commands as $command) {
                        if ($command['category'] !== $category['category']) continue;
                    ?>
                        <div class="command">
                            <div>
                                <h1 class="command-title">
                                    <?php
                                    if ($command['type'] === 1) echo "<span>/</span> ";
                                    echo $command['name'];
                                    ?>
                                </h1>
                                <p>
                                    <?= $command['description'] ?>
                                </p>
                            </div>
                            <span class="command-permission" title="Required Permission">
                                <?= $command['permission'] ?>
                            </span>
                        </div>
                    <?php
                    }
                    ?>
                </div>
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