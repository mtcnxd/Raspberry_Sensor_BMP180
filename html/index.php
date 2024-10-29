<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Raspberry temperature sensor</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            .rounded-pill {
                padding: 2px 7px;
            }
            .chart {
                max-height:200px;
                margin-bottom: 30px;
            }
            .overlay {
                overflow-y:scroll;
                max-height:600px;
            }
        </style>
    </head>
    <body>
        <div class="container" style="margin-bottom: 40px;">
            <canvas class="chart" id="myChart"></canvas>
        </div>
        <div class="container overlay">
            <?php
            include 'classes/mySQL.php';

            $dataBase = new MySQL();
            $results  = $dataBase->mySQLquery("SELECT * FROM temperature ORDER BY created_at DESC LIMIT 50");

            $counter = 1;
            echo "<table class='table table-hover'>";
            foreach ($results as $sensor) {
                echo "<tr>";
                echo "  <td> #". $counter ."</td>";
                echo "  <td><span class='bagde rounded-pill text-bg-secondary'>". $sensor->id ."</span></td>";
                echo "  <td>Temperature: <b>". number_format($sensor->value1, 1) ."</b></td>";
                echo "  <td>". $sensor->created_at ."</td>";
                echo "</tr>";
                $counter ++;
                $labels[] = substr($sensor->created_at, 10);
                $values[] = $sensor->value1;
            }
            echo "</table>";
            ?>    
        </div>
    </body>

    <script>
        const ctx = document.getElementById('myChart');
        new Chart(ctx, {
            type: 'line',
            data: {
            labels: <?=json_encode( $labels );?>,
            datasets: [{
                label: 'Temperature',
                data: <?=json_encode( $values );?>,
                borderWidth: 1
            }]
            },
        });
    </script>
</html>