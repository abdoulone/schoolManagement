<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="incomeExpenseChart" width="450" height="450"></canvas>
<script>
        // Generate random figures for income and expenses
        const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const income = months.map(() => Math.floor(Math.random() * 10000) + 1000);
        const expenses = months.map(() => Math.floor(Math.random() * 8000) + 500);
    
        // Line Chart
        const cty = document.getElementById('incomeExpenseChart').getContext('2d');
        const incomeExpenseChart = new Chart(cty, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Income',
                        data: income,
                        borderColor: '#36A2EB',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderWidth: 2,
                        fill: true
                    },
                    {
                        label: 'Expenses',
                        data: expenses,
                        borderColor: '#FF6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 2,
                        fill: true
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
                            text: 'Amount ($)'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                },
                plugins: {
                    title: {
                        display: true,
                        text: 'Income / Expense Chart'
                    }
                }
            }
        });
    </script>