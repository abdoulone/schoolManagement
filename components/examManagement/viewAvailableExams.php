<?php
// filepath: /schoolManagement/schoolManagement/components/examManagement/viewAvailableExams.php

// Assuming a database connection is already established
// include_once '../../db_connection.php'; // Adjust the path as necessary

// Fetch available exams from the database
// $query = "SELECT * FROM exams WHERE status = 'available'";
// $result = mysqli_query($conn, $query);
$result = [
    [
        "name" => "First Term",
        "description" => "First Term Examamination 2024/2025 ",
        "id" => "1",
    ],
    [
        "name" => "Second Term",
        "description" => "Second Term Examamination 2024/2025 ",
        "id" => "2",
    ],
];
?>

<div class="h-screen flex flex-col p-4">
    <h1 class="text-2xl font-bold mb-4">Available Exams</h1>
    <?php if ($result > 0): ?>
        <ul class="space-y-4">
            <?php foreach ($result as $exam): ?>
                <li class="p-4 bg-white shadow rounded-md">
                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($exam['name']); ?></h2>
                    <p class="text-gray-700"><?php echo htmlspecialchars($exam['description']); ?></p>
                    <a href="checkExamResults.php?exam_id=<?php echo $exam['id']; ?>" class="text-blue-500 hover:underline">View Results</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-gray-500">No available exams at the moment.</p>
    <?php endif; ?>
</div>

<?php
// mysqli_close($conn); // Close the database connection
?>