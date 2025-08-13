<?php
$user = DB->prepare("SELECT * FROM users WHERE role > 0");
$user->execute();
$userResult = $user->fetchAll(PDO::FETCH_ASSOC);

$pageDesc = 'The staff of Cheryl.';
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
                            Wren is the only developer currently, he likes to put on his fursuit and go to conventions like Furnal Equinox or CanFURence. He's a pretty gay and silly dude.
                        </span>
                    </div>
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