<?php
date_default_timezone_set('asia/shanghai');
$myfile = fopen('./animalid.txt', "rw");
$namefile = fopen('./name.txt', "rw");
$name = file_get_contents('./name.txt');
$namelist = split("/", $name);
// echo "name: ".$namelist[0];
fclose($namefile);
// echo "size: ".sizeof($namelist);
for($i = 0; $i < sizeof($namelist); $i++) {
  $data = array(
    "apikey" => "7AmWvsEj",
    "objectType" => "animals",
    "objectAction" => "publicSearch",
    "search" => array (
      // "resultStart" => 0,
      // "resultSort" => "animalID",
      "resultOrder" => "desc",
      "filters" => array(
        array(
          // "animalName" => "d",
          "fieldName" => "animalName",
          "operation" => "equals",
          "criteria" => $namelist[$i],
        ),
        
      ),
      "fields" => array("animalID")
    ),
  );
  $jsonData = json_encode($data);

  // set options, url, etc.
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
  curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/json/");
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
   
  curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  curl_setopt($ch, CURLOPT_POST, 1);
   
  //curl_setopt($ch, CURLOPT_VERBOSE, true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   
  $result = curl_exec($ch);
   
  if (curl_errno($ch)) {
   
    $results = curl_error($ch);
    echo "result: " , $results;

   
  } else {
   
    // close cURL resource, and free up system resources
    curl_close($ch);
   
    $results = $result;
    $resultsArray = json_decode($results);
    $x = $resultsArray->data;
    $animalid = reset($x)->animalID;
    $id .=$animalid."/";
    print_r($animalid);
    
  }
}
file_put_contents('./animalid.txt', $id);
fclose($myfile);

?>


