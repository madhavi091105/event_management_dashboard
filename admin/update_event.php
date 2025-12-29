<?php
session_start();
require_once "../config/database.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $id = $_POST['event_id']; // Hidden field se aayega
    $title = $_POST['title'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];

    $sql = "UPDATE events SET title = :title, location = :location, event_date = :event_date WHERE id = :id";
    $stmt = $db->prepare($sql);
    
    $stmt->execute([
        ':title' => $title,
        ':location' => $location,
        ':event_date' => $event_date,
        ':id' => $id
    ]);

    header("Location: dashboard.php?msg=Updated");
    exit;
}