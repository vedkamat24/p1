<?php
include 'db.php';
session_start();

if (!isset($_SESSION['employee_id'])) {
    header("Location: user-login.php");
    exit();
}

$employee_id = $_SESSION['employee_id'];

// Fetch the plants allotted to the logged-in user
$plants_sql = "SELECT p.plant_id, p.plant_name, p.plant_type FROM plants AS p
               INNER JOIN medicine_administration AS ma ON p.plant_id = ma.plant_id
               WHERE ma.administered_by = ?";
$plants_stmt = $conn->prepare($plants_sql);
$plants_stmt->bind_param("i", $employee_id);
$plants_stmt->execute();
$plants_result = $plants_stmt->get_result();
$plants = $plants_result->fetch_all(MYSQLI_ASSOC);

// Handle the form submission for adding plant health information, medicine administration, and new medicine types
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_medicine'])) {
        // Add new medicine type
        $medicine_name = $_POST['medicine_name'];
        $dosage = $_POST['dosage'];
        $expiry_date = $_POST['expiry_date'];

        // Insert new medicine into the medicines table
        $medicine_sql = "INSERT INTO medicines (medicine_name, dosage, expiry_date) VALUES (?, ?, ?)";
        $medicine_stmt = $conn->prepare($medicine_sql);
        $medicine_stmt->bind_param("sss", $medicine_name, $dosage, $expiry_date);
        $medicine_stmt->execute();

        echo "<script>alert('New medicine added successfully!'); window.location.href='user-dashboard.php';</script>";
    }

    // Add plant health and medicine administration information
    if (isset($_POST['add_health_medicine'])) {
        $plant_id = $_POST['plant_id'];
        $health_status = $_POST['health_status'];
        $medicine_id = $_POST['medicine_id'];
        $administered_on = date('Y-m-d H:i:s');

        // Insert plant health information
        $health_sql = "INSERT INTO plant_health (plant_id, health_status, last_updated) VALUES (?, ?, ?)";
        $health_stmt = $conn->prepare($health_sql);
        $health_stmt->bind_param("iss", $plant_id, $health_status, $administered_on);
        $health_stmt->execute();

        // Insert medicine administration information
        $medicine_sql = "INSERT INTO medicine_administration (plant_id, medicine_id, administered_by, administered_on) VALUES (?, ?, ?, ?)";
        $medicine_stmt = $conn->prepare($medicine_sql);
        $medicine_stmt->bind_param("iiis", $plant_id, $medicine_id, $employee_id, $administered_on);
        $medicine_stmt->execute();

        echo "<script>alert('Information added successfully!'); window.location.href='user-dashboard.php';</script>";
    }
}

// Fetch all medicines for the dropdown
$medicines_sql = "SELECT medicine_id, medicine_name FROM medicines";
$medicines_result = $conn->query($medicines_sql);
$medicines = $medicines_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .container { max-width: 1200px; margin: 0 auto; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="container my-10">
        <h1 class="text-3xl font-bold mb-5">User Dashboard</h1>

        <h2 class="text-2xl font-bold mb-3">Allotted Plants</h2>
        <table class="table-auto w-full mb-10">
            <thead>
                <tr>
                    <th class="px-4 py-2">Plant ID</th>
                    <th class="px-4 py-2">Plant Name</th>
                    <th class="px-4 py-2">Plant Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plants as $plant): ?>
                    <tr>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($plant['plant_id']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($plant['plant_name']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($plant['plant_type']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Add New Medicine Section -->
        <h2 class="text-2xl font-bold mb-3">Add New Medicine Type</h2>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="medicine_name" class="block mb-2">Medicine Name</label>
                <input type="text" id="medicine_name" name="medicine_name" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="dosage" class="block mb-2">Dosage</label>
                <input type="text" id="dosage" name="dosage" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="expiry_date" class="block mb-2">Expiry Date</label>
                <input type="date" id="expiry_date" name="expiry_date" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <button type="submit" name="add_medicine" class="w-full p-2 bg-blue-500 text-white rounded">Add Medicine</button>
        </form>

        <h2 class="text-2xl font-bold mb-3 mt-10">Add Plant Health Information and Administer Medicine</h2>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="plant_id" class="block mb-2">Plant ID</label>
                <select id="plant_id" name="plant_id" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php foreach ($plants as $plant): ?>
                        <option value="<?php echo htmlspecialchars($plant['plant_id']); ?>"><?php echo htmlspecialchars($plant['plant_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="health_status" class="block mb-2">Health Status</label>
                <input type="text" id="health_status" name="health_status" class="w-full p-2 border border-gray-300 rounded" required>
            </div>
            <div class="mb-4">
                <label for="medicine_id" class="block mb-2">Medicine</label>
                <select id="medicine_id" name="medicine_id" class="w-full p-2 border border-gray-300 rounded" required>
                    <?php foreach ($medicines as $medicine): ?>
                        <option value="<?php echo htmlspecialchars($medicine['medicine_id']); ?>"><?php echo htmlspecialchars($medicine['medicine_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="add_health_medicine" class="w-full p-2 bg-green-500 text-white rounded">Add Information</button>
        </form>

        <h2 class="text-2xl font-bold mb-3 mt-10">Plant Management</h2>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2">Plant ID</th>
                    <th class="px-4 py-2">Plant Name</th>
                    <th class="px-4 py-2">Employee ID</th>
                    <th class="px-4 py-2">Medicine Name</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $management_sql = "SELECT p.plant_id, p.plant_name, ma.administered_by, m.medicine_name
                                   FROM plants AS p
                                   INNER JOIN medicine_administration AS ma ON p.plant_id = ma.plant_id
                                   INNER JOIN medicines AS m ON ma.medicine_id = m.medicine_id
                                   WHERE ma.administered_by = ?";
                $management_stmt = $conn->prepare($management_sql);
                $management_stmt->bind_param("i", $employee_id);
                $management_stmt->execute();
                $management_result = $management_stmt->get_result();

                while ($row = $management_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['plant_id']) . "</td>";
                    echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['plant_name']) . "</td>";
                    echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['administered_by']) . "</td>";
                    echo "<td class='border px-4 py-2'>" . htmlspecialchars($row['medicine_name']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>

<?php
// Close the database connection
close_connection();
?>
