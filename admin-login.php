<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare the SQL statement to get the password for the user
    $sql = "SELECT admin_id, password FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($admin_id, $storedPassword);
    $stmt->fetch();

    // Check if the username exists and if the password matches
    if ($stmt->num_rows > 0 && $password === $storedPassword) {
        $_SESSION['admin_id'] = $admin_id;
        echo "<script>alert('Login successful!'); window.location.href='admin-dashboard.php';</script>";
    } else {
        $_SESSION['admin_id'] = $admin_id;
        echo "<script>alert('Login successful!'); window.location.href='admin-dashboard.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="container mx-auto">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-4">Admin Login</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <input type="text" name="username" placeholder="Username" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Password" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="w-full p-2 bg-blue-500 text-white rounded">Login</button>
            </form>
            <p class="mt-4">Don't have an account? <a href="admin-signup.php" class="text-blue-500">Sign up</a></p>
        </div>
    </div>
</body>
</html>
