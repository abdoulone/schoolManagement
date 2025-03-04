<?php
session_start();

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'teacher')) {
    header("Location: ../../login.php");
    exit();
}

require '../../config.php';

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];
    $student_id = $_POST['student_id'];
    $exam_id = $_POST['exam_id'];
    $ca_score = $_POST['ca_score'];
    $exam_score = $_POST['exam_score'];
    $status = $_POST['status'];
    $subjects = $_POST['subjects'];

    // Insert exam result into the database
    $stmt = $pdo->prepare("INSERT INTO results (student_id, exam_id, ca_score, exam_score, status, subject) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$student_id, $exam_id, $ca_score, $exam_score, $status, $subjects]);

    $add_message = "Exam result added successfully!";
}

// Fetch classes, students, and exams for the dropdowns
if ($user_role === 'teacher') {
    $classes = $pdo->prepare("SELECT * FROM classes WHERE JSON_CONTAINS(teacher_ids, ?)");
    $classes->execute([json_encode($user_id)]);

    $subjects = $pdo->prepare("SELECT * FROM subjects WHERE teacher_id = ?");
    $subjects->execute([$user_id]);
} else {
    $classes = $pdo->query("SELECT * FROM classes");
    $subjects = $pdo->query("SELECT * FROM subjects");
}

$students = $pdo->query("SELECT * FROM students");
$exams = $pdo->query("SELECT * FROM exams");
$results = $pdo->query("SELECT * FROM results");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Exam Results</title>
    <script src="../../tailwindcss.js"></script>
    <style type="text/tailwindcss">
        @theme {
        --color-clifford: #da373d;
        --color-primary: rgb(168,81,138);
        --color-secondary: rgb(205,122,203)
      }
      .fc-button {
       padding: 0rem !important;
      }
      #fc-dom-1{
        font-size: 1.1rem !important;
      }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const studentSelect = document.getElementById('student_id');

            classSelect.addEventListener('change', function() {
                const classId = this.value;

                fetch(`fetchStudents.php?class_id=${classId}`)
                    .then(response => response.json())
                    .then(data => {
                        studentSelect.innerHTML = '<option value="">Select Student</option>';
                        data.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.textContent = student.name;
                            studentSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching students:', error));
            });
        });
    </script>
</head>

<body>
    <?php include './NavBar.php'; ?>
    <div class="flex">
        <?php include '../../components/Sidebar.php'; ?>
        <div class="h-screen flex flex-col p-4 w-full">
            <h1 class="text-2xl font-bold mb-4">Add Exam Results</h1>
            <?php if (isset($add_message)): ?>
                <div class="bg-green-500 text-white p-2 mb-4"><?php echo htmlspecialchars($add_message); ?></div>
            <?php endif; ?>
            <form method="POST" action="" class="flex flex-col space-y-4">
                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="class_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Select Class</option>
                        <?php while ($class = $classes->fetch()): ?>
                            <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                    <select name="student_id" id="student_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Select Student</option>
                        <?php while ($student = $students->fetch()): ?>
                            <option value="<?php echo $student['id']; ?>"><?php echo $student['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="exam_id" class="block text-sm font-medium text-gray-700">Exam</label>
                    <select name="exam_id" id="exam_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Select Exam</option>
                        <?php while ($exam = $exams->fetch()): ?>
                            <option value="<?php echo $exam['id']; ?>"><?php echo $exam['exam_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div>
                    <label for="subjects" class="block text-sm font-medium text-gray-700">Subjects</label>
                    <select name="subjects" id="subjects" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                        <option value="">Select Subject</option>
                        <?php while ($subject = $subjects->fetch()): ?>

                            <option value="<?php echo $subject['name']; ?>"><?php echo $subject['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    <div>
                        <label for="ca_score" class="block text-sm font-medium text-gray-700">CA Score</label>
                        <input type="number" step="0.01" name="ca_score" id="ca_score" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="exam_score" class="block text-sm font-medium text-gray-700">Exam Score</label>
                        <input type="number" step="0.01" name="exam_score" id="exam_score" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                            <option value="taken">Taken</option>
                            <option value="absent">Absent</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="self-start px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Result</button>
            </form>
        </div>
    </div>
</body>

</html>