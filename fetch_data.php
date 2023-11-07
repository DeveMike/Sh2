<?php
// fetch_data.php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require 'includes/dbconnect.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
    exit;
}

$json = file_get_contents("php://input");
$data = json_decode($json);

if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid JSON data received."]);
    exit;
}

if (!isset($data->city) || empty($data->city)) {
    echo json_encode(["status" => "error", "message" => "City not selected."]);
    exit;
}

$city = $data->city;
$user_id = $_SESSION['user_id'] ?? null;

try {
    $conn->beginTransaction();

    $stmt = $conn->prepare("SELECT DISTINCT address FROM Jumpat WHERE SUBSTRING_INDEX(address, ' ', -1) = :city");
    $stmt->execute([':city' => $city]);
    $addresses = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $conn->prepare("SELECT DISTINCT name FROM Jumpat WHERE SUBSTRING_INDEX(address, ' ', -1) = :city");
    $stmt->execute([':city' => $city]);
    $classNames = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $stmt = $conn->prepare("SELECT DISTINCT j.instructor_id, o.name FROM Jumpat j JOIN Ohjaajat o ON j.instructor_id = o.instructor_id WHERE SUBSTRING_INDEX(j.address, ' ', -1) = :city");
    $stmt->execute([':city' => $city]);
    $instructors = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $response = [
        'addresses' => $addresses,
        'classNames' => $classNames,
        'instructors' => $instructors
    ];

    $whereClauses = ["SUBSTRING_INDEX(address, ' ', -1) = :city"];
    $params = [':city' => $city];

    if (isset($data->address) && !empty($data->address)) {
        $whereClauses[] = "address LIKE :address";
        $params[':address'] = '%' . $data->address . '%';
    }
    if (isset($data->instructor) && !empty($data->instructor)) {
        $whereClauses[] = "instructor_id = :instructor_id";
        $params[':instructor_id'] = $data->instructor;
    }
    if (isset($data->name) && !empty($data->name)) {
        $whereClauses[] = "name = :name";
        $params[':name'] = $data->name;
    }
    if (isset($data->startTime) && isset($data->endTime) && !empty($data->startTime) && !empty($data->endTime)) {
        $whereClauses[] = "start_time BETWEEN :start_time AND :end_time";
        $params[':start_time'] = $data->startTime;
        $params[':end_time'] = $data->endTime;
    }

    $where = implode(' AND ', $whereClauses);
    $sql = "SELECT j.*, 
               COUNT(v.class_id) as reservation_count, 
               SUM(v.customer_id = :user_id) as user_has_reservation
        FROM Jumpat j
        LEFT JOIN Varaukset v ON j.class_id = v.class_id
        WHERE $where
        GROUP BY j.class_id";

    $user_id = $_SESSION['user_id'] ?? null;
    $params[':user_id'] = $user_id;
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($classes)) {
        $response['classes'] = $classes;
    }

    $conn->commit();
    echo json_encode($response);
} catch (PDOException $e) {
    $conn->rollBack();
    echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    exit;
}
