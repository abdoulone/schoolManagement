<!-- filepath: /c:/xampp/htdocs/schoolManagement/addClass.php -->
<?php
require '../config.php';

session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $section = $_POST['section'];
    $subjects = json_encode($_POST['subjects']);
    $teachers = $_POST['teachers'];

    // Insert the new class into the classes table
    $stmt = $pdo->prepare("INSERT INTO classes (name, section, subjects, teacher_ids) VALUES (?, ?, ?, ?)");
    $stmt->execute([$name, $section, $subjects, json_encode($teachers)]);
    $classId = $pdo->lastInsertId();

    // Update the selected teachers' class_id and classes columns
    foreach ($teachers as $teacherId) {
        $stmt = $pdo->prepare("SELECT class_id, classes FROM teachers WHERE id = ?");
        $stmt->execute([$teacherId]);
        $teacher = $stmt->fetch(PDO::FETCH_ASSOC);

        $classIds = json_decode($teacher['class_id'], true) ?: [];
        $classNames = json_decode($teacher['classes'], true) ?: [];

        $classIds[] = $classId;
        $classNames[] = $name;

        $stmt = $pdo->prepare("UPDATE teachers SET class_id = ?, classes = ? WHERE id = ?");
        $stmt->execute([json_encode($classIds), json_encode($classNames), $teacherId]);
    }

    header("Location: dashboard.php");
    exit();
}

// Fetch all subjects
$stmt = $pdo->query("SELECT id, name, section FROM subjects");
$allSubjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all teachers
$stmt = $pdo->query("SELECT id, name, section FROM teachers");
$allTeachers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all existing classes
$stmt = $pdo->query("SELECT name, section FROM classes");
$existingClasses = $stmt->fetchAll(PDO::FETCH_ASSOC);

include './classList.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Class</title>
    <script src="../tailwindcss.js"></script>
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
        const allSubjects = <?php echo json_encode($allSubjects); ?>;
        const allTeachers = <?php echo json_encode($allTeachers); ?>;
        const classList = <?php echo json_encode($classList); ?>;
        const existingClasses = <?php echo json_encode($existingClasses); ?>;

        function fetchSubjectsAndTeachers(section) {
            const subjectsContainer = document.getElementById('subjects-container');
            subjectsContainer.innerHTML = '';
            const filteredSubjects = allSubjects.filter(subject => subject.section === section);
            filteredSubjects.forEach(subject => {
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'subjects[]';
                checkbox.value = subject.name;
                checkbox.id = `subject-${subject.id}`;

                const label = document.createElement('label');
                label.htmlFor = `subject-${subject.id}`;
                label.textContent = subject.name;

                const div = document.createElement('div');
                div.className = 'flex items-center gap-2';
                div.appendChild(checkbox);
                div.appendChild(label);

                subjectsContainer.appendChild(div);
            });

            const teachersContainer = document.getElementById('teachers-container');
            teachersContainer.innerHTML = '';
            const filteredTeachers = allTeachers.filter(teacher => teacher.section === section);
            filteredTeachers.forEach(teacher => {
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'teachers[]';
                checkbox.value = teacher.id;
                checkbox.id = `teacher-${teacher.id}`;

                const label = document.createElement('label');
                label.htmlFor = `teacher-${teacher.id}`;
                label.textContent = teacher.name;

                const div = document.createElement('div');
                div.className = 'flex items-center gap-2';
                div.appendChild(checkbox);
                div.appendChild(label);

                teachersContainer.appendChild(div);
            });

            const classNameSelect = document.getElementById('class-name');
            classNameSelect.innerHTML = '';
            const filteredClasses = classList.filter(cls => cls.section === section);
            const existingClassNames = existingClasses.map(cls => cls.name);
            filteredClasses.forEach(cls => {
                if (!existingClassNames.includes(cls.name)) {
                    const option = document.createElement('option');
                    option.value = cls.name;
                    option.textContent = cls.name;
                    classNameSelect.appendChild(option);
                }
            });
        }
    </script>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Add Class</h1>
        <form action="addClass.php" method="POST">
            <div class="mb-4">
                <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                <select name="section" id="section" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required onchange="fetchSubjectsAndTeachers(this.value)">
                    <option value="">Select Section</option>
                    <option value="nursery">Nursery</option>
                    <option value="primary">Primary</option>
                    <option value="jss">JSS</option>
                    <option value="ss science">SS Science</option>
                    <option value="ss art">SS Art</option>
                    <option value="ss commerce">SS Commerce</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="class-name" class="block text-sm font-medium text-gray-700">Class Name</label>
                <select name="name" id="class-name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    <!-- Class names will be loaded here based on the selected section -->
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Subjects</label>
                <div id="subjects-container" class="mt-2 space-y-2">
                    <!-- Subjects checkboxes will be loaded here based on the selected section -->
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Teachers</label>
                <div id="teachers-container" class="mt-2 space-y-2">
                    <!-- Teachers checkboxes will be loaded here based on the selected section -->
                </div>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Class</button>
        </form>
    </div>
</body>

</html>