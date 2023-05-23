<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rock Paper Scissors Tournament Static</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid black;
        }

        th, td {
            text-align: left;
            padding: 8px;
            border: 1px solid black;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        td:first-child {
            font-weight: bold;
        }

        td:nth-child(3), td:nth-child(5) {
            text-transform: uppercase;
        }
    </style>
</head>

<body>
<h1>Rock Paper Scissors Tournament</h1>
<p>Date: 19th April 2023</p>

<table>
    <tr>
        <th>Round NR.</th>

        <th>Player 1</th>
        <th>Symbol Player 1</th>

        <th>Player 2</th>
        <th>Symbol Player 2</th>

        <th>Winner</th>

        <th>Date and Time</th>
    </tr>
    <tr>
        <td>1</td>

        <td>Nico Zach</td>
        <td>Schere</td>

        <td>Eyüp Özbege</td>
        <td>Schere</td>

        <td>Nico Zach</td>

        <td><?php echo date('j-M-Y h:i A', strtotime('2023-04-19 10:00:00')); ?></td>
    </tr>

</table>
</body>
</html>
