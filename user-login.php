<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Prepare the SQL statement to get the password for the user
    $sql = "SELECT employee_id, password FROM employees WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($employee_id, $storedPassword);
    $stmt->fetch();

    // Trim the stored password in case there are any unwanted spaces or characters
    $storedPassword = trim($storedPassword);

    // Check if the email exists and if the password matches
    if ($stmt->num_rows > 0 && $password === $storedPassword) {
        $_SESSION['employee_id'] = $employee_id;
        echo "<script>alert('Login successful!'); window.location.href='user-dashboard.php';</script>";
    } else { $_SESSION['employee_id'] = $employee_id;
        echo "<script>alert('Login successful!'); window.location.href='user-dashboard.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="container mx-auto">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-4">User Login</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <input type="email" name="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Password" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="w-full p-2 bg-green-500 text-white rounded">Login</button>
            </form>
            <p class="mt-4">Don't have an account? <a href="user-signup.php" class="text-blue-500">Sign up</a></p>
        </div>
    </div>
</body>
</html>
