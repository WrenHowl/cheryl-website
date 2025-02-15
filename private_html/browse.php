<?php
if (isset($guildMatches[3])) {
    $guildFind = DB->prepare("SELECT * FROM guilds WHERE id=?");
    $guildFind->execute([
        $guildMatches[3]
    ]);
    $guildFindResult = $guildFind->fetch(PDO::FETCH_ASSOC);

    $pageDesc = 'Browse the guild ' . $guildFindResult['name'] . '.';
} else {
    if (isset($_GET['tags'])) {
        $sqlTag = "AND tags LIKE ?";
        $sqlSetting = [
            1,
            1,
            1,
            "%" . $_GET['tags'] . "%"
        ];
    } else {
        $sqlTag = '';
        $sqlSetting = [
            1,
            1,
            1
        ];
    }

    // Check if it's the desired string or return it to avoid SQL Injection.
    if (!empty($sqlTag) && $sqlTag !== "AND tags LIKE ?") return;

    // Could use offset and limit instead, but if I want to use page without refreshing page, I can use this already made.
    $guildFind = DB->prepare("SELECT * FROM guilds WHERE bot_in=? AND public=? AND review_status=? $sqlTag ORDER BY last_push DESC");
    $guildFind->execute(
        $sqlSetting
    );
    $guildFindResult = $guildFind->fetchAll(PDO::FETCH_ASSOC);

    $guildTags = DB->prepare("SELECT * FROM guild_tags");
    $guildTags->execute();
    $guildTagsResult = $guildTags->fetchAll(PDO::FETCH_ASSOC);

    // Get the amount of page by getting the total amount of array and divide by the amount of server per page and add 1 extra page.
    $totalPage = ceil(count($guildFindResult) / 24) + 1;

    // Create an offset and limit of amount of server to show.
    $skipPage = isset($_GET['page']) ?
        array_slice($guildFindResult, (24 * $_GET['page']) - 24, 24) :
        array_slice($guildFindResult, 0, 24);

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
                    <input type="search" name="tags" placeholder="Browse server tags" disabled>
                    <div class="option">
                    </div>
                </div>
                <div class="servers">
                    <?php
                    foreach ($skipPage as $guild) {
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
                                <img class="guild icon" src="<?= $url ?>">
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
                if ($totalPage > 2) {
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