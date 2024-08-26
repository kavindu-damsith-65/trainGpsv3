<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "train_gps";



/*$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);*/

$api_key_value = "tPmAT5Ab3j7F9";

$api_key= $elephant_id = $lat = $lon= "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        $elephant_id = test_input($_POST["elephant_id"]);
        $lat = test_input($_POST["lat"]);   
        $lon = test_input($_POST["lon"]);
      
        
        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);
        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        
        $newSql="SELECT `elephant_id` FROM `elephant_locations` WHERE `elephant_id`='$elephant_id'";
        if ($result=mysqli_query($conn,$newSql))
        {
      
             $rowcount=mysqli_num_rows($result);
             if($rowcount>0){
                $sql="UPDATE `elephant_locations` SET `elephant_id`='$elephant_id',`lat`='$lat',`lon`='$lon' WHERE  `elephant_id`='$elephant_id'";
             }else{
                $sql="INSERT INTO `elephant_locations` (`elephant_id`, `lat`, `lon`) VALUES ('$elephant_id','$lat','$lon')";
             }
             if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } 
            else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }


       
             mysqli_free_result($result);
        }
      
    
        $conn->close();
    }
    else {
        echo "Wrong API Key provided.";
    }

}
else {
    echo "No data posted with HTTP POST.";
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}