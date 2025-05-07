<?php
include_once "../includes/connect.php"; // Adjust path if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];

    if ($action === 'add') {
        $stmt = $pdo->prepare("INSERT INTO patients 
            (full_name, dob, gender, contact_number, email, academic_year) 
            VALUES (:full_name, :dob, :gender, :contact_number, :email, :academic_year)");

        $stmt->execute([
            ':full_name' => $_POST['full_name'],
            ':dob' => $_POST['dob'],
            ':gender' => $_POST['gender'],
            ':contact_number' => $_POST['contact_number'],
            ':email' => $_POST['email'],
            ':academic_year' => $_POST['academic_year']
        ]);

        header("Location: patients.php?success=Patient added");
        exit();

    } elseif ($action === 'update') {
        $stmt = $pdo->prepare("UPDATE patients SET 
            full_name = :full_name,
            dob = :dob,
            gender = :gender,
            contact_number = :contact_number,
            email = :email,
            academic_year = :academic_year
            WHERE id = :id");

        $stmt->execute([
            ':full_name' => $_POST['full_name'],
            ':dob' => $_POST['dob'],
            ':gender' => $_POST['gender'],
            ':contact_number' => $_POST['contact_number'],
            ':email' => $_POST['email'],
            ':academic_year' => $_POST['academic_year'],
            ':id' => $_POST['id']
        ]);

        header("Location: patients.php?success=Patient updated");
        exit();

    } elseif ($action === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM patients WHERE id = :id");
        $stmt->execute([':id' => $_GET['id']]);

        header("Location: patients.php?success=Patient deleted");
        exit();
    }
}
?>
