<?php
$requestBody = file_get_contents('php://input');
$request = (array) json_decode($requestBody);

$noSearch = false;

if (!empty($request['tags'])) {
    $searching = DB->prepare("SELECT tags FROM guilds WHERE tags LIKE ?");
    $searching->execute([
        '%' . $request['tags'] . '%'
    ]);
    $searchingResult = $searching->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($searchingResult)) {
        $tags = [];

        foreach ($searchingResult as $search) {
            if (!isset($search['tags'])) continue;

            foreach (explode(', ', $search['tags']) as $tag) {
                if (in_array($tag, $tags)) continue;
                $tags[] = $tag;
            }
        }
    } else {
        $noSearch = true;
    }
} else {
    $noSearch = true;
}

if ($noSearch === true) {
    $tags = [
        'error' => 'The request is empty.'
    ];
}

echo json_encode($tags, JSON_PRETTY_PRINT);
die;
