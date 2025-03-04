<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="doughnutChart" width="50" height="50"></canvas>
<script>
        // Random figures for boys and girls
        const boys = Math.floor(Math.random() * 100);
        const girls = Math.floor(Math.random() * 100);
    
        // Doughnut Chart
        const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
        const doughnutChart = new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: ['Boys', 'Girls'],
                datasets: [{
                    data: [boys, girls],
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    hoverBackgroundColor: ['#36A2EB', '#FF6384']
                }]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Distribution of Boys and Girls (Doughnut Chart)'
                }
            }
        });
    
       
    </script>