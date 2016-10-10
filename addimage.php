

<?php
date_default_timezone_set('asia/shanghai');
$initIdfile = fopen('./animalid.txt', "rw");
$initid = file_get_contents('./animalid.txt');
$idlist = split("/", $initid);

echo "initid: ".$idlist[0];
$filename = "1.png";
$handle = fopen($filename, "r");
$binary = fread($handle, filesize($filename));
fclose($handle);


for($i = 0; $i < sizeof($idlist); $i++) {
    $data = array(
      // "apikey" => "7AmWvsEj",
      "objectType" => "animals",
      "token" => "FWU7YCiUFvzL",
      "tokenHash" => "e348678cab1780395f7b4404ef755307",
      "objectAction" => "addPicture",
      "values" => array(
          array(
                // "animalID"=>"10657116",
                "animalID"=>$idlist[$i],
                "pictureBinary"=> base64_encode($binary)
            
          ),
      ),
    );



    $jsonData = json_encode($data);

    // set options, url, etc.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/");
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
      print_r($resultsArray);
    }
}
?>