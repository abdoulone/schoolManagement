<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/examManagement/processAddCBTQuestion.php -->
<?php
require '../../database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exam_id = $_POST['exam_id'];
    $question = $_POST['question'];
    $options = $_POST['options'];
    $correct_answer = $_POST['correct_answer'];

    $stmt = $pdo->prepare("INSERT INTO cbt_questions (exam_id, question, options, correct_answer, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$exam_id, $question, $options, $correct_answer]);

    header("Location: addCBTQuestion.php");
    exit();
}
?>