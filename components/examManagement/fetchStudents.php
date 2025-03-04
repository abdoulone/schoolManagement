<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/examManagement/fetchStudents.php -->
<?php
require '../../config.php';

$class_id = $_GET['class_id'];
$exam_id = $_GET['exam_id'];

// Fetch students who do not have results for the selected exam
$stmt = $pdo->prepare("SELECT s.user_id, u.name 
                       FROM students s 
                       JOIN users u ON s.user_id = u.id 
                       WHERE s.class = ? 
                       AND s.user_id NOT IN (SELECT student_id FROM results WHERE exam_id = ?)");
$stmt->execute([$class_id, $exam_id]);
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($students as $student) {
    echo '<option value="' . $student['user_id'] . '">' . htmlspecialchars($student['name']) . '</option>';
}
?>
<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/examManagement/fetchSubjects.php -->
<?php
require '../../config.php';

if (isset($_GET['exam_id']) && isset($_GET['student_id'])) {
    $exam_id = $_GET['exam_id'];
    $student_id = $_GET['student_id'];

    // Fetch subjects that have not been recorded yet for the selected exam and student
    $stmt = $pdo->prepare("SELECT s.name FROM subjects s
                           LEFT JOIN results r ON s.name = r.subject AND r.exam_id = ? AND r.student_id = ?
                           WHERE r.subject IS NULL");
    $stmt->execute([$exam_id, $student_id]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subjects);
}
?>