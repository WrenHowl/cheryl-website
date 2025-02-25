<?php
if (isset($guildMatches[3])) {
    $guildFind = DB->prepare("SELECT * FROM guilds WHERE id=?");
    $guildFind->execute([
        $guildMatches[3]
    ]);
    $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

    $pageDesc = 'Browse the guild ' . $guildFindResult['name'] . '.';
} else {
    $page = isset($_GET['page']) ?
        24 * $_GET['page'] - 24 :
        0;

    if (!preg_match('/\d/', $page)) die;

    $guildTags = DB->prepare("SELECT * FROM guild_tags");
    $guildTags->execute();
    $guildTagsResult = $guildTags->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_GET['tags'])) {
        $sqlId = rtrim(str_repeat('?, ', count($guildTagsResult)), ', ');
        $sqlValue = [];

        foreach ($guildTagsResult as $key => $value) {
            $sqlValue[] = $value['id'];
        }

        if (empty($sqlId) || !preg_match('/(\?|\,.)/', $sqlId)) die;;

        $guildFind = DB->prepare("SELECT * FROM guilds WHERE bot_in=? AND public=? AND review_status=? AND id IN ($sqlId) ORDER BY last_push DESC LIMIT 24 OFFSET $page");
        $guildFind->execute([
            1,
            1,
            1,
            ...$sqlValue
        ]);
    } else {
        $guildFind = DB->prepare("SELECT * FROM guilds WHERE bot_in=? AND public=? AND review_status=? ORDER BY last_push DESC LIMIT 24 OFFSET $page");
        $guildFind->execute([
            1,
            1,
            1,
        ]);
    }

    $guildFindResult = $guildFind->fetchAll(PDO::FETCH_ASSOC);

    $parameter = explode('?', $_SERVER['REQUEST_URI']);

    $pageDesc = 'Browse the servers that Cheryl is on.';
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
        <?php
        if (isset($guildMatches[3])) {
        ?>

        <?php
        } else {
        ?>
            <div class="browse">
                <h2>
                    Cheryl - Server Listing
                </h2>
                <div class="searchbar">
                    <input type="search" name="tags" placeholder="Browse server tags">
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
                            $url = '/assets/images/all/error-square.jpg';
                        } else {
                            $format = str_starts_with($icon, 'a_') ?
                                '.gif' :
                                '.png';

                            $url = "https://cdn.discordapp.com/icons/$id/$icon$format";
                        }
                    ?>
                        <div class="guild column">
                            <div class="guild top">
                                <?php
                                $classNsfw = $guild['nsfw'] ?
                                    ' nsfw' :
                                    '';
                                ?>
                                <img class="guild icon<?= $classNsfw ?>" src="<?= $url ?>">
                                <div class="guild info">
                                    <span class="guild name">
                                        <?= $name ?>
                                    </span>
                                    <span class="guild members">
                                        <img src="/assets/images/home/members.png"><?= $guild['members'] ?>
                                    </span>
                                    <div class="guild tags">
                                        <?php
                                        if ($guild['nsfw'] === 1) {
                                        ?>
                                            <span class="tags nsfw">
                                                NSFW
                                            </span>
                                        <?php
                                        }

                                        foreach ($guildTagsResult as $tags) {
                                            if ($tags['id'] !== $guild['id']) continue;
                                        ?>
                                            <span class="tags normal">
                                                <?= $tags['tag'] ?>
                                            </span>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="guild center">
                                <span class="guild description">
                                    <?php
                                    if (isset($guild['description'])) {
                                        echo $guild['description'];
                                    } else {
                                        echo "Currently no description available.";
                                    }
                                    ?>
                                </span>
                                <button class="guild button" type="button">
                                    <img src="/assets/images/all/arrow.png">
                                </button>
                            </div>
                            <div class="guild bottom">
                                <a class="guild view" href="/browse/guild/<?= $guild['id'] ?>">
                                    View
                                </a>
                                <a class="guild join" href="https://discord.gg/<?= $guild['invite'] ?>">
                                    Join
                                </a>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                if (false > 2) {
                ?>
                    <div class="page">
                        <?php
                        for ($i = 1; $i < $totalPage; $i++) {
                            $addParameter = isset($parameter[1]) ?
                                "?" . $parameter[1] . "&" :
                                '';
                        ?>
                            <a href="/browse<?= $addParameter ?>?page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        <?php
                        }
                        ?>
                    </div>
                <?php
                }
                ?>
            </div>
        <?php
        }
        ?>
    </main>
    <?php
    require '../private_html/essential/footer.php';
    ?>
</body>

</html>