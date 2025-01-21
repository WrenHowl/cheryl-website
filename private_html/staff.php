<?php
$user = DB->prepare("SELECT * FROM users HAVING COUNT(role) > 0");
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
            $role = [
                1 => [
                    'name' => 'Lead Developer',
                    'color' => '#ff1e25',
                ],
                2 => [
                    'name' => 'Developer',
                    'color' => '#ff5b5b'
                ],
                3 => [
                    'name' => 'Administrator',
                    'color' => '#1668ff'
                ],
                4 => [
                    'name' => 'Moderator',
                    'color' => '#3b80ff'
                ],
                5 => [
                    'name' => 'Helper',
                    'color' => '#FFD700'
                ],
            ];

            foreach ($userResult as $user) {
                $userId = $user['userId'];
                $avatar = $user['avatar'];
                $globalName = $user['globalName'];

                $format = str_starts_with($avatar, 'a_') ?
                    '.gif' :
                    '.png';

                $avatarUrl = "https://cdn.discordapp.com/avatars/$userId/$avatar$format";
            ?>
                <img src="<?= $avatarUrl ?>" style="border: 2px solid <?= $role[$user['role']]['color'] ?>">
                <div class="user-info">
                    <div>
                        <span class="username">
                            <?= $globalName ?>
                        </span>
                        <span class="arrow">
                            →
                        </span>
                        <span class="role" style="color: <?= $role[$user['role']]['color'] ?>">
                            <?= $role[$user['role']]['name'] ?>
                        </span>
                    </div>
                    <span class="description">
                        Currently the only developer to work on the project, he gets some help from others, but generally doesn't have a team that works full time with him.
                    </span>
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