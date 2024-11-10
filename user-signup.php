<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the input values
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Check if the passwords match
    if ($password !== $confirmPassword) {
        echo "<script>alert('Passwords do not match!');</script>";
    } else {
        // Insert the data without hashing the password
        $sql = "INSERT INTO employees (name, email, phone_number, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $password);

        if ($stmt->execute()) {
            echo "<script>alert('Signup successful!'); window.location.href='user-login.php';</script>";
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
    <title>User Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="container mx-auto">
        <div class="max-w-md mx-auto bg-white p-8 border border-gray-300 shadow-lg rounded-lg">
            <h2 class="text-2xl font-bold mb-4">User Signup</h2>
            <form method="POST" action="">
                <div class="mb-4">
                    <input type="text" name="name" placeholder="Full Name" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <input type="email" name="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <input type="tel" name="phone" placeholder="Phone Number" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="password" placeholder="Password" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <div class="mb-4">
                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="w-full p-2 border border-gray-300 rounded" required>
                </div>
                <button type="submit" class="w-full p-2 bg-green-500 text-white rounded">Signup</button>
            </form>
            <p class="mt-4">Already have an account? <a href="user-login.php" class="text-blue-500">Login</a></p>
        </div>
    </div>
</body>
</html>
