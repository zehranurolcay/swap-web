<?php
require '../vendor/autoload.php'; 

use Dotenv\Dotenv;

$data = json_decode(file_get_contents('php://input'), true);

$jwt = $_GET['jwt'];

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();


if ($jwt == $_ENV['JWT']) {
    echo json_encode(['success' => true]);
}
else
{
    echo json_encode(['success' => false]);
}
?>
