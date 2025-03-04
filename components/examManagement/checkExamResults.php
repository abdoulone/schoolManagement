<?php
session_start();
require '../../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

$students = [];
$classes = [];

if ($user_role === 'student') {
    $stmt = $pdo->prepare("SELECT id, name FROM students WHERE id = ?");
    $stmt->execute([$user_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($user_role === 'parent') {
    $stmt = $pdo->prepare("SELECT id, name FROM students WHERE parent_id = ?");
    $stmt->execute([$user_id]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($user_role === 'teacher') {
    $stmt = $pdo->prepare("SELECT s.id, s.name FROM students s JOIN classes c ON s.class_id = c.id WHERE JSON_CONTAINS(c.teacher_ids, ?)");
    $stmt->execute([json_encode($user_id)]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} elseif ($user_role === 'admin') {
    $stmt = $pdo->query("SELECT id, name FROM classes");
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Exam Results</title>
    <script src="../../tailwindcss.js"></script>
    <style type="text/tailwindcss"></style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const studentSelect = document.getElementById('student_id');

            if (classSelect) {
                classSelect.addEventListener('change', function() {
                    const classId = this.value;

                    fetch(`fetchStudents.php?class_id=${classId}`)
                        .then(response => response.text())
                        .then(data => {
                            studentSelect.innerHTML = '<option value="">Select Student</option>' + data;
                        })
                        .catch(error => console.error('Error fetching students:', error));
                });
            }
        });
    </script>
</head>

<body>
    <div class="h-screen flex flex-col p-4">
        <h1 class="text-2xl font-bold mb-4 font-purple">Check Exam Results</h1>
        <form method="GET" action="viewResult.php" class="flex flex-col space-y-4">
            <?php if ($user_role === 'admin'): ?>
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="class_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php endif; ?>
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                <select name="student_id" id="student_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    <option value="">Select Student</option>
                    <?php if ($user_role !== 'admin'): ?>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo $student['name']; ?></option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <button type="submit" class="self-start px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Check Results</button>
        </form>
    </div>
</body>

</html>