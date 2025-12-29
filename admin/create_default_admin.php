<?php
require_once "../config/database.php";

/* create DB object */
$database = new Database();
$db = $database->getConnection();

/* default admin details */
$username = "admin";
$email    = "admin@example.com";
$password = "admin123";

/* hash password */
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

/* check if admin exists */
$query = "SELECT id FROM admins WHERE username = :username";
$stmt = $db->prepare($query);
$stmt->bindParam(":username", $username);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    echo "✅ Admin already exists in database";
    exit;
}

/* insert admin */
$insert = "INSERT INTO admins (username, email, password)
           VALUES (:username, :email, :password)";
$stmt = $db->prepare($insert);
$stmt->bindParam(":username", $username);
$stmt->bindParam(":email", $email);
$stmt->bindParam(":password", $hashedPassword);

if ($stmt->execute()) {
    echo "🎉 Default admin created successfully";
} else {
    echo "❌ Failed to create admin";
}
?>
