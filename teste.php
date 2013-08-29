<?php

$array = array();

array_push($array, "A");
array_push($array, "F");
array_push($array, "fas");
array_push($array, "T");
array_push($array, "Q");
array_push($array, "B");
array_push($array, "P");

$count = count($array);

for($i=0;$i<$count;$i++) {
	for($j=$i+1;$j<$count;$j++) {

		if($array[$i] > $array[$j]) {
			$aux = $array[$j];
			$array[$j] = $array[$i];
			$array[$i] = $aux;
		}

	}
}

foreach($array as $a)
	echo $a."<br>";

?>