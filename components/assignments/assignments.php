<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/assignments/assignments.php -->
<?php
require '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $session = $_POST['session'];
    $subject = $_POST['subject'];
    $due_date = $_POST['due_date'];
    $user_id = 1; // Assuming the teacher's user ID is 1 for this example

    $stmt = $pdo->prepare("INSERT INTO assignments (title, content, class, section, session, subject, due_date, created_at, is_active, user_id) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), 1, ?)");
    $stmt->execute([$title, $content, $class, $section, $session, $subject, $due_date, $user_id]);

    header("Location: assignments.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM assignments WHERE user_id = ?");
$stmt->execute([1]); // Assuming the teacher's user ID is 1 for this example
$assignments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignments</title>
    <script src="./tailwindcss.js"></script>
    <style type="text/tailwindcss"></style>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Assignments</h1>
        <form action="assignments.php" method="POST">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" id="title" name="title" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="content" class="block text-sm font-medium text-gray-700">Content</label>
                <textarea id="content" name="content" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
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
                <label for="session" class="block text-sm font-medium text-gray-700">Session</label>
                <input type="text" id="session" name="session" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                <input type="text" id="subject" name="subject" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="due_date" class="block text-sm font-medium text-gray-700">Due Date</label>
                <input type="date" id="due_date" name="due_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Assignment</button>
        </form>

        <h2 class="text-xl font-bold mt-8 mb-4">Existing Assignments</h2>
        <ul>
            <?php foreach ($assignments as $assignment): ?>
                <li class="mb-4">
                    <strong>Title:</strong> <?= htmlspecialchars($assignment['title']) ?><br>
                    <strong>Content:</strong> <?= htmlspecialchars($assignment['content']) ?><br>
                    <strong>Class:</strong> <?= htmlspecialchars($assignment['class']) ?><br>
                    <strong>Section:</strong> <?= htmlspecialchars($assignment['section']) ?><br>
                    <strong>Session:</strong> <?= htmlspecialchars($assignment['session']) ?><br>
                    <strong>Subject:</strong> <?= htmlspecialchars($assignment['subject']) ?><br>
                    <strong>Due Date:</strong> <?= htmlspecialchars($assignment['due_date']) ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>