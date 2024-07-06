<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// On vérifie si la méthode envoyée avec la requette est GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // *** On récupere le paramétre entré par l'utilisateur ***
    $param = $_GET['param'] ?? '';

    // *** On consome l'api ***
    $apiUrl = 'https://api.dictionaryapi.dev/api/v2/entries/en/'.urlencode($param);

    // *** Vérifier si le paramètre est vide ***
    if (empty($param)) {
        http_response_code(400);
        echo json_encode(['error' => 'Le paramètre est requis']);
        exit;
    }

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $apiResponse = curl_exec($ch);
    curl_close($ch);

    // On vérifie si notre api a retournée false?
    if ($apiResponse === false) {
        http_response_code(500);
        echo json_encode(['error' => "Une erreur lors l'exécution de notre api"]);
        exit;
    }

    // On décode la réponse
    $data = json_decode($apiResponse, true);
    $data = $data[0]['meanings']['0']['definitions'][0]['example'];

    // On renvoie la réponse sous forme d'Api
    echo json_encode($data);
} else {
    echo json_encode(['message' => 'La méthode envoyée est incorrecte']);
}
