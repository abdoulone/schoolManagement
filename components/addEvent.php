<!-- filepath: /c:/xampp/htdocs/schoolManagement/addEvent.php -->
<?php
require 'config.php';

session_start();

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $venue = $_POST['venue'];

    $stmt = $pdo->prepare("INSERT INTO events (title, content, date, time, venue, created_at, is_active) VALUES (?, ?, ?, ?, ?, NOW(), 1)");
    $stmt->execute([$title, $content, $date, $time, $venue]);

    header("Location: dashboard.php");
    exit();
}
?>

<!<!-- filepath: /c:/xampp/htdocs/schoolManagement/addEvent.php -->
    <?php
    require 'config.php';

    session_start();

    if ($_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $venue = $_POST['venue'];

        $stmt = $pdo->prepare("INSERT INTO events (title, content, date, time, venue, created_at, is_active) VALUES (?, ?, ?, ?, ?, NOW(), 1)");
        $stmt->execute([$title, $content, $date, $time, $venue]);

        header("Location: dashboard.php");
        exit();
    }
    ?>