<?php
$requestBody = file_get_contents('php://input');
$request = (array) json_decode($requestBody);

$tags = [
    'status' => 'Failed'
];

if (!empty($request['tags'])) {
    $searching = DB->prepare("SELECT tag FROM guild_tags WHERE tag LIKE ?");
    $searching->execute([
        '%' . $request['tags'] . '%'
    ]);
    $searchingResult = $searching->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($searchingResult)) {
        $tags = [];

        foreach ($searchingResult as $search) {
            $tags[] = $search['tag'];
        }
    }
}

echo json_encode($tags);
die;
