<?php
date_default_timezone_set('asia/shanghai');
$myfile = fopen('./timestamp.txt', "rw");
$namefile = fopen('./name.txt', "rw");
$last_update_timestamp = file_get_contents('./timestamp.txt');

$servername = "localhost";  
$username = "root";
$password = "Aa1313250!";
$dbname = "pet";
$timeset = 0;

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    // echo "db error";
} 
// echo "last: " , $last_update_timestamp;
$sql = "SELECT Timestamp, Name, Sex, Age, Purebred_Mix, Short_bio, Main_picture, Long_bio, Other_photos FROM pet ORDER BY Timestamp ASC";
$result = $conn->query($sql);
// echo "result: " . $result;
// set options, url, etc.
// $id_num = $result->num_rows;
// echo "last: " , $last_update_timestamp;
if ($result->num_rows > 0) {
	// echo "last: " , $last_update_timestamp;
    // output data of each row
    while($row = $result->fetch_assoc()) {
        // var_dump($row);
        $timeset = $row["Timestamp"];
        $timeset = strtotime($timeset);
        
        // echo "name: " . $row["Name"]. " - sex: " . $row["Sex"]. " - Age: " . $row["Age"]. " - Purebreded/mix: ". (string)$row["Purebred_Mix"]. " -short_bio: ". $row["Short_bio"]. " - Main_picture: ". $row["Main_picture"]. " - Long_bio: " . $row["Long_bio"]. " - Other_photos: ". $row["Other_photos"].  "- Timestamp: " . $row["Timestamp"]. "<br>";

    	if(($timeset-$last_update_timestamp) > 0) {
            // echo "timeset diff: ", $timeset-$last_update_timestamp;
            // echo "step into if", " ---" , "timestamp: ", $timeset;
          echo "name: " . $row["Name"]. " - sex: " . $row["Sex"]. " - Age: " . $row["Age"]. " - Purebreded/mix: ". (string)$row["Purebred_Mix"]. " -short_bio: ". $row["Short_bio"]. " - Main_picture: ". $row["Main_picture"]. " - Long_bio: " . $row["Long_bio"]. " - Other_photos: ". $row["Other_photos"].  "- Timestamp: " . $row["Timestamp"]. "<br>";

	        // echo "name: " . $row["Name"]. " - sex: " . $row["Purebreded/mix"]. " - Age: " . $row["Age"]. " - Purebreded/mix: ". $row["Purebreded/mix"]. " -short_bio: ". $row["Short_bio"]. " - Main_picture: ". $row["Main_picture"]. " - Long_bio: " . $row["Long_bio"]. " - Other_photos: ". $row["Other_photos"].  "- Timestamp: " . $row["Timestamp"]. "<br>";
          $name .= $row["Name"]."/";
          $data = array(
    // "apikey" => "7AmWvsEj",
                "objectType" => "animals",
                "token" => "FWU7YCiUFvzL",
                "tokenHash" => "e348678cab1780395f7b4404ef755307",
                "objectAction" => "add",
                "values" => array(
                    array(
                        "animalName" => $row["Name"],
                        "animalSpeciesID" => "Dog",
                        "animalGeneralAge" => $row["Age"],
                        "animalSex" => $row["Sex"],
                        "animalMixedBreed" => $row["Purebred_Mix"],
                        "animalPrimaryBreedID" => "1",
                        "animalStatusID" => "1",
                        "animalDescription" => $row["Short_bio"]
                        // "animalSpecialneedsDescription" => "here's the description"
                    ),
                ),
              );
            $jsonData = json_encode($data);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_URL, "https://api.rescuegroups.org/http/");
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
             
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
            curl_setopt($ch, CURLOPT_POST, 1);
             
            
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             
            $result1 = curl_exec($ch);
             
            if (curl_errno($ch)) {
             
              $results = curl_error($ch);
              echo "result: " , $results;

             
            } else {
             
              // close cURL resource, and free up system resources
              curl_close($ch);
             
              $results = $result1;
              $resultsArray = json_decode($results);
              print_r($resultsArray);
            }
            echo "if end!!!!!";
        }


    }
    // echo "end!";

} else {
    echo "0 results";
}
// $result->free();

file_put_contents('./timestamp.txt', $timeset);
file_put_contents('./name.txt', $name);
fclose($myfile);
fclose($namefile);
$conn->close();
?>