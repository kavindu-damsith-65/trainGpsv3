<!DOCTYPE html>
<?php
   session_start();
   session_destroy();

   if ( isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
      if(isset($_POST['name']) &&($_POST['name']!="" && $_POST['password']!="")){
        $name = $_POST['name'];
        $password = $_POST['password'];
        
       
        $servername = "localhost";
        $username = "root";
        $passwordDb = "";
        $dbname = "train_gps";
        
       
        $conn = new mysqli($servername, $username, $passwordDb, $dbname);
        
      
        if ($conn->connect_error) {
          die("Connection failed: " . $conn->connect_error);
        }
       
        $sql = "SELECT `train_id` FROM `train_locations` WHERE `user_name`='$name' and `password`='$password'";
     
        $result = $conn->query($sql);

       
        if ($result->num_rows > 0) {
        if($row = $result->fetch_assoc()){
            session_start();
            $_SESSION['userLogin'] = true;
            $_SESSION['trainId'] = $row['train_id'];

            // echo '<script>alert('.$_SESSION['trainId'].')</script>';

            header("Location: index.php");
             exit;
        }
       
        } else {

              ?>
     
                <script>  
              
                window.onload = function() {
                    document.getElementById("errorMsg").innerHTML = "User name or Password is incorrect!"
                };
                </script>
<?php
              
        }

        // Close the connection
        $conn->close();

        
        
      }
   
  
  }
?>



<head>
    <meta charset="UTF-8">
    <title>LogIn</title>
</head>

<body>

    <div class="box">

        <img src="../images/location.png" style="width:60px;margin-top:10px">
        <div class="page">
            <div class="header">
                <a id="login" class="active" href="#login">log in</a>

            </div>
            <div id="errorMsg"></div>
            <div class="content">
                <form class="login" name="loginForm" onsubmit="return validateLoginForm()" method="POST"
                    action="login.php">
                    <input type="text" name="name" id="logName" placeholder="Username">
                    <input type="password" name="password" id="logPassword" placeholder="Password">
                    <div id="check">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <br><br>
                    <input type="submit" value="Login">
                    <div class="row">
                    <div class="col-6 ">
                        <a style="font-size:15px"href="../app_model/admin/adminlogIn.php">Are You an Admin?</a>
                        </div>
                        <br>
                        <div class="col-6">
                        <a style="font-size:18px"href="../index.php">Not a Train Driver?</a>
                        </div>
                        
                   
                   
                    </div>
                    
                </form>
                <form class="signup" name="signupForm" method="POST">
                    <input type="email" name="email" id="signEmail" placeholder="Email">
                    <input type="text" name="name" id="signName" placeholder="Username">
                    <input type="password" name="password" id="signPassword" placeholder="Password"><br>
                    <input type="submit" value="SignUp">
                </form>
            </div>
        </div>
    </div>



    <style>
    body {



        margin: 0;
        padding: 0;
        background-image: url(../images/train2.jpg);
        background-size: cover;
        background-repeat: no-repeat;
        font-family: Verdana, Tahoma, sans-serif;
    }

    .box {
        width: 380px;
        height: 500px;
        background-color: rgba(0, 0, 0, 0.7);
        padding: 20px;
        margin: 8% auto 0;
        text-align: center;
        position: relative;
        box-sizing: border-box;
        flex-direction: column;
    }

    .box img {
        width: 100px;
        margin-top: -50px;
    }

    .header {
        /* width: 300px;
        height: 50px; */
        text-transform: uppercase;
        text-align: center;
        /* display: inline-flex; */
        padding-top: 20px;
        padding-bottom: 0;
        /* justify-content: space-between; */
    }

    .header a {
        font-size: 30px;
        text-decoration: none;
        color: #ffffff;
        display: inline-flex;
        padding-left: 25px;
        padding-right: 25px;
        justify-content: center;
        cursor: pointer;
    }

    .header .active {
        /* color: #0bd38a; */
        font-weight: bold;
        position: relative;
    }

    .header .active:after {
        position: absolute;
        border: none;
        content: "";
    }

    #errorMsg {
        color: #eb3b3b;
        text-align: center;
        font-size: 14px;
        padding-bottom: 20px;
        padding-bottom: 10px;

    }

    .content {
        display: inline-flex;
        overflow: hidden;
    }

    form {
        position: relative;
        display: inline-flex;
        width: 100%;
        height: 100%;
        flex-shrink: 0;
        flex-direction: column;
        transition: right 0.5s;
    }

    .login a {
        text-decoration: none;
        color: #ffffff;
        font-size: 15px;
        text-align: center;
        cursor: pointer;

    }

    .extend form {
        right: 100%;
    }

    input[type=text],
    input[type=password],
    input[type=email] {
        border-radius: 5px;
        display: block;
        position: relative;
        border: 4px solid #ffffff;
        padding: 12px;
        margin-bottom: 15px;
        width: 100%;
        box-sizing: border-box;
        font-size: 17px;
        outline: none;
    }

    input[type=text]:hover,
    input[type=password]:hover,
    input[type=email]:hover {
        border: 4px solid #0bd38a;

    }

    input[type=submit] {
        display: block;
        position: relative;
        border: 3px solid #0bd38a;
        padding: 12px;
        margin-bottom: 20px;
        width: 100%;
        border-radius: 5px;
        background-color: #0bd38a;
        text-align: center;
        font-size: 20px;
        color: #ffffff;
        cursor: pointer;
        outline: none;
    }

    input[type=submit]:hover {
        background-color: #ffffff;
        color: #000000;
        font-weight: bold;
    }

    input[type=checkbox] {
        background-color: #0bd38a;
    }

    #check {
        margin-top: 10px;
        text-align: left;
        color: #ffffff;
        font-size: 15px;
    }
    </style>


    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script src="index.js"></script>

    <script>
    $(window).on("hashchange", function() {
        if (location.hash.slice(1) == "signup") {
            $(".page").addClass("extend");
            $("#login").removeClass("active");
            $("#signup").addClass("active");
        } else {
            $(".page").removeClass("extend");
            $("#login").addClass("active");
            $("#signup").removeClass("active");
        }
    });
    $(window).trigger("hashchange");

    function validateLoginForm() {
        var name = document.getElementById("logName").value;
        var password = document.getElementById("logPassword").value;

        if (name == "" || password == "") {
            document.getElementById("errorMsg").innerHTML = "Please fill the required fields"
            return false;
        } else {

            return true;
        }
    }
    </script>

</body>