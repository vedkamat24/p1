<?php
include 'db.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin-login.php"); // Redirect to admin login page if not logged in
    exit();
}

// Fetch plants from the database
$sqlPlants = "SELECT plant_id, plant_name, plant_type FROM plants";
$resultPlants = $conn->query($sqlPlants);
$plants = $resultPlants->fetch_all(MYSQLI_ASSOC);

// Fetch employees from the database
$sqlEmployees = "SELECT employee_id, name, email, phone_number FROM employees";
$resultEmployees = $conn->query($sqlEmployees);
$employees = $resultEmployees->fetch_all(MYSQLI_ASSOC);

// Handle form submissions for adding, deleting, and updating plants and employees
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action == 'add_plant') {
        // Add new plant
        $plant_name = $_POST['plant_name'];
        $plant_type = $_POST['plant_type'];
        $sql = "INSERT INTO plants (plant_name, plant_type) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $plant_name, $plant_type);
        $stmt->execute();
    } elseif ($action == 'delete_plant') {
        // Delete plant
        $plant_id = $_POST['plant_id'];
        $sql = "DELETE FROM plants WHERE plant_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $plant_id);
        $stmt->execute();
    } elseif ($action == 'update_plant') {
        // Update plant
        $plant_id = $_POST['plant_id'];
        $plant_name = $_POST['plant_name'];
        $plant_type = $_POST['plant_type'];
        $sql = "UPDATE plants SET plant_name = ?, plant_type = ? WHERE plant_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $plant_name, $plant_type, $plant_id);
        $stmt->execute();
    } elseif ($action == 'add_employee') {
        // Add new employee
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $password = $_POST['password'];
        $sql = "INSERT INTO employees (name, email, phone_number, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone_number, $password);
        $stmt->execute();
    } elseif ($action == 'delete_employee') {
        // Delete employee
        $employee_id = $_POST['employee_id'];
        $sql = "DELETE FROM employees WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $employee_id);
        $stmt->execute();
    } elseif ($action == 'update_employee') {
        // Update employee
        $employee_id = $_POST['employee_id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone_number = $_POST['phone_number'];
        $sql = "UPDATE employees SET name = ?, email = ?, phone_number = ? WHERE employee_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $name, $email, $phone_number, $employee_id);
        $stmt->execute();
    }

    // Refresh the page to reflect changes
    header("Location: admin-dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-blue-500 p-4">
        <div class="container mx-auto">
            <a href="admin-dashboard.php" class="text-white font-bold text-xl">Farm2 Admin Dashboard</a>
            <a href="admin-logout.php" class="float-right text-white">Logout</a>
        </div>
    </nav>

    <!-- Admin Dashboard -->
    <div class="container mx-auto p-8">
        <h2 class="text-3xl font-semibold mb-6">Admin Dashboard</h2>
        
        <!-- Plants Management -->
        <h3 class="text-2xl font-semibold mb-4">Manage Plants</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($plants as $plant): ?>
                <div class="bg-white p-4 border border-gray-300 shadow rounded-lg">
                    <h4 class="font-semibold text-xl"><?php echo $plant['plant_name']; ?></h4>
                    <p>Type: <?php echo $plant['plant_type']; ?></p>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="plant_id" value="<?php echo $plant['plant_id']; ?>">
                        <input type="text" name="plant_name" value="<?php echo $plant['plant_name']; ?>" class="w-full p-2 border border-gray-300 rounded mb-2">
                        <input type="text" name="plant_type" value="<?php echo $plant['plant_type']; ?>" class="w-full p-2 border border-gray-300 rounded mb-2">
                        <input type="hidden" name="action" value="update_plant">
                        <button type="submit" class="w-full p-2 bg-yellow-500 text-white rounded">Update</button>
                    </form>
                    <form method="POST" class="mt-2">
                        <input type="hidden" name="plant_id" value="<?php echo $plant['plant_id']; ?>">
                        <input type="hidden" name="action" value="delete_plant">
                        <button type="submit" class="w-full p-2 bg-red-500 text-white rounded">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Add New Plant -->
        <h3 class="text-2xl font-semibold mb-4">Add New Plant</h3>
        <form method="POST" class="bg-white p-4 border border-gray-300 shadow rounded-lg mb-8">
            <div class="mb-4">
                <input type="text" name="plant_name" placeholder="Plant Name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <input type="text" name="plant_type" placeholder="Plant Type" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <input type="hidden" name="action" value="add_plant">
            <button type="submit" class="w-full p-2 bg-green-500 text-white rounded">Add Plant</button>
        </form>

        <!-- Employees Management -->
        <h3 class="text-2xl font-semibold mb-4">Manage Employees</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($employees as $employee): ?>
                <div class="bg-white p-4 border border-gray-300 shadow rounded-lg">
                    <h4 class="font-semibold text-xl"><?php echo $employee['name']; ?></h4>
                    <p>Email: <?php echo $employee['email']; ?></p>
                    <p>Phone: <?php echo $employee['phone_number']; ?></p>
                    <form method="POST" class="mt-4">
                        <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
                        <input type="text" name="name" value="<?php echo $employee['name']; ?>" class="w-full p-2 border border-gray-300 rounded mb-2">
                        <input type="email" name="email" value="<?php echo $employee['email']; ?>" class="w-full p-2 border border-gray-300 rounded mb-2">
                        <input type="text" name="phone_number" value="<?php echo $employee['phone_number']; ?>" class="w-full p-2 border border-gray-300 rounded mb-2">
                        <input type="hidden" name="action" value="update_employee">
                        <button type="submit" class="w-full p-2 bg-yellow-500 text-white rounded">Update</button>
                    </form>
                    <form method="POST" class="mt-2">
                        <input type="hidden" name="employee_id" value="<?php echo $employee['employee_id']; ?>">
                        <input type="hidden" name="action" value="delete_employee">
                        <button type="submit" class="w-full p-2 bg-red-500 text-white rounded">Delete</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Add New Employee -->
        <h3 class="text-2xl font-semibold mb-4">Add New Employee</h3>
        <form method="POST" class="bg-white p-4 border border-gray-300 shadow rounded-lg">
            <div class="mb-4">
                <input type="text" name="name" placeholder="Name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <input type="email" name="email" placeholder="Email" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <input type="text" name="phone_number" placeholder="Phone Number" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <input type="password" name="password" placeholder="Password" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <input type="hidden" name="action" value="add_employee">
            <button type="submit" class="w-full p-2 bg-green-500 text-white rounded">Add Employee</button>
        </form>
    </div>
</body>
</html>
