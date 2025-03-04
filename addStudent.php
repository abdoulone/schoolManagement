<!-- filepath: /c:/xampp/htdocs/schoolManagement/addStudent.php -->
<?php
require '../config.php';

session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $class = $_POST['class'];
    $parent_id = $_POST['parent_id'];
    $section = $_POST['section'];
    $session = $_POST['session'];
    $username = $_POST['username'];

    // Check for duplicate email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $error = 'Email already exists.';
    }

    // Check for duplicate username
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM students WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $error = 'Username already exists.';
    }

    if (empty($error)) {
        // Insert student details into the users table
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, created_at, is_active) VALUES (?, ?, ?, 'student', NOW(), 1)");
        $stmt->execute([$name, $email, $password]);
        $user_id = $pdo->lastInsertId();

        // Insert student details into the students table
        $stmt = $pdo->prepare("INSERT INTO students (user_id, class, parent_id, section, session, username, created_at, is_active) VALUES (?, ?, ?, ?, ?, ?, NOW(), 1)");
        $stmt->execute([$user_id, $class, $parent_id, $section, $session, $username]);

        // Update the students column in the parents table
        $stmt = $pdo->prepare("SELECT students FROM parents WHERE user_id = ?");
        $stmt->execute([$parent_id]);
        $parent = $stmt->fetch(PDO::FETCH_ASSOC);
        $students = json_decode($parent['students'], true);
        if (!$students) {
            $students = [];
        }
        $students[] = $name;
        $students_json = json_encode($students);

        $stmt = $pdo->prepare("UPDATE parents SET students = ? WHERE user_id = ?");
        $stmt->execute([$students_json, $parent_id]);

        header("Location: dashboard.php");
        exit();
    }
}

// Fetch parents from the database
$stmt = $pdo->query("SELECT p.user_id, u.name 
                     FROM parents p 
                     JOIN users u ON p.user_id = u.id 
                     ORDER BY p.user_id DESC");
$parents = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch classes from the database
$stmt = $pdo->query("SELECT id, name FROM classes ORDER BY name ASC");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch users from the database
$stmt = $pdo->query("SELECT email FROM users");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
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
        const users = <?php echo json_encode($users); ?>;

        function checkDuplicate(field, value) {
            if (value.length === 0) {
                document.getElementById(field + '-error').innerText = '';
                return;
            }

            const exists = users.some(user => user[field] === value);
            if (exists) {
                document.getElementById(field + '-error').innerText = field.charAt(0).toUpperCase() + field.slice(1) + ' already exists.';
            } else {
                document.getElementById(field + '-error').innerText = '';
            }
        }
    </script>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Add Student</h1>
        <?php if (!empty($error)): ?>
            <div class="mb-4 text-red-500"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form action="addStudent.php" method="POST">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input type="text" id="name" name="name" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" id="email" name="email" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required oninput="checkDuplicate('email', this.value)">
                <div id="email-error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="class" class="block text-sm font-medium text-gray-700">Class</label>
                <select id="class" name="class" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                    <option value="">Select Class</option>
                    <?php foreach ($classes as $class): ?>
                        <option value="<?php echo $class['name']; ?>"><?php echo htmlspecialchars($class['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent</label>
                <select id="parent_id" name="parent_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    <option value="">Select Parent</option>
                    <?php foreach ($parents as $parent): ?>
                        <option value="<?php echo $parent['user_id']; ?>"><?php echo htmlspecialchars($parent['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-4">
                <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                <input type="text" id="section" name="section" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="session" class="block text-sm font-medium text-gray-700">Session</label>
                <input type="text" id="session" name="session" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required oninput="checkDuplicate('username', this.value)">
                <div id="username-error" class="text-red-500 text-sm mt-1"></div>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Student</button>
        </form>
    </div>
</body>

</html>