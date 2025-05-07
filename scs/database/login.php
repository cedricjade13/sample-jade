<?php
session_start();
include('config.php'); // Adjust if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, account FROM users_acc WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password, $account);
        $stmt->fetch();
    
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['account'] = $account;
    
            if ($account === 'admin') {
                header("Location: ../admin/index.php");
            } elseif ($account === 'staff') {
                header("Location: ../staff/dashboard.php");
            } else {
                header("Location: login.php?error=Invalid account type");
            }
        } else {
            header("Location: login.php?error=Invalid password");
        }
    } else {
        header("Location: login.php?error=No user found");
    }
    

    $stmt->close();
    $conn->close();
    exit();
} else {
    header("Location: login.php");
    exit();
}
