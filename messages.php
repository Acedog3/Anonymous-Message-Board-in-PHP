<?php
    function h($str) {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
    
    session_start();
    
    date_default_timezone_set('UTC');
 
    $date = date('H:i Y/m/d').' (UTC)';
    $name = (string)filter_input(INPUT_POST, 'name');
    $message = (string)filter_input(INPUT_POST, 'message');
    $token = (string)filter_input(INPUT_POST, 'token');

    $fp = fopen('commentdata.csv', 'a+b');
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && sha1(session_id()) === $token) {
        flock($fp, LOCK_EX);
        fputcsv($fp, [$date, $name, $message]);
        rewind($fp);
    }
    
    flock($fp, LOCK_SH);
    
    while ($row = fgetcsv($fp)) {
        $rows[] = $row;
    }
    
    flock($fp, LOCK_UN);
    fclose($fp);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
a{
  color: black;
  text-decoration: none;
}

a:hover{
  color: black;
  text-decoration: underline;
}

body {
  font-family: Arial;
}
        table.comments {
    width: 70%;
    max-width: 1000px;
    border: 1px solid #000;
    border-collapse: collapse;
    table-layout: fixed;
}

th, td {
    margin: 0px;
    border: 1px solid #000;
    border-collapse: collapse;
    word-wrap: break-word;
    vertical-align: top;
    padding: 15px;
}

th.name, td.name {
    width: 150px;
}

th.date, td.date {
    width: 120px;
}

th.message, id.message {

    
}
    </style>
</head>
<body>
    
    <?php if (!empty($rows)): ?>
        <table class="comments">
            <tr>
                <th class="date">DATE</th>
                <th class="name">NAME</th>
                <th class="message">MESSAGE</th>
            </tr>
    <?php $index = count($rows);
        while ($index): ?>
            <?php $index = $index - 1;?>
            <tr>
                <td class="date"><?=h($rows[$index][0])?></td>
                <td class="name"><?=h($rows[$index][1])?></td>
                <td class="message"><?=h($rows[$index][2])?></td>
            </tr>
    <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>no one has commented yet, be the first!</p>
    <?php endif; ?>
    
</body>
</html>