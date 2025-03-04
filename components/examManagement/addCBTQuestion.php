<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/examManagement/addCBTQuestion.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add CBT Question</title>
    <script src="../../tailwindcss.js"></script>
    <style type="text/tailwindcss"></style>
</head>

<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Add CBT Question</h1>
        <form action="processAddCBTQuestion.php" method="POST">
            <div class="mb-4">
                <label for="exam_id" class="block text-sm font-medium text-gray-700">Exam ID</label>
                <input type="number" id="exam_id" name="exam_id" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <div class="mb-4">
                <label for="question" class="block text-sm font-medium text-gray-700">Question</label>
                <textarea id="question" name="question" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
            </div>
            <div class="mb-4">
                <label for="options" class="block text-sm font-medium text-gray-700">Options (JSON format)</label>
                <textarea id="options" name="options" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required></textarea>
            </div>
            <div class="mb-4">
                <label for="correct_answer" class="block text-sm font-medium text-gray-700">Correct Answer</label>
                <input type="text" id="correct_answer" name="correct_answer" class="mt-1 block w-full p-2 border border-gray-300 rounded-md" required>
            </div>
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Add Question</button>
        </form>
    </div>
</body>

</html>