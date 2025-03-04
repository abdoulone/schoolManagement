<!-- filepath: /c:/xampp/htdocs/schoolManagement/addSubject.php -->
<?php
require 'config.php';

session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $classes = json_encode($_POST['classes']);
    $teacher = $_POST['teacher'];

    $stmt = $pdo->prepare("INSERT INTO subjects (name, classes, teacher, created_at, is_active) VALUES (?, ?, ?, NOW(), 1)");
    $stmt->execute([$name, $classes, $teacher]);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Subject</title>
    <link rel="stylesheet" href="path/to/tailwind.css">
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Add Subject</h1>
        <form action="addSubject.php" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Subject Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="classes" class="block text-sm font-medium text-gray-700">Classes (comma-separated)</label>
                <input type="text" id="classes" name="classes" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="teacher" class="block text-sm font-medium text-gray-700">Teacher ID</label>
                <input type="number" id="teacher" name="teacher" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Subject</button>
        </form>
    </div>
</body>

</html>