<?php
$requestBody = file_get_contents('php://input');
$request = (array) json_decode($requestBody);

switch (false) {
    case empty($_POST):
        $listValid = [
            'description' => $_POST['description'],
            'nsfw' => 0,
            'public' => 0,
        ];
        $skipOverwrite = [
            'description',
        ];

        foreach ($_POST as $key => $value) {
            if (!array_key_exists($key, $listValid)) continue;
            if ($value === 'on' && !array_key_exists($key, $skipOverwrite)) $value = 1;

            if (isset($listValid[$key])) $listValid[$key] = $value;
        }

        $data = [];
        $sets = [];

        foreach ($listValid as $key => $value) {
            if (!array_key_exists($key, $listValid)) continue;

            $data[] = "$key=?";
            $sets[] = $value;
        }

        $guildDataCreate = DB->prepare("UPDATE guilds LEFT JOIN guild_tags ON guilds.id = guild_tags.id SET " . implode(', ', $data) . ", review_status=? WHERE guilds.id=?");
        $guildDataCreate->execute([
            ...$sets,
            2,
            $_POST['id']
        ]);

        header('Location: /settings');
        break;
    case empty($request):
        $findGuild = DB->prepare("SELECT * FROM guilds LEFT JOIN guild_tags ON guilds.id = guild_tags.id WHERE guilds.id=?");
        $findGuild->execute([
            $request['id']
        ]);
        $findGuildResult = $findGuild->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($findGuildResult, JSON_PRETTY_PRINT);
        break;
}

header("Content-Type: application/json");
die;
