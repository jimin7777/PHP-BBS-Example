<?php session_start();
include "./API/dbcon.php";
// ini_set( 'display_errors', '0' );

if(!$_SESSION['UID']){
    echo "member";
    exit;
}

$memo  = $_POST['memo'];
$bid  = $_POST['bid'];
$memoid = $_POST['memoid']??0;

$sql="INSERT INTO memo
(bid, pid, userid, memo, status)
VALUES(".$bid.", ".$memoid.", '".$_SESSION['UID']."', '".$memo."', 1)";
$result=$mysqli->query($sql) or die($mysqli->error);
if($result)$last_memoid = $mysqli -> insert_id;

echo "<div class=\"card mb-4\" style=\"max-width: 100%;margin-top:20px;\">
<div class=\"row g-0\">
    <div class=\"col-md-12\">
    <div class=\"card-body\">
      <p class=\"card-text\">".$memo."</p>
      <p class=\"card-text\"><small class=\"text-muted\">".$_SESSION['UID']." / now</small></p>
    </div>
  </div>
</div>
</div>";

?>