<?php
include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $plant_id = $_POST['plant_id_med'];
    $medicine_id = $_POST['medicine_id'];
    $administered_by = $_SESSION['employee_id'];
    $administered_on = date('Y-m-d H:i:s');

    $sql = "INSERT INTO medicine_administration (plant_id, medicine_id, administered_by, administered_on) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiis", $plant_id, $medicine_id, $administered_by, $administered_on);

    if ($stmt->execute()) {
        echo "<script>alert('Medicine administered successfully!'); window.location.href='user-dashboard.php';</script>";
    } else {
        echo "<script>alert('Failed to administer medicine!'); window.location.href='user-dashboard.php';</script>";
    }
}
?>
