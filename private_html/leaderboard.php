<?php
if (isset($guildMatches[3])) {
    $guildFind = DB->prepare("SELECT * FROM `guilds` WHERE id=?");
    $guildFind->execute([
        $guildMatches[3]
    ]);
    $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

    if (!$guildFindResult) {
        header('Location: /leaderboard');
        die;
    }

    $guild_name = 'Leaderboard of ' . $guildFindResult['name'];

    $levelFind = DB->prepare("SELECT * FROM `levels` WHERE guild_id=? ORDER BY xp DESC");
    $levelFind->execute([
        $guildMatches[3]
    ]);
    $levelFindResult = $levelFind->fetchAll(PDO::FETCH_ASSOC);

    $pageDesc = "Browse the leaderboard of $guild_name.";
} else {
    $guild_name = 'Global Leaderboard';

    $levelFind = DB->prepare("SELECT * FROM `levels` ORDER BY xp DESC");
    $levelFind->execute();
    $levelFindResult = $levelFind->fetchAll(PDO::FETCH_ASSOC);

    $pageDesc = "Browse the global leaderboard.";
}
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

                    // Check if the user exist in the database, if not continue without including them.
                    $userFind = DB->prepare("SELECT * FROM `users` WHERE id=?");
                    $userFind->execute([
                        $leaderboard['user_id']
                    ]);
                    $userFindResult = $userFind->fetch(PDO::FETCH_ASSOC);
                    if ($userFindResult != true) continue;

                    $format = str_starts_with($userFindResult['avatar'], 'a_') ?
                        '.gif' :
                        '.png';

                    $url = "https://cdn.discordapp.com/avatars/" . $userFindResult['id'] . "/" . $userFindResult['avatar'] . $format;

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

                    $guildFind = DB->prepare("SELECT * FROM `guilds` WHERE id=?");
                    $guildFind->execute([
                        $leaderboard['guild_id']
                    ]);
                    $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

                ?>
                    <div class="user real" style="border: 1px solid <?= $color ?>" title="Click to show details">
                        <div class="user level">
                            <div class="user profile">
                                <img src="<?= $url ?>">
                                <p>
                                    <?= $userFindResult['name'] ?>
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
                                    <?= $guildFindResult['name'] ?>
                                </span>
                            </div>
                            <div>
                                <span>
                                    User ID
                                </span>
                                <span>
                                    <?= $leaderboard['user_id'] ?>
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
    require 'essential/footer.php';
    ?>
</body>

</html>