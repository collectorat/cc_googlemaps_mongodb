<?php

$db = new PDO('mysql:host=localhost;dbname=site', 'homestead', 'secret');

$mongodb = new MongoClient('mongodb://localhost:27017');
$mongodb = $mongodb->selectDB('codecourse');
