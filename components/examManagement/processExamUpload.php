<!-- filepath: /C:/xampp/htdocs/schoolManagement/components/examManagement/processExamUpload.php -->
<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'teacher')) {
    header("Location: ../../login.php");
    exit();
}

require '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $examName = $_POST['examName'];
    $class = $_POST['class'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $term = $_POST['term'];
    $section = $_POST['section'];
    $session = $_POST['session'];
    $opening_date = $_POST['opening_date'];
    $closing_date = $_POST['closing_date'];
    $class_average = $_POST['class_average'];

    // Insert exam data into the database
    $stmt = $pdo->prepare("INSERT INTO exams (exam_name, class, date, time, term, section, session, opening_date, closing_date, class_average) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$examName, $class, $date, $time, $term, $section, $session, $opening_date, $closing_date, $class_average]);

    header("Location: addExams.php");
    exit();
} else {
    echo "Invalid request method.";
}
?>