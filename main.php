<!DOCTYPE html>
<html>
<body>

<?php
// Incuding IDtable class
require_once("idtable.php");

// Creating buffer class
class buffer{
    public $t;
    public $s;
    public $ids;
    function __construct(){
        $this-> ids = new IDTable();
    }
    public function load($data){
        $this-> t = $data["t"];
        $this-> s = $data["s"];
        $this-> ids = $this-> ids-> load($data["ids"]);
    } 
}

// Including interface
require_once("interface.php");

// Loading responses
$res = json_decode(file_get_contents ('response.json'), TRUE);

// Loading last buffer
$bufl = new buffer();
$bufl-> load(json_decode(file_get_contents ('bufl.json'), TRUE));

// Creating new buffer
$buf = new buffer();

// Buffer size (in seconds)
$buf-> s = 30;

// Initalizing interface
$stream = new Connection();

$data = array(); // Storage for all the messages
$latestw = 0; // Time of most recent message
for($i = 0; $i < count($res); $i++){ // Iterate through querys
    $data[$i] = $stream-> get($res[$i]['query'], $bufl-> t - $bufl-> s); // Download messages
    if ($latestw < $data[$i][0]-> t) $latestw = $data[$i][0]-> t; // Update  latestw
}

$buf-> t = $latestw; // Set new buffer time
for ($j = 0; $j < count($data); $j++){ // Iterate through messages
    for ($i = 0; $i < count($data[$j]) && $data[$j][$i]-> t > $bufl-> t - $bufl-> s; $i++){
        if ($data[$j][$i]-> t >= $buf-> t - $buf-> s) // Check if we have to put it into the new buffer
            $buf-> ids-> insert($data[$j][$i]-> id); // Put it into the new buffer
        if ($data[$j][$i]-> t <= $bufl-> t){
            if (!$bufl-> ids-> find($data[$j][$i]-> id)) // Check if we have responded before
                $stream-> send($data[$j][$i]-> name, $res[$j]['message_before'].$data[$j][$i]-> name.$res[$j]['message_after']);
        }else{ // Send the response
            $stream-> send($data[$j][$i]-> name, $res[$j]['message_before'].$data[$j][$i]-> name.$res[$j]['message_after']);
        }
    }
}


$outf = fopen("bufl2.json", "w");
fwrite($outf, json_encode($buf));
fclose($outf);
?>

</body>
</html> 