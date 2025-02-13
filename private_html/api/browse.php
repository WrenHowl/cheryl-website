<?php
$requestBody = file_get_contents('php://input');
$request = (array) json_decode($requestBody);

if (!empty($request['tags'])) {
    $searching = DB->prepare("SELECT name, avatar, id FROM guilds WHERE name LIKE ? LIMIT 5");
    $searching->execute([
        '%' . $request['tags'] . '%'
    ]);
    $searchingResult = $searching->fetchAll(PDO::FETCH_ASSOC);

    if (!$searchingResult) {
        $searchingResult = [
            'error' => '404'
        ];
    };
} else {
    $searchingResult = [
        'error' => '404'
    ];
}

echo json_encode($searchingResult, JSON_PRETTY_PRINT);
