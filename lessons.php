<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/lessons/lessons.php -->
<?php
require '../config.php';

session_start();

// if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin' || $_SESSION['role'] !== 'teacher') {
//     header("Location: ../index.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $subject = $_POST['subject'];
    $class = $_POST['class'];
    $section = $_POST['section'];
    $term = $_POST['term'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO lessons (title, subject, class, section, term, content, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, NOW(), 1)");
    $stmt->execute([$title, $subject, $class, $section, $term, $content]);

    header("Location: ./lessons.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM lessons WHERE is_active = 1 ORDER BY created_at DESC");
$stmt->execute();
$lessons = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lessons</title>
    <script src="../tailwindcss.js"></script>
    <style type="text/tailwindcss">
        @theme {
            --color-clifford: #da373d;
            --color-primary: rgb(168,81,138);
            --color-secondary: rgb(205,122,203);
        }
        .fc-button {
            padding: 0rem !important;
        }
        #fc-dom-1 {
            font-size: 1.1rem !important;
        }
    </style>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Lessons</h1>
        <?php if ($_SESSION['role'] === "admin" || $_SESSION['role'] === 'teacher') { ?>
            <form action="lessons.php" method="POST">
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
        <?php }; ?>
        <h2 class="text-xl font-bold mt-8 mb-4">Existing Lessons</h2>
        <ul>
            <?php foreach ($lessons as $lesson): ?>
                <li class="mb-4">
                    <strong>Title:</strong> <?= htmlspecialchars($lesson['title']) ?><br>
                    <strong>Subject:</strong> <?= htmlspecialchars($lesson['subject']) ?><br>
                    <strong>Class:</strong> <?= htmlspecialchars($lesson['class']) ?><br>
                    <strong>Section:</strong> <?= htmlspecialchars($lesson['section']) ?><br>
                    <strong>Term:</strong> <?= htmlspecialchars($lesson['term']) ?><br>
                    <strong>Content:</strong> <?= htmlspecialchars($lesson['content']) ?><br>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>