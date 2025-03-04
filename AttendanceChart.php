    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <canvas id="attendanceChart" width="50" height="50"></canvas>

    <script>
    // Random figures for male and female attendance in different classes
    const classes = ['Year 1', 'Year 2', 'Year 3', 'Year 4',];
    const maleAttendance = classes.map(() => Math.floor(Math.random() * 100));
    const femaleAttendance = classes.map(() => Math.floor(Math.random() * 100));

    // Bar Chart
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const attendanceChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: classes,
            datasets: [
                {
                    label: 'Males',
                    data: maleAttendance,
                    backgroundColor: '#36A2EB',
                    borderColor: '#36A2EB',
                    borderWidth: 1
                },
                {
                    label: 'Females',
                    data: femaleAttendance,
                    backgroundColor: '#FF6384',
                    borderColor: '#FF6384',
                    borderWidth: 1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Attendance'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Classes'
                    }
                }
            },
            plugins: {
                title: {
                    display: true,
                    text: 'Attendance'
                }
            }
        }
    });
</script>

