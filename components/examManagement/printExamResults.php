<?php
// session_start();
// include '../../dbConnection.php'; // Include your database connection file

// if (!isset($_SESSION['user_id'])) {
//     header("Location: ../../index.php");
//     exit();
// }

// $user_id = $_SESSION['user_id'];
// $role = $_SESSION['role'];

// if ($role !== 'student') {
//     echo "Access denied. This page is for students only.";
//     exit();
// }

// // Fetch exam results from the database
// $query = "SELECT * FROM exam_results WHERE student_id = ?";
// $stmt = $conn->prepare($query);
// $stmt->bind_param("i", $user_id);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result->num_rows > 0) {
//     $examResults = $result->fetch_all(MYSQLI_ASSOC);
// } else {
//     $examResults = [];
// }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Exam Results</title>
    <script src="./tailwindcss.js"></script>
    <style type="text/tailwindcss"></style>
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div class="h-screen flex flex-col p-4">
        <h1 class="text-2xl font-bold mb-4">Print Exam Results</h1>
        <form class="flex flex-col space-y-4">
            <div>
                <label for="student" class="block text-sm font-medium text-gray-700">Student</label>
                <input type="text" id="student" name="student" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="self-start px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Print Results</button>
        </form>
    </div>
</body>

</html>