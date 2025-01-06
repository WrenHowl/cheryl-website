<?php
if (array_key_exists('guild', $_GET)) {
    $guildFind = DB->prepare("SELECT * FROM `guilds` WHERE guildId=?");
    $guildFind->execute([
        $_GET['guild']
    ]);
    $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

    $guildName = 'Leaderboard of ' . $guildFindResult['guildName'];

    $levelFind = DB->prepare("SELECT * FROM `level` WHERE guildId=? ORDER BY xp DESC");
    $levelFind->execute([
        $_GET['guild']
    ]);
    $levelFindResult = $levelFind->fetchAll(PDO::FETCH_ASSOC);

    if (!$levelFindResult) header('Location: /leaderboard');

    $pageDesc = "Browse the leaderboard of $guildName.";
} else {
    $guildName = 'Global Leaderboard';

    $levelFind = DB->prepare("SELECT * FROM `level` ORDER BY xp DESC");
    $levelFind->execute();
    $levelFindResult = $levelFind->fetchAll(PDO::FETCH_ASSOC);

    $pageDesc = "Browse the $guildName.";
}
?>

<!DOCTYPE html>

<?php
require 'all/all.php';
require 'all/style.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main id="page">
        <h1>
            <?= $guildName ?>
        </h1>
        <div class="page">
            <div class="leaderboard">
                <div class="leaderboard-description">
                    <p>
                        User
                    </p>
                    <div class="leaderboardDescription-rightside">
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
                    //
                    // Do not include people that doesn't have a level.
                    if ($leaderboard['level'] <= 0) continue;
                    if ($topLeaderboard > 25) continue;

                    //
                    // Check if the user exist in the database, if not continue without including them.
                    $userFind = DB->prepare("SELECT * FROM `users` WHERE userId=?");
                    $userFind->execute([
                        $leaderboard['userId']
                    ]);
                    $userFindResult = $userFind->fetch(PDO::FETCH_ASSOC);
                    if ($userFindResult != true) continue;

                    str_starts_with($userFindResult['avatar'], 'a_') ?
                        $format = '.gif' :
                        $format = '.png';

                    $url = "https://cdn.discordapp.com/avatars/" . $userFindResult['userId'] . "/" . $userFindResult['avatar'] . $format;

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
                    <div class="user" style="border: 1px solid <?= $color ?>" onclick="copyID('<?= $leaderboard['userId'] ?>')">
                        <div class="user-profile">
                            <img src="<?= $url ?>">
                            <p>
                                <?= $userFindResult['userName'] ?>
                            </p>
                        </div>
                        <div class="user-stats">
                            <p>
                                <?= $leaderboard['level'] ?>
                            </p>
                            <p>
                                <?= $leaderboard['xp'] ?>
                            </p>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
        <?php
        require '../private_html/essential/footer.php';
        ?>
    </main>
</body>

</html>