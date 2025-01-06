<?php
$adminTool = false;

if (array_key_exists('userId', $_SESSION)) {
    // Check for the user session information
    $userFind = DB->prepare("SELECT `role` FROM users WHERE userId=?");
    $userFind->execute([
        $userId
    ]);
    $userFindResult = $userFind->fetch(PDO::FETCH_ASSOC);

    $role = $userFindResult['role'];

    if ($userFindResult) {
        $role >= 1 ?
            $adminTool = true :
            $adminTool = false;
    }
}

$id = $_GET['id'];

$user = DB->prepare("SELECT `globalName`, `userId`, `nationality`, `age`, `role`, `avatar`, `createdAt` FROM users WHERE accountId=?");
$user->execute([
    $id,
]);
$userResult = $user->fetch(PDO::FETCH_ASSOC);

if (!$userResult) {
    // If page not valid, return to default page
    $globalName = 'default';
    header('Location: /commissions/user?id=1');
    die;
}

$globalName = $userResult['globalName'];
$avatar = $userResult['avatar'];
$userId = $userResult['userId'];
$createdAt = $userResult['createdAt'];
$role = $userResult['role'];
$nationality = $userResult['nationality'];
$age = $userResult['age'];

// Changing avatar in function of anim or not
$avatarUrl = "/assets/images/external_logos/discord.png";

if ($avatar) {
    str_starts_with($avatar, 'a_') ?
        $format = '.gif' :
        $format = '.png';

    $avatarUrl = "https://cdn.discordapp.com/avatars/$userId/$avatar$format";
}

// Checking user role and applying right color to their profile
switch ($role) {
    case 1:
        $role = 'Lead Developer';
        $color = '#ff1e25';
        break;
    case 2:
        $role = 'Developer';
        $color = '#ff5b5b';
        break;
    case 3:
        $role = 'Administrator';
        $color = '#1668ff';
        break;
    case 4:
        $role = 'Moderator';
        $color = '#3b80ff';
        break;
    case 5:
        $role = 'Donator';
        $color = '#FFD700';
        break;
    default:
        $role = 'User';
        $color = 'white';
        break;
};

// Checking for commission information
$userCommission = DB->prepare("SELECT `status`, `pricing`, `slot`, `maxSlot`, `discount`, `rating` FROM commissions WHERE accountId=?");
$userCommission->execute([
    $id,
]);
$userComissionResult = $userCommission->fetch(PDO::FETCH_ASSOC);

$status = $userComissionResult['status'];
$pricing = $userComissionResult['pricing'];
$slot = $userComissionResult['slot'];
$maxSlot = $userComissionResult['maxSlot'];
$discount = $userComissionResult['discount'];
$rating = $userComissionResult['rating'];

$status === 1 ?
    $status = "ON" :
    $status = "OFF";

$pageDesc = "View <?= $globalName ?>'s profile and commission.";
?>

<!DOCTYPE html>

<?php
require '../private_html/all/all.php';
require '../private_html/all/style.php';
?>

<body>
    <?php
    require '../private_html/essential/header.php';
    ?>
    <main id="page">
        <div class="backgroundPage">
            <div class="page">
                <div class="leftPage">
                    <div class="aboutMe">
                        <div class="moreInfo_name" id="moreInfoTitle_left">
                            About Me
                        </div>
                        <p class="aboutMe_box">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris magna risus, eleifend eu posuere et, feugiat ut ipsum. Nulla facilisi. Proin venenatis nisi vitae urna pharetra sollicitudin. Nunc ac dui lectus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Nam mattis suscipit elit, sed cursus mauris aliquet a. Curabitur non viverra nibh. Nullam neque nunc, bibendum elementum risus rhoncus, consectetur convallis lorem. Cras sed diam et leo blandit pulvinar. Fusce tempus, augue pretium iaculis luctus, sapien massa feugiat ante, dignissim condimentum nulla ex feugiat sem. Morbi purus turpis, sodales eu ultrices eu, pellentesque eu felis. Vivamus et fermentum massa.
                            <br><br>
                            Maecenas non placerat velit. Nulla facilisi. Ut porta, risus eget blandit hendrerit, neque libero faucibus diam, eget condimentum diam nisi eu massa. Nunc interdum bibendum tincidunt. Nulla accumsan libero libero. Etiam quis mauris in felis blandit bibendum. Fusce suscipit finibus metus. Nullam luctus elit tempus metus tincidunt mattis. Morbi at orci nibh. Aenean efficitur nulla urna, id commodo sem porta vel.
                            <br><br>
                            Vestibulum nunc tellus, congue sit amet suscipit vitae, congue non massa. Sed laoreet tellus magna, venenatis posuere sapien consequat et. Nulla auctor elit in venenatis semper. Sed eget magna eget tortor aliquam vulputate eu in ante. Suspendisse purus tellus, molestie sit amet dui eget, aliquam malesuada augue. Integer accumsan tristique risus. Sed pharetra nunc a egestas porttitor. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Vestibulum id turpis sapien. Vestibulum vulputate risus sit amet risus tincidunt faucibus. In rutrum sem ut posuere congue. Duis justo enim, gravida non augue sit amet, viverra iaculis diam.
                            <br><br>
                            Aliquam cursus felis vel ante aliquet, eget blandit tortor vulputate. Aliquam erat volutpat. Curabitur ac facilisis dui. Etiam sit amet risus id ante facilisis ornare varius ut velit. Nullam faucibus, risus vitae egestas laoreet, magna arcu tristique lorem, eleifend iaculis ex eros ut arcu. Suspendisse potenti. Praesent nec mi magna. Integer vulputate libero erat. Nunc eleifend lobortis libero, quis accumsan erat. Integer tempus nec felis at viverra. Phasellus pharetra justo aliquam, varius est eu, blandit nibh.
                            <br><br>
                            Maecenas ex odio, venenatis non nunc sit amet, aliquam vehicula purus. Integer elit diam, sodales non condimentum eget, iaculis tristique lacus. Integer non mauris vel sem scelerisque aliquet. Vestibulum risus lorem, mollis eget enim quis, faucibus bibendum quam. Sed pharetra nec elit ut consequat. Aliquam a felis ac risus vestibulum faucibus ac sit amet metus. Donec tincidunt nec odio et varius. Nunc nibh felis, suscipit et mollis sed, viverra quis ex. Phasellus at ipsum consectetur, finibus urna id, euismod dui. Lorem ipsum dolor sit amet, consectetur adipiscing elit. In porttitor libero et lorem rutrum posuere. In hac habitasse platea dictumst. Pellentesque commodo ante odio, a dapibus metus dignissim eget. Curabitur eu ante sed nisl consequat blandit. Cras sodales tellus vitae nibh ullamcorper, vitae lobortis massa lacinia.
                        </p>
                    </div>
                    <div class="showcase">
                        <div class="showcaseSizing">
                            <img class="showcaseArt" src='/assets/images/commissions/default_showcase_1.png'>
                        </div>
                        <div class="showcaseSizing">
                            <img class="showcaseArt" src='/assets/images/commissions/default_showcase_2.png'>
                        </div>
                        <div class="showcaseSizing">
                            <img class="showcaseArt" src='/assets/images/commissions/default_showcase_3.png'>
                        </div>
                    </div>
                </div>
                <div class="rightPage">
                    <div class="avatar">
                        <img src="<?= $avatarUrl ?>" style="border-color: <?= $color ?>">
                    </div>
                    <div class="userInfo">
                        <div class="moreInfo">
                            <span class="moreInfo_name">
                                User Info
                            </span>
                            <div class="moreInfo_content">
                                <div class="moreInfoContent_name">Age</div>
                                <div class="moreInfoContent_value"><?= $age ?> years old</div>
                            </div>
                            <div class="moreInfo_content">
                                <div class="moreInfoContent_name">Nationality</div>
                                <div class="moreInfoContent_value"><?= $nationality ?></div>
                            </div>
                            <div class="moreInfo_content">
                                <div class="moreInfoContent_name">Role</div>
                                <div class="moreInfoContent_value" style="color: <?= $color ?>"><?= $role ?></div>
                            </div>
                            <div class="moreInfo_content">
                                <div class="moreInfoContent_name">Created At</div>
                                <div class="moreInfoContent_value"><?= $createdAt ?></div>
                            </div>
                        </div>
                        <div class="moreInfo">
                            <span class="moreInfo_name">
                                Commission Info
                            </span>
                            <div class="moreInfo_content">
                                <div class="moreInfoContent_name">Status</div>
                                <div class="moreInfoContent_value"><?= $status ?></div>
                            </div>
                            <?php
                            if (!$pricing == 0) {
                                switch ($pricing) {
                                    case (1):
                                        $valuePricing = 'Free';
                                        break;
                                    case (2):
                                        $valuePricing = '$';
                                        break;
                                    case (3):
                                        $valuePricing = '$$';
                                        break;
                                    case (4):
                                        $valuePricing = '$$$';
                                        break;
                                    case (5):
                                        $valuePricing = '$$$$';
                                        break;
                                    case (6):
                                        $valuePricing = '$$$$$';
                                        break;
                                }
                            ?>
                                <div class="moreInfo_content">
                                    <div class="moreInfoContent_name">Pricing</div>
                                    <div class="moreInfoContent_value"><?= $valuePricing ?></div>
                                </div>
                                <div class="moreInfo_content">
                                    <div class="moreInfoContent_name">Slots</div>
                                    <div class="moreInfoContent_value"><?= $slot ?>/<?= $maxSlot ?></div>
                                </div>
                            <?php
                            }
                            ?>
                            <div class="moreInfo_content">
                                <div class="moreInfoContent_name">Rating</div>
                                <div class="moreInfoContent_value"><?= $rating ?></div>
                            </div>
                        </div>
                        <?php
                        if ($adminTool === true) {
                        ?>
                            <div class="moreInfo">
                                <div class="moreInfo_name">
                                    Admin Tools
                                </div>
                                <form class="settingForm" method="POST" enctype="application/x-www-form-urlencoded">
                                    <button type="submit" title="Ban">
                                        <img src="/assets/images/moderation/ban-hammer.png">
                                    </button>
                                    <input type="hidden" name="banAction" value=1>
                                    <button type="submit" title="Warn">
                                        <img src="/assets/images/moderation/warning.png">
                                    </button>
                                    <input type="hidden" name="warnAction" value=1>
                                </form>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="space"></div>
        <?php
        require '../private_html/essential/footer.php';
        ?>
    </main>
</body>

</html>