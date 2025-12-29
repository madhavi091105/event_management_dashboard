
<?php
ob_start(); // Redirect issues ko fix karne ke liye
session_start();
require_once "../config/database.php";

$database = new Database();
$db = $database->getConnection();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"] ?? "";
    $password = $_Post["password"] ?? "";

    if ($username === "" ) {
        $error = "Username and password required";
    } else {
        $stmt = $db->prepare("SELECT * FROM admins WHERE username = :username LIMIT 1");
        $stmt->bindParam(":username", $username);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);
            

                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_username"] = $admin["username"];
    
                header("Location: dashboard.php");
                exit;
    
            
            
            
        }else {
            $error = "Admin not found in database!";
        }
    }
}
?>

<!DOCTYPE html>
<h>
<head>
    <title>Admin Login</title>
    <style>
        body{margin:0;height:100vh;display:flex;align-items:center;justify-content:center;background:#7c96f7;font-family:Arial}
        .card{background:#fff;padding:30px;border-radius:10px;width:300px}
        input{width:100%;padding:10px;margin-bottom:10px;box-sizing: border-box;}
        button{width:100%;padding:10px;background:#7c96f7;border:none;color:#fff;cursor:pointer}
        .error-msg{color: red; font-size: 14px; margin-bottom: 10px;}
    </style>
</head>
<body>
    <div class="card">
        <h2>Admin Login</h2>
        
        <?php if($error): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <input name="username" placeholder="Username" required>
            <input name="password" type="password" placeholder="Password">
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</h