<?PHP

require('config.php');
$date = $_POST['date'];
$hostname = mariahost;
$username = mariauser;
$password = mariapass;

try{
	$DBH = new PDO("mysql:host=$hostname;dbname=derp", $username, $password);

	echo "connected to db. </BR>";

	$getShiftNotes = $DBH->prepare("SELECT * FROM shiftnotes where created_at>='$date'");
	$getShiftNotes->setFetchMode(PDO::FETCH_ASSOC);
	$getShiftNotes->execute();

	$result = $getShiftNotes->fetchAll();

	foreach($result as $row){
		echo "Note-'".$row['note']."' by: ".$row['user_name']."@".$row['created_at']."</BR></BR>";
		}
	$dbh = null;
}
catch(PDOException $e){
	echo $e->getMessage();
}

echo "I'm alive </BR>";

?>
