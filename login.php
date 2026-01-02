<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = $_POST['student_id'];
    $password   = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->execute([$student_id]);

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch();

        if (password_verify($password, $user['password_hash'])) {
            $_SESSION['logged_in'] = true;
            $_SESSION['name'] = $user['full_name'];

            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "Student ID not found";
    }
}
?>

<link rel="stylesheet" href="style.css">

<form method="POST">
    <h2>Login</h2>

    Student ID:<br>
    <input type="text" name="student_id" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>

<?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>

<p>New student? <a href="register.php">Register here</a></p>
