<!DOCTYPE html>

<?php
function userCom($type)
{
    $userCommission = DB->prepare("SELECT `image`, `accountId`, `finishWhen` FROM commissions WHERE `type`=:type");
    $userCommission->execute(
        [
            ':type' => $type,
        ],
    );
    $userComissionResult = $userCommission->fetchAll(PDO::FETCH_ASSOC);
    return $userComissionResult;
}

$redirectCommission = '/commissions/user?id=';

$pageDesc = 'Commission or advertise your commission to others. Digital artist, VRChat avatar, etc. are all welcomed!';

require 'all.php';

?>

<body>
    <?php
    require 'essential/header.php';
    ?>
    <main>
        <div class="windowInfo">
        </div>
        <div class="page">
            <div>
                <h2 class="comTitle" id="com_Promoted">
                    Promoted
                </h2>
                <div class="comCase">
                    <?php
                    $userComissionResult = userCom(0);

                    foreach ($userComissionResult as $an) {
                        $image = $an['image'];
                        $id = $an['accountId'];
                    ?>
                        <a class="comImage" href="<?= $redirectCommission . $id ?>"></a>
                    <?php
                    }
                    if (!$userComissionResult) {
                    ?>
                        <p>
                            No commission in this category yet!
                        </p>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="space"></div>
            <div>
                <h2 class="comTitle" id="promotion_ongoing_commission">
                    Promotion Ongoing
                </h2>
                <div class="comCase">
                    <?php
                    $userComissionResult = userCom(1);

                    foreach ($userComissionResult as $an) {
                        $image = $an['image'];
                        $id = $an['accountId'];
                    ?>
                        <a class="comImage" href="<?= $redirectCommission . $id ?>">
                            <div class="comDiscount">
                                50% OFF
                            </div>
                        </a>
                    <?php
                    }
                    if (!$userComissionResult) {
                    ?>
                        <p>
                            No commission in this category yet!
                        </p>
                    <?php
                    }
                    ?>
                </div>
            </div>
            <div class="space"></div>
            <div>
                <h2 class="comTitle" id="new_commission">
                    Recently Published
                </h2>
                <div class="comCase">
                    <?php
                    $userComissionResult = userCom(2);

                    foreach ($userComissionResult as $an) {
                        $image = $an['image'];
                        $id = $an['accountId'];
                    ?>
                        <a class="comImage" href="<?= $redirectCommission . $id ?>"></a>
                    <?php
                    }
                    if (!$userComissionResult) {
                    ?>
                        <p>
                            No commission in this category yet!
                        </p>
                    <?php
                    }
                    ?>
                </div>
                <div class="scroll"></div>
            </div>
            <div class="space"></div>
        </div>
        <?php
        require('essential/footer.php');
        ?>
    </main>
</body>

</html>