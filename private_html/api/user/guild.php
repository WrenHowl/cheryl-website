<?php
$requestBody = file_get_contents('php://input');
$request = (array) json_decode($requestBody);

$response = [
    'status' => 'Failed'
];

if (!empty($request)) {
    switch ($request['type']) {
        case 'display_servers':
            $findGuild = DB->prepare("SELECT * FROM guilds LEFT JOIN guild_tags ON guilds.id=guild_tags.id WHERE guilds.id=?");
            $findGuild->execute([
                $request['id']
            ]);
            $findGuildResult = $findGuild->fetchAll(PDO::FETCH_ASSOC);

            $findGuildResult[0]['id'] = $request['id'];

            if (count($findGuildResult) > 1) {
                $createTag = [];

                foreach ($findGuildResult as $key => $value) {
                    $createTag[] = $value['tag'];
                }

                $findGuildResult[0]['tag'] = $createTag;
            }

            $response = $findGuildResult[0];
            break;
        case 'submit_server':
            $listValid = [
                'description' => '',
                'nsfw' => 0,
                'public' => 0,
            ];

            foreach ($request as $key => $value) {
                if ($key === 'description' && $value === '') {
                    $listValid[$key] = null;
                    continue;
                }

                if (isset($listValid[$key])) $listValid[$key] = $value;
            }

            $dataGuild = [];
            $setsGuild = [];

            foreach ($listValid as $key => $value) {
                if (!array_key_exists($key, $listValid)) continue;

                $dataGuild[] = "$key=?";
                $setsGuild[] = $value;
            }

            $update = DB->prepare("UPDATE guilds SET " . implode(', ', $dataGuild) . ", review_status=? WHERE id=?");
            $update->execute([
                ...$setsGuild,
                2,
                $request['id']
            ]);

            $delete = DB->prepare("DELETE FROM guild_tags WHERE id=?");
            $delete->execute([
                $request['id']
            ]);

            if (!empty($request['tag'])) {
                $tagsArray = explode(', ', $request['tag']);

                $dataTag = [];
                $setsTag = [];

                foreach ($tagsArray as $value) {
                    if (count($setsTag) >= 5) continue;
                    if (in_array($value, $setsTag)) continue;

                    $dataTag[] = "('" . $request['id'] . "', ?)";
                    $setsTag[] = $value;
                }

                $insert = DB->prepare("INSERT INTO guild_tags (id, tag) VALUES " . implode(', ', $dataTag));
                $insert->execute([
                    ...$setsTag
                ]);
            }

            $response = [
                'status' => 'Ok'
            ];
            break;
    }
}

echo json_encode($response);
header("Content-Type: application/json");
die;
