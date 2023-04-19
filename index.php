<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rock Paper Scissors Tournament</title>
    <style>

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

        <th>Date and Time</th>
    </tr>
    <tr>
        <td>1</td>

        <td>Nico Zach</td>
        <td>Schere</td>

        <td>Eyüp Özbege</td>
        <td>Schere</td>

        <td><?php echo date('j-M-Y h:i A', strtotime('2023-04-19 10:00:00')); ?></td>
    </tr>

</table>
</body>
</html>
