<script type="text/javascript">
    google.charts.load("current", {
        packages: ["corechart"]
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Position', 'Bil'],
            <?php
            $i = 1;
            $query = $this->db->query("SELECT Position,COUNT(Position) as 'Bil' FROM users WHERE Position != 'Fullstack Developer' and role_id!=5 GROUP BY Position");

            foreach ($query->result() as $row) {

                if ($i = $query->num_rows()) {
                    echo "['$row->Position',$row->Bil],\n";
                } else {
                    echo "['$row->Position',$row->Bil]";
                }
                $i++;
            }
            ?>
        ]);

        var options = {
            title: 'Position Staf',
            is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }
</script>