<?php
$user = DB->prepare("SELECT * FROM users WHERE role > 0");
$user->execute();
$userResult = $user->fetchAll(PDO::FETCH_ASSOC);

$pageDesc = 'The staff of Cheryl.';
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
        <div class="staff">
            <?php
            foreach ($userResult as $user) {
                $userId = $user['id'];
                $avatar = $user['avatar'];
                $username = $user['name'];
                $role = $user['role'];

                $format = str_starts_with($avatar, 'a_') ?
                    '.gif' :
                    '.png';

                $avatarUrl = "https://cdn.discordapp.com/avatars/$userId/$avatar$format";
            ?>
                <div class="user">
                    <img src="<?= $avatarUrl ?>" style="border: 2px solid <?= $rank[$role]['color'] ?>">
                    <div class="user-info">
                        <div>
                            <span class="username">
                                <?= $username ?>
                            </span>
                            <span class="arrow">
                                →
                            </span>
                            <span class="role" style="color: <?= $rank[$role]['color'] ?>">
                                <?= $rank[$role]['name'] ?>
                            </span>
                        </div>
                        <span class="description">
                            Currently the only developer to work on the project, he gets some help from others, but generally doesn't have a team that works full time with him.
                        </span>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </main>
    <?php
    require 'essential/footer.php';
    ?>
</body>

</html>