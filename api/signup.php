<?php
session_start();
require_once __DIR__ . "/../config/db.php";

ob_start(); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = htmlspecialchars($_POST['name'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
    $age = htmlspecialchars($_POST['age'] ?? '');
    $phone_number = htmlspecialchars($_POST['phone_number'] ?? '');
    
    
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (!isset($pdo)) {
        die("Database connection not found.");
    }

    try {
        $query = "INSERT INTO users (name, email, password, age, phone_number, is_admin) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$name, $email, $password, $age, $phone_number, $is_admin]);

        $_SESSION['user'] = [
            'name' => $name,
            'email' => $email,
            'is_admin' => $is_admin
        ];

        if ($is_admin == 1) {
            header("Location: ../admin/dashboard.php"); //  admin dashboard link
        } else {
            header("Location: ../public/pages/dashboard.php"); //  normal user dashboard link
        }
        exit();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../public/pages/register.php");
    exit();
}

ob_end_flush();
?>
