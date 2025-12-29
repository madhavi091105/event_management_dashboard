<?php
session_start();
require_once "../config/database.php";

if (!isset($_SESSION["admin_id"])) {
    header("Location: login.php");
    exit;
}

$database = new Database();
$db = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST['title'];
    $location = $_POST['location'];
    $event_date = $_POST['event_date'];
    $description = $_POST['description'] ?? "";

    $sql = "INSERT INTO events (title, location, event_date, description, created_by) 
            VALUES (:title, :location, :event_date, :description, :created_by)";
    
    $stmt = $db->prepare($sql);
    
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':location', $location);
    $stmt->bindParam(':event_date', $event_date);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':created_by', $_SESSION["admin_id"]);

    if ($stmt->execute()) {
        header("Location: dashboard.php?success=1");
    } else {
        echo "Error: Event save nahi ho paya.";
    }
}
?>