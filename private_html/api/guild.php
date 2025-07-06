<?php
$requestBody = file_get_contents('php://input');
$request = (array) json_decode($requestBody);

$response = [
    'status' => 'Failed'
];

if (!empty($request)) {
    switch ($request['type']) {
        case 'display_servers':
            $findGuild = DB->prepare("SELECT * FROM guilds LEFT JOIN guild_tags ON guilds.id = guild_tags.id WHERE guilds.id=?");
            $findGuild->execute([
                $request['id']
            ]);
            $findGuildResult = $findGuild->fetchAll(PDO::FETCH_ASSOC);

            $createTag = [];

            foreach ($findGuildResult as $key => $value) {
                $createTag[] = $value['tag'];
            }

            $findGuildResult[0]['tag'] = $createTag;
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

            $data = [
                "review_status=?"
            ];
            $sets = [
                2
            ];

            foreach ($listValid as $key => $value) {
                if (!array_key_exists($key, $listValid)) continue;

                $data[] = "$key=?";
                $sets[] = $value;
            }

            $update = DB->prepare("UPDATE guilds SET " . implode(', ', $data) . " WHERE id=?");
            $update->execute([
                ...$sets,
                $request['id']
            ]);

            $delete = DB->prepare("DELETE FROM guild_tags WHERE id=?");
            $delete->execute([
                $request['id']
            ]);

            if (!empty($request['tag'])) {
                $amount = 0;

                foreach (explode(', ', $request['tag']) as $value) {
                    $amount++;

                    if ($amount === 5) continue;

                    $insert = DB->prepare("INSERT INTO guild_tags (id, tag) VALUES (?, ?)");
                    $insert->execute([
                        $request['id'],
                        $value,
                    ]);
                }
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
