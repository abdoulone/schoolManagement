<!-- filepath: /c:/xampp/htdocs/schoolManagement/components/examManagement/viewResult.php -->
<?php
session_start();
require '../../config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit();
}

$student_id = $_GET['student_id'] ?? null;
if (!$student_id) {
    echo "No student selected.";
    exit();
}

// Fetch student information
$stmt = $pdo->prepare("SELECT name, class FROM students WHERE id = ?");
$stmt->execute([$student_id]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);

$student_class = $student['class'];

// Fetch exam results for the selected student
$stmt = $pdo->prepare("SELECT e.exam_name, r.ca_score, r.exam_score, r.status, r.subject, r.session
                       FROM results r
                       JOIN exams e ON r.exam_id = e.id
                       WHERE r.student_id = ?");
$stmt->execute([$student_id]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch exam details
$stmt1 = $pdo->prepare("SELECT exam_name, class, term, session, date, opening_date, closing_date, class_average, id FROM exams WHERE class = ?");
$stmt1->execute([$student_class]);
$exam = $stmt1->fetch(PDO::FETCH_ASSOC);
$exam_id = $exam['id'];

// Fetch attendance from behavioral assessments
$stmt2 = $pdo->prepare("SELECT * FROM behavioral_assessments WHERE student_id = ? AND exam_id = ?");
$stmt2->execute([$student_id, $exam_id]);
$behavioral_assessment = $stmt2->fetch(PDO::FETCH_ASSOC);

function getGrade($total)
{
    if ($total >= 70) return 'A';
    if ($total >= 60) return 'B';
    if ($total >= 50) return 'C';
    if ($total >= 45) return 'D';
    if ($total >= 40) return 'E';
    return 'F';
}

function getRemark($grade)
{
    switch ($grade) {
        case 'A':
            return 'Excellent';
        case 'B':
            return 'Very Good';
        case 'C':
            return 'Good';
        case 'D':
            return 'Average';
        case 'E':
            return 'Pass';
        default:
            return 'Work in Progress';
    }
}

function getRemarkColor($grade)
{
    switch ($grade) {
        case 'A':
            return 'bg-green-500';
        case 'B':
            return 'bg-blue-500';
        case 'C':
            return 'bg-yellow-500';
        case 'D':
            return 'bg-orange-500';
        case 'E':
            return 'bg-gray-500';
        default:
            return 'bg-red-500';
    }
}

// Calculate total score and average
$total_score = array_sum(array_map(function ($result) {
    return $result['ca_score'] + $result['exam_score'];
}, $results));
$average_score = count($results) ? $total_score / count($results) : 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Exam Results</title>
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <style>
        @media print {
            body {
                width: 210mm;
                height: 297mm;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            .page {
                width: 100%;
                height: 100%;
                padding: 20mm;
                box-sizing: border-box;
                page-break-after: always;
            }
        }

        .page {
            width: 100%;
            max-width: 210mm;
            height: auto;
            padding: 20mm;
            box-sizing: border-box;
            background: white;
            margin: 0 auto;
        }

        .header,
        .info-column,
        .table-container {
            margin-bottom: 20px;
        }

        .header img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
        }

        .header h1 {
            font-family: 'Arial Black';
        }

        .header p {
            font-size: 12px;
        }

        .header h3 {
            font-family: 'Arial Black';
            color: green;
            margin: 0;
        }

        .info-column p {
            margin: 0;
        }

        .table-container {
            overflow-x: auto;
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .table-container th {
            background-color: #f2f2f2;
        }

        .chart-container {
            width: 100%;
            height: 300px;
        }
    </style>
</head>

<body>
    <div class="page">
        <div class="container sm:text-xs">

            <h5 class="text-xl sm:text-lg font-bold mb-4 font-purple">Exam Results</h5>
            <center>
                <header class="header align-center items-center justify-between justify-center">
                    <!-- School Name -->
                    <div><img src="../../images/logoo.png" class="w-50 h-50" alt="School Logo"></div>
                    <h5 class="text-xl sm:text-lg font-bold text-primary">OGIBA <span>COMPREHENSIVE SCHOOL</span></h5>
                    <!-- School Contact Information -->
                    <p class="sm:text-xs">3330 Danyaro Street, Link 19 Off Forestry Road, Dorayi Babba Gwale Kano</p>
                    <p class="sm:text-xs">Phone: 08069475230 | Email: ogiba629@gmail.com</p>
                    <h3 class="sm:text-sm">REPORT SHEET</h3>
                </header>
            </center>

            <div class="student-info grid grid-cols-2 text-red border border-primary gap-0">
                <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2">
                    <p class="sm:text-xs" id="stud-name"><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                    <p class="sm:text-xs"><strong>Class:</strong> <?php echo htmlspecialchars($exam['class']); ?></p>
                    <p class="sm:text-xs"><strong>Closing Date:</strong> <?php echo htmlspecialchars($exam['closing_date']); ?></p>
                </div>
                <div class="info-column border border-primary w-full h-full p-2 ">
                    <p class="sm:text-xs"><strong>Term/Session:</strong> <?php echo htmlspecialchars($exam['term'] . '/' . $exam['session']); ?></p>
                    <p class="sm:text-xs"><strong>Attendance:</strong> <?php echo htmlspecialchars($behavioral_assessment['attendance']) . '%'; ?></p>
                    <p class="sm:text-xs"><strong>Opening Date:</strong> <?php echo htmlspecialchars($exam['opening_date']); ?></p>
                </div>
            </div>
            <div class="table-container">
                <table class="sm:text-xs">
                    <thead>
                        <tr class="bg-primary">
                            <th>Subject</th>
                            <th>CA Score</th>
                            <th>Exam Score</th>
                            <th>Total</th>
                            <th>Grade</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($results)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No results found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($results as $result): ?>
                                <?php
                                $total = $result['ca_score'] + $result['exam_score'];
                                $grade = getGrade($total);
                                $remark = getRemark($grade);
                                $remarkColor = getRemarkColor($grade);
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($result['subject']); ?></td>
                                    <td><?php echo htmlspecialchars($result['ca_score']); ?></td>
                                    <td><?php echo htmlspecialchars($result['exam_score']); ?></td>
                                    <td><?php echo htmlspecialchars($total); ?></td>
                                    <td><?php echo htmlspecialchars($grade); ?></td>
                                    <td class="<?php echo $remarkColor; ?>"><?php echo htmlspecialchars($remark); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-center align-center items-center mt-4">

                <p class="sm:text-xs"><strong>Child's Average:</strong> <?php echo number_format($average_score, 2) . "%" . "  "; ?> ||
                    <strong>Total Score:</strong> <?php echo $total_score . "  "; ?> ||
                    <strong>Class Average:</strong> <?php echo htmlspecialchars($exam['class_average']) . "%"; ?>
                </p>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="resultChart"></canvas>
        </div>
        <div class="student-info grid grid-cols-2 text-red border border-primary gap-0">
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Punctuality</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['punctuality']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Social interaction</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['social_interaction']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Communcation SKills</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['communication_skills']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Physical co-ordination and Motor Skills</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['physical_coordination']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Compliance with rules and regulations</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['compliance_rules']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Attention and Focus</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['attention_focus']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Problem solving and Critical thinking</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['problem_solving']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Academic challenges</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['academic_challenges']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Behavioural challenges</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['behavioral_challenges']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Action to deploy upon challenges</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['action_deploy']) ?></p>
            </div>
            <div class="info-column border border-primary w-full items-left justify-left content-left h-full p-2 ">
                <p class="sm:text-xs"><strong>Teacher's comments</strong></p>
            </div>
            <div class="info-column border border-primary w-full h-full p-2 ">
                <p class="sm:text-xs"><?php echo htmlspecialchars($behavioral_assessment['teachers_comment']) ?></p>
            </div>
        </div>
        <div class="flex justify-center mt-4">
            <button onclick="window.print()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 sm:text-xs">Print Result</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('resultChart').getContext('2d');
            const resultChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: <?php echo json_encode(array_column($results, 'subject')); ?>,
                    datasets: [{
                        label: 'Scores',
                        data: <?php echo json_encode(array_map(function ($result) {
                                    return $result['ca_score'] + $result['exam_score'];
                                }, $results)); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(199, 199, 199, 0.2)',
                            'rgba(83, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(199, 199, 199, 1)',
                            'rgba(83, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
</body>

</html>