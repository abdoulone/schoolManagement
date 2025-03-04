<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/examManagement/editCBTQuestions.php -->
<?php
require '../../config.php';

$examId = $_GET['examId'] ?? null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $questionId = $_POST['questionId'] ?? null;
    $question = $_POST['question'];
    $options = json_encode($_POST['options']);
    $correctAnswer = $_POST['correct_answer'];

    if ($questionId) {
        $stmt = $pdo->prepare("UPDATE cbt_questions SET question = ?, options = ?, correct_answer = ? WHERE id = ?");
        $stmt->execute([$question, $options, $correctAnswer, $questionId]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cbt_questions (exam_id, question, options, correct_answer) VALUES (?, ?, ?, ?)");
        $stmt->execute([$examId, $question, $options, $correctAnswer]);
    }

    header("Location: editCBTQuestions.php?examId=" . $examId);
    exit();
}

if ($examId) {
    $stmt = $pdo->prepare("SELECT * FROM cbt_questions WHERE exam_id = ?");
    $stmt->execute([$examId]);
    $questions = $stmt->fetchAll();
} else {
    $questions = [];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit CBT Questions</title>
    <script src="./tailwindcss.js"></script>
    <style type="text/tailwindcss">
    </style>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Edit CBT Questions</h1>
        <form action="editCBTQuestions.php?examId=<?= $examId ?>" method="POST">
            <input type="hidden" name="questionId" value="">
            <div class="mb-4">
                <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                <textarea id="question" name="question" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
            </div>
            <div class="mb-4">
                <label for="options" class="block text-sm font-medium text-gray-700">Options (JSON format)</label>
                <textarea id="options" name="options" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
            </div>
            <div class="mb-4">
                <label for="correct_answer" class="block text-sm font-medium text-gray-700">Correct Answer</label>
                <input type="text" id="correct_answer" name="correct_answer" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Save Question</button>
        </form>

        <h2 class="text-xl font-bold mt-8 mb-4">Existing Questions</h2>
        <ul>
            <?php foreach ($questions as $question): ?>
                <li class="mb-4">
                    <strong>Question:</strong> <?= htmlspecialchars($question['question']) ?><br>
                    <strong>Options:</strong> <?= htmlspecialchars($question['options']) ?><br>
                    <strong>Correct Answer:</strong> <?= htmlspecialchars($question['correct_answer']) ?><br>
                    <a href="editCBTQuestions.php?examId=<?= $examId ?>&questionId=<?= $question['id'] ?>" class="text-blue-500 hover:underline">Edit</a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>

</html>