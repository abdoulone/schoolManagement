<!-- filepath: /c:/xampp/htdocs/schoolManagement/addLesson.php -->
<?php
require 'config.php';

session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $term = $_POST['term'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("INSERT INTO lessons (title, subject, class, section, term, content, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, NOW(), 1)");
    $stmt->execute([$title, $subject, $class, $section, $term, $content]);

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lesson</title>
    <link rel="stylesheet" href="path/to/tailwind.css">
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Add Lesson</h1>
        <form action="addLesson.php" method="POST">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <input type="text" id="subject" name="subject" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="class" class="block text-sm font-medium text-gray-700">Class</label>
                <input type="text" id="class" name="class" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                <input type="text" id="section" name="section" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                <input type="text" id="term" name="term" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea id="content" name="content" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Lesson</button>
        </form>
    </div>
</body>

</html>