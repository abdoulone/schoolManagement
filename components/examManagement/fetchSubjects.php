<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/examManagement/fetchSubjects.php -->
<?php
require '../../config.php';

if (isset($_GET['exam_id'])) {
    $exam_id = $_GET['exam_id'];

    // Fetch the class name associated with the selected exam
    $stmt = $pdo->prepare("SELECT class FROM exams WHERE id = ?");
    $stmt->execute([$exam_id]);
    $exam = $stmt->fetch(PDO::FETCH_ASSOC);
    $class_name = $exam['class'];

    // Fetch subjects for the selected class name from the classes table
    $stmt = $pdo->prepare("SELECT subjects FROM classes WHERE name = ?");
    $stmt->execute([$class_name]);
    $class = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $class['subjects'];
    // Clean up the subjects string
    $subjects_string = trim($class['subjects'], '[]');
    $subjects_string = str_replace('"', '', $subjects_string);
    $subjects = explode(',', $subjects_string);

    foreach ($subjects as $subject_name) {
        $subject_name = trim($subject_name);
        echo '<div class="mb-4">';
        echo '<h3 class="text-lg font-semibold">' . htmlspecialchars($subject_name) . '</h3>';
        echo '<div class="flex space-x-4">';
        echo '<div>';
        echo '<label for="ca_score_' . htmlspecialchars($subject_name) . '" class="block text-sm font-medium text-gray-700">CA Score</label>';
        echo '<input type="number" name="results[' . htmlspecialchars($subject_name) . '][ca_score]" id="ca_score_' . htmlspecialchars($subject_name) . '" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>';
        echo '</div>';
        echo '<div>';
        echo '<label for="exam_score_' . htmlspecialchars($subject_name) . '" class="block text-sm font-medium text-gray-700">Exam Score</label>';
        echo '<input type="number" name="results[' . htmlspecialchars($subject_name) . '][exam_score]" id="exam_score_' . htmlspecialchars($subject_name) . '" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p>No subjects found for the selected examination.</p>';
}
?>