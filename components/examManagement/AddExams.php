<!-- filepath: /C:/xampp/htdocs/schoolManagement/components/examManagement/addExams.php -->
<?php


require '../../config.php';

// Fetch classes
$stmt = $pdo->query("SELECT id, name FROM classes");
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Exam</title>
    <script src="../../tailwindcss.js"></script>
    <script src="./components/ChartJs.js"></script>
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
</head>

<body>

    <div class="flex">
        <?php include '../Sidebar.php'; ?>
        <div class="h-screen flex flex-col p-4 w-full">
            <h1 class="text-2xl font-bold mb-4">Add Exam</h1>
            <form class="flex flex-col space-y-4" action="processExamUpload.php" method="POST" enctype="multipart/form-data">
                <div>
                    <label for="examName" class="block text-sm font-medium text-gray-700">Exam Name</label>
                    <input type="text" id="examName" name="examName" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="class" class="block text-sm font-medium text-gray-700">Class</label>
                    <select id="class" name="class" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                        <option value="">Select Class</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?php echo $class['name']; ?>"><?php echo $class['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                    <input type="date" id="date" name="date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                    <input type="time" id="time" name="time" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                    <input type="text" id="term" name="term" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="section" class="block text-sm font-medium text-gray-700">Section</label>
                    <input type="text" id="section" name="section" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="session" class="block text-sm font-medium text-gray-700">Session</label>
                    <input type="text" id="session" name="session" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="opening_date" class="block text-sm font-medium text-gray-700">Opening Date</label>
                    <input type="date" id="opening_date" name="opening_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="closing_date" class="block text-sm font-medium text-gray-700">Closing Date</label>
                    <input type="date" id="closing_date" name="closing_date" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <div>
                    <label for="class_average" class="block text-sm font-medium text-gray-700">Class Average</label>
                    <input type="number" max="100" id="class_average" name="class_average" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
                </div>
                <button type="submit" class="self-start px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Exam</button>
            </form>
        </div>
    </div>
</body>

</html>