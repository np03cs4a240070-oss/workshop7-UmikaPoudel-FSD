<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $student_id = trim($_POST['student_id']);
    $full_name  = trim($_POST['full_name']);
    $password   = $_POST['password'];

    if (empty($student_id) || empty($full_name) || empty($password)) {
        $error = "All fields are required";
    } else {
        $check = $pdo->prepare("SELECT id FROM students WHERE student_id = ?");
        $check->execute([$student_id]);

        if ($check->rowCount() > 0) {
            $error = "Student ID already exists";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $pdo->prepare(
                "INSERT INTO students (student_id, full_name, password_hash)
                 VALUES (?, ?, ?)"
            );
            $stmt->execute([$student_id, $full_name, $hash]);

            $success = "Registration successful! You can now log in.";
        }
    }
}
?>

<link rel="stylesheet" href="style.css">

<form method="POST">
    <h2>Student Registration</h2>

    Student ID:<br>
    <input type="text" name="student_id" required><br><br>

    Full Name:<br>
    <input type="text" name="full_name" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Register</button>
</form>

<?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>
<?php if (!empty($success)) echo "<p style='color:green'>$success</p>"; ?>

<p>Already registered? <a href="login.php">Login here</a></p>
