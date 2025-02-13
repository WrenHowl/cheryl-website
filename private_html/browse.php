<?php
if (!array_key_exists('user_id', $_SESSION) || $user_id === 291262778730217472) {
    header('Location: /');
    die;
}

if (isset($guildMatches[3])) {
    $guildFind = DB->prepare("SELECT * FROM guilds WHERE id=?");
    $guildFind->execute([
        $guildMatches[3]
    ]);
    $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

    $pageDesc = 'Browse the guild ' . $guildFindResult['name'] . '.';
} else {
    $guildFind = DB->prepare("SELECT * FROM guilds WHERE bot_in=? ORDER BY RAND()");
    $guildFind->execute([
        1
    ]);
    $guildFindResult = $guildFind->fetchAll(PDO::FETCH_ASSOC);

    $pageDesc = 'Browse the servers that Cheryl is on.';
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
        <?php
        if (isset($guildMatches[3])) {
        ?>

        <?php
        } else {
        ?>
            <div class="browse">
                <h2>
                    Cheryl - Furry Servers
                </h2>
                <div class="searchbar">
                    <input type="search" name="tags" placeholder="Browse server names">
                    <div class="option">
                    </div>
                </div>
                <div class="servers">
                    <?php
                    foreach ($guildFindResult as $guild) {
                        $id = $guild['id'];
                        $name = $guild['name'];
                        $icon = $guild['avatar'];

                        if (!isset($icon)) {
                            $url = '/assets/images/all/error.png';
                            continue;
                        }

                        $format = str_starts_with($icon, 'a_') ?
                            '.gif' :
                            '.png';

                        $url = "https://cdn.discordapp.com/icons/$id/$icon$format";
                    ?>
                        <div class="guild column">
                            <div class="guild top">
                                <img class="guild icon" src="<?= $url ?>">
                                <div class="guild info">
                                    <span class="guild name">
                                        <?= $name ?>
                                    </span>
                                    <span class="guild members">
                                        <img src="/assets/images/home/members.png"><?= $guild['members'] ?>
                                    </span>
                                </div>
                            </div>
                            <div class="guild bottom">
                                <span class="guild description">

                                </span>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        <?php
        }
        ?>
    </main>
    <?php
    require 'essential/footer.php';
    ?>
</body>

</html>