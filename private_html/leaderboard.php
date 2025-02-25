<?php
if (isset($guildFind)) {
    $levelGuild = 'WHERE guild_id=?';
    $arrayQuery = [
        $guildMatches[3]
    ];
} else {
    $levelGuild = '';
    $arrayQuery = [];
}

$levelFind = DB->prepare("SELECT guild_id, id, name, level, xp, avatar FROM `levels` LEFT JOIN users ON levels.user_id = users.id $levelGuild ORDER BY xp DESC LIMIT 25");
$levelFind->execute($arrayQuery);
$levelFindResult = $levelFind->fetchAll(PDO::FETCH_ASSOC);

$guildInLeaderboard = [];

foreach ($levelFindResult as $levelGuild) {
    $guildInLeaderboard[] = $levelGuild['guild_id'];
}

if (isset($guildFind)) {
    if (!$guildFind) {
        header('Location: /leaderboard');
        die;
    }

    $guild_name = 'Leaderboard of ' . $guildFind['name'];
    $pageDesc = "Browse the leaderboard of $guild_name.";
} else {
    $guild = DB->prepare("SELECT * FROM guilds WHERE id IN (" . rtrim(str_repeat('?, ', count($guildInLeaderboard)), ', ') . ")");
    $guild->execute([
        ...$guildInLeaderboard
    ]);
    $allGuildFind = $guild->fetchAll(PDO::FETCH_ASSOC);

    $guild_name = 'Global Leaderboard';
    $pageDesc = "Browse the global leaderboard.";
}
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
        <h1>
            <?= $guild_name ?>
        </h1>
        <?php
        if ($levelFindResult) {
        ?>
            <div class="leaderboard">
                <div class="user padding">
                    <p>
                        User
                    </p>
                    <div class="user stats">
                        <p>
                            Level
                        </p>
                        <p>
                            XP
                        </p>
                    </div>
                </div>
                <?php
                $topLeaderboard = 0;

                foreach ($levelFindResult as $leaderboard) {
                    // Do not include people that doesn't have a level.
                    if ($leaderboard['level'] <= 0) continue;
                    if ($topLeaderboard >= 25) continue;

                    $format = str_starts_with($leaderboard['avatar'], 'a_') ?
                        '.gif' :
                        '.png';

                    $url = "https://cdn.discordapp.com/avatars/" . $leaderboard['id'] . "/" . $leaderboard['avatar'] . $format;

                    $topLeaderboard++;

                    switch ($topLeaderboard) {
                        case 1:
                            // First Place (Gold)
                            $color = '#FFD700';
                            break;
                        case 2:
                            // Second Place (Silver)
                            $color = '#C0C0C0';
                            break;
                        case 3:
                            // Third Place (Bronze)
                            $color = '#CD7F32';
                            break;
                        default:
                            // Everything else (None)
                            $color = '#1b1b1b';
                            break;
                    }
                ?>
                    <div class="user real" style="border: 1px solid <?= $color ?>" title="Click to show details">
                        <div class="user level">
                            <div class="user profile">
                                <img src="<?= $url ?>">
                                <p>
                                    <?= $leaderboard['name'] ?>
                                </p>
                            </div>
                            <div class="user stats">
                                <p>
                                    <?= $leaderboard['level'] ?>
                                </p>
                                <p>
                                    <?= $leaderboard['xp'] ?>
                                </p>
                            </div>
                        </div>
                        <div class="user details">
                            <div>
                                <span>
                                    Position
                                </span>
                                <?php
                                if ($color === '#1b1b1b') $color = 'white';
                                ?>
                                <span style="color: <?= $color ?>">
                                    #<?= $topLeaderboard ?>
                                </span>
                            </div>
                            <div>
                                <span>
                                    Guild
                                </span>
                                <span>
                                    <?php
                                    if (isset($guildFind)) {
                                        echo $guildFind['name'];
                                    } else {
                                        $guildLookup = array_search($leaderboard['guild_id'], array_column($allGuildFind, 'id'));
                                        echo $allGuildFind[$guildLookup]['name'];
                                    }
                                    ?>
                                </span>
                            </div>
                            <div>
                                <span>
                                    User ID
                                </span>
                                <span>
                                    <?= $leaderboard['id'] ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        <?php
        } else {
        ?>
            <p class="no-leaderboard">
                Currently, it is empty. Start gaining levels by messaging on the server!
            </p>
        <?php
        }
        ?>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>