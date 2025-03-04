<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script> google.charts.load('current', {'packages':['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Day', 'Present', 'Absent', ],
          ['Year 1 Gold', 20, 7 ],
          ['Year 2 Silver', 31, 2],
          ['Year 3 Diamond', 29, 1],
          ['Year 4 Platinum', 22, 10]
        ]);

        var options = {
          chart: {
            title: 'Attendance',
            subtitle: 'Present and Absent: Year 1 Gold-Year 4 Platinum',
          },
          bars: 'vertical', // Required for Material Bar Charts.
          hAxis: {format: 'decimal'},
          height: 400,
          colors: ['#1b9e77', '#d95f02']
        };

        var chart = new google.charts.Bar(document.getElementById('chart_divv'));

        chart.draw(data, google.charts.Bar.convertOptions(options));

        var btns = document.getElementById('btn-group');

        btns.onclick = function (e) {

          if (e.target.tagName === 'BUTTON') {
            options.hAxis.format = e.target.id === 'none' ? '' : e.target.id;
            chart.draw(data, google.charts.Bar.convertOptions(options));
          }
        }
      }</script>
    <br/>
    <div id="btn-group">
      <button class="button button-blue" id="none">No Format</button>
      <button class="button button-blue" id="scientific">Scientific Notation</button>
      <button class="button button-blue" id="decimal">Decimal</button>
      <button class="button button-blue" id="short">Short</button>
    </div>
   