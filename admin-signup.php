<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Check if the passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Insert the data without hashing the password
        $sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful!'); window.location.href='admin-login.php';</script>";
        } else {
            echo "<script>alert('Signup failed!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="container mx-auto">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-4">Admin Signup</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <input type="text" name="username" placeholder="Username" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Password" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="w-full p-2 bg-blue-500 text-white rounded">Signup</button>
            </form>
            <p class="mt-4">Already have an account? <a href="admin-login.php" class="text-blue-500">Login</a></p>
        </div>
    </div>
</body>
</html>
