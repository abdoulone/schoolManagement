<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/examManagement/addResults.php -->
<?php
session_start();
require '../../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

$classes = [];
$exams = [];
$students = [];

if ($user_role === 'admin' || $user_role === 'teacher') {
    // Fetch classes
    $stmt = $pdo->query("SELECT id, name FROM classes");
    $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch exams
    $stmt = $pdo->query("SELECT id, exam_name, class FROM exams");
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_id = $_POST['class_id'];
    $exam_id = $_POST['exam_id'];
    $student_id = $_POST['student_id'];
    $results = $_POST['results'];

    foreach ($results as $subject_name => $scores) {
        $ca_score = $scores['ca_score'];
        $exam_score = $scores['exam_score'];

        $stmt = $pdo->prepare("INSERT INTO results (student_id, exam_id, subject, ca_score, exam_score, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$student_id, $exam_id, $subject_name, $ca_score, $exam_score]);
    }

    // Insert behavioral assessments
    $stmt = $pdo->prepare("INSERT INTO behavioral_assessments (student_id, exam_id, punctuality, social_interaction, communication_skills, physical_coordination, compliance_rules, attention_focus, problem_solving, academic_challenges, behavioral_challenges, action_deploy, teachers_comment, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
    $stmt->execute([
        $student_id,
        $exam_id,
        $_POST['punctuality'],
        $_POST['social_interaction'],
        $_POST['communication_skills'],
        $_POST['physical_coordination'],
        $_POST['compliance_rules'],
        $_POST['attention_focus'],
        $_POST['problem_solving'],
        $_POST['academic_challenges'],
        $_POST['behavioral_challenges'],
        $_POST['action_deploy'],
        $_POST['teachers_comment']
    ]);

    header("Location: viewResult.php?student_id=$student_id&class_id=$class_id");
    exit();
}
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
            --color-secondary: rgb(205,122,203);
        }
        .fc-button {
            padding: 0rem !important;
        }
        #fc-dom-1 {
            font-size: 1.1rem !important;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const examSelect = document.getElementById('exam_id');
            const studentSelect = document.getElementById('student_id');
            const subjectsContainer = document.getElementById('subjects-container');

            classSelect.addEventListener('change', function() {
                const classId = this.value;
                const examId = examSelect.value;

                fetch(`fetchStudents.php?class_id=${classId}&exam_id=${examId}`)
                    .then(response => response.text())
                    .then(data => {
                        studentSelect.innerHTML = '<option value="">Select Student</option>' + data;
                    })
                    .catch(error => console.error('Error fetching students:', error));
            });

            examSelect.addEventListener('change', function() {
                const examId = this.value;
                const classId = classSelect.value;

                fetch(`fetchStudents.php?class_id=${classId}&exam_id=${examId}`)
                    .then(response => response.text())
                    .then(data => {
                        studentSelect.innerHTML = '<option value="">Select Student</option>' + data;
                    })
                    .catch(error => console.error('Error fetching students:', error));

                fetch(`fetchSubjects.php?exam_id=${examId}`)
                    .then(response => response.text())
                    .then(data => {
                        subjectsContainer.innerHTML = data;
                    })
                    .catch(error => console.error('Error fetching subjects:', error));
            });
        });
    </script>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Add Exam Results</h1>
        <form action="addResults.php" method="POST" class="flex flex-col space-y-4">
            <div>
                <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                <select name="class_id" id="class_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="exam_id" class="block text-sm font-medium text-gray-700">Examination</label>
                <select name="exam_id" id="exam_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    <option value="">Select Examination</option>
                    <?php foreach ($exams as $exam): ?>
                        <option value="<?php echo $exam['id']; ?>" data-class="<?php echo $exam['class']; ?>"><?php echo $exam['exam_name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                <select name="student_id" id="student_id" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    <option value="">Select Student</option>
                </select>
            </div>
            <div id="subjects-container">
                <!-- Subjects and Scores will be loaded here based on the selected examination -->
            </div>
            <div>
                <h2 class="text-xl font-bold mb-2">Behavioral Assessment</h2>
                <div class="mb-4">
                    <label for="punctuality" class="block text-sm font-medium text-gray-700">Punctuality</label>
                    <input type="text" name="punctuality" id="punctuality" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="social_interaction" class="block text-sm font-medium text-gray-700">Social Interaction</label>
                    <input type="text" name="social_interaction" id="social_interaction" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="communication_skills" class="block text-sm font-medium text-gray-700">Communication Skills</label>
                    <input type="text" name="communication_skills" id="communication_skills" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="physical_coordination" class="block text-sm font-medium text-gray-700">Physical Coordination and Motor Skills</label>
                    <input type="text" name="physical_coordination" id="physical_coordination" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="compliance_rules" class="block text-sm font-medium text-gray-700">Compliance with Rules and Regulations</label>
                    <input type="text" name="compliance_rules" id="compliance_rules" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="attention_focus" class="block text-sm font-medium text-gray-700">Attention and Focus</label>
                    <input type="text" name="attention_focus" id="attention_focus" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="problem_solving" class="block text-sm font-medium text-gray-700">Problem Solving and Critical Thinking</label>
                    <input type="text" name="problem_solving" id="problem_solving" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="academic_challenges" class="block text-sm font-medium text-gray-700">Academic Challenges</label>
                    <input type="text" name="academic_challenges" id="academic_challenges" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="behavioral_challenges" class="block text-sm font-medium text-gray-700">Behavioral Challenges</label>
                    <input type="text" name="behavioral_challenges" id="behavioral_challenges" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="action_deploy" class="block text-sm font-medium text-gray-700">Action to Deploy upon Challenges</label>
                    <input type="text" name="action_deploy" id="action_deploy" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div class="mb-4">
                    <label for="teachers_comment" class="block text-sm font-medium text-gray-700">Teacher's Comment</label>
                    <textarea name="teachers_comment" id="teachers_comment" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Submit Results</button>
        </form>
    </div>
</body>

</html>