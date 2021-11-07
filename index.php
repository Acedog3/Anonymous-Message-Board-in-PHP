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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
textarea, input {
  background-color: #2F3136;
  border: 1px solid #111213;
  color: white;
  outline:none;
}

button {
   background-color: #2F3136;
  border: 1px solid #111213;
  color: white;
  outline:none;
  border-radius: 4px;
  padding: 5px;
}

button:hover {
   background-color: #2F3136;
  border: 1px solid black;
  color: white;
  outline:none;
}

a{
  color: #DCDDDE;
  text-decoration: none;
}

a:hover{
  color: DCDDDE;
  text-decoration: underline;
}

body {
  font-family: Arial;
  background-color: #36393F;
  color: #DCDDDE;
}

        table.comments {
    width: 70%;
    max-width: 1000px;
    min-width: 500px;
000000    border-collapse: collapse;
    table-layout: fixed;
}

th, td {
    margin: 0px;
    border: 1px solid black;
    border-collapse: collapse;
    word-wrap: break-word;
    vertical-align: top;
    padding: 15px;
    background-color: #2F3136;
}

th.name, td.name {
    width: 150px;
}

th.date, td.date {
  width: 120px;
}

a.blue:visited,a.blue {color: #00B0E8; text-decoration: none;}
a.blue:hover {text-decoration: underline;}
    </style>
<script>if ( window.history.replaceState ) {window.history.replaceState( null, null, window.location.href );} //stops weird thing where it sends your message every time you refresh</script> 
</head>
<body>
    
    
    
<div style="top: 75px; border: 1px solid black; text-overflow: string; height: auto; overflow-y: scroll; width: 285px; padding: 10px; background-color: #2F3136; position: absolute; left: 57%;">rules:
<ol>
<li>dont be dumb</li>
<li>nothing illegal</li>
</ol>
contact:
<ul>
**your email or whatever here**
</ul>
<?php
function isa_convert_bytes_to_specified($bytes, $to, $decimal_places = 1) {
    $formulas = array(
        'K' => number_format($bytes / 1024, $decimal_places),
        'M' => number_format($bytes / 1048576, $decimal_places),
        'G' => number_format($bytes / 1073741824, $decimal_places)
    );
    return isset($formulas[$to]) ? $formulas[$to] : 0;
}

// get file size as bytes
$size = filesize('commentdata.csv');

// convert and display file size
echo "File size of all messages:<ul>" . isa_convert_bytes_to_specified($size, "K"). " kB</ul>";
                            //if it gets too big you can change kilobytes here ^ into a different format as defined above


?>
<a href="#bottom" class="blue">Jump to bottom</a>
</div>

    <h1><a id="path" href="">Anonymous Message Board</a></h1>
    <form id="mainForm" action="" method="post">
        Name:<br />
        <input maxlength="50" type="text" name="name" style="width: 250px;"><br />
        
        Message: (up to 500 characters)<br />
        <textarea placeholder="" maxlength="500" name="message" style="width: 250px; height: 150px; resize: none;" required="" oninvalid="this.setCustomValidity('enter a message bruh')" oninput="setCustomValidity('')"></textarea><br />
        
        <button type="submit" style="width: 100px; margin: 0 0 0 150px; padding: 0; cursor: pointer;">submit</button>
        
        <input type="hidden" name="token" value="<?=h(sha1(session_id()))?>">
    </form>
    <br><br>
    
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





<div id="bottom"></div><br>
<style>
#topButton {
  display: none;
  position: fixed;
  bottom: 20px;
  right: 30px;
  z-index: 99;
  font-size: 18px;
  border: none;
  outline: none;
  background-color: #2B2D32;
  color: white;
  cursor: pointer;
  padding: 15px;
  border-radius: 4px;
}

#topButton:hover {
  background-color: #555;
}
</style>

<button onclick="gototop()" id="topButton" title="Go to top">Jump to top</button>

<script>
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
    document.getElementById("topButton").style.display = "block";
  } else {
    document.getElementById("topButton").style.display = "none";
  }
}

scrollFunction()

function gototop() {
  document.body.scrollTop = 0;
  document.documentElement.scrollTop = 0;
  window.location.hash = '#path';
}

</script>
</body>
</html>