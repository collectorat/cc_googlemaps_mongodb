<?php

require_once 'app/bootstrap.php';

header('Content-Type: application/json');

$mongoStores = $mongodb->selectCollection('stores');

if (!isset($_GET['lat'], $_GET['lng'])) {
    echo json_encode([
        'error' => 'No lat/lng provided',
    ]);

    die();
}

$lat = (float) $_GET['lat'];
$lng = (float) $_GET['lng'];

$stores = $mongodb->command([
    'geoNear' => 'stores',
    'near' => [
        'type' => 'Point',
        'coordinates' => [
            $lng,
            $lat
        ],
    ],
    'spherical' => true,
    'maxDistance' => 10000,
]);

$results = array_map(function ($store) {
    $obj = $store['obj'];

    return [
        'distance' => [
            'meters' => $store['dis'],
            'miles' => number_format($store['dis'] / 1069.344, 2),
        ],
        'address' => $obj['address'],
        'telephone' => $obj['telephone'],
        'location' => [
            'lat' => $obj['location']['coordinates'][1],
            'lng' => $obj['location']['coordinates'][0],
        ],
    ];

}, $stores['results']);

echo json_encode([
    'count' => count($results),
    'results' => $results,
]);
