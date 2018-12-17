<?php

include('connection.php'); //Includes de connection file, which contains de variable $connection to access the Database
session_start(); //Starts de session, to handle login information
if(isset($_POST['usuario'])){ //If a variable named usuario is sent, then
    $usuario =              $_POST['usuario'];      //Set variables
    $password =             sha1($_POST['contra']);
    $sID =                  session_id();
    //Create a query's variable named stmt
    $stmt =                 $connection->prepare("SELECT ID, SN, Email, Firstname, Lastname, Division, StartDate, EndDate, Type, Status, LastLogin, Logged, SessionID, Title FROM consultors WHERE Email=? AND Hash=?");
    $stmt ->                bind_param("ss", $usuario, $password); //the ? marks are substituted with the values received. This prevents SQL Injection
    $stmt ->                execute(); 
    $stmt ->                store_result();
    if ($stmt -> num_rows != 0){ //If a user with the received password and email exists
        //Create variables with the received results
        $stmt ->                bind_result($ID, $SN, $Email, $FirstName, $LastName, $Division, $StartDate, $EndDate, $Type, $Status, $Login, $Logged, $SessionID, $Title);
        $stmt ->                fetch(); //Fetch the array
        $query =                $connection->query("UPDATE consultors SET LastLogin=NOW(), Logged='1', SessionID='$sID' WHERE ID='$ID'"); //Update the status
        
        //Generate an array named consultor, that stores the main data from the database 
        
        $_SESSION['consultor'] = array("ID" => $ID, "SN" => $SN,"FirstName" => $FirstName, "LastName" => $LastName, "Email" => $Email, "Division" => $Division,
            "StartDate" => $StartDate, "EndDate" => $EndDate, "Type" => $Type, "Status" => $Status, "Login" => $Login, "Logged" => $Logged,
            "SessionID" => $SessionID, "Title" => $Title);
        
        //if the checkbox "Remember me" was checked 
        
        if($_POST['rem'] == '1'){
            $cookie_value =         array("ID" =>$ID, "First" => $FirstName, "Last" => $LastName); //Store an array of info in a cookie
            $cookie_expire =        time() + 60*2; //The cookie expires in two minutes. This can be changed
            $cookie_path =          '/';
            setcookie('remember_me', serialize($cookie_value), $cookie_expire, $cookie_path);
        }else{
            unset($_COOKIE['remember_me']);
            setcookie('remember_me', null, -1, '/');
        }
        echo "success"; //Web response if the query was successful
    }
    else{ //If no user with the email and password is found, then respond with
        echo    "Wrong Credentials";
    }
    $stmt->                 close(); //Close the query
}
if(isset($_POST['Straight'])){ //When the cookie is available
    $data =                 unserialize($_COOKIE['remember_me']); //Retrieve the cookie's data
    
    // Procceed with the same request as when email and password are submitted, but check for different fields
    
    $stmt =                 $connection->prepare("SELECT ID, SN, Email, Firstname, Lastname, Division, StartDate, EndDate, Type, Status, LastLogin, Logged, SessionID, Title FROM consultors WHERE ID=? AND Firstname=?");
    $stmt ->                bind_param("is", $data['ID'], $data['First']);
    $stmt ->                execute();
    $stmt ->                store_result();
    if ($stmt -> num_rows != 0){
        $stmt ->                bind_result($ID, $SN, $Email, $FirstName, $LastName, $Division, $StartDate, $EndDate, $Type, $Status, $Login, $Logged, $SessionID, $Title);
        $stmt ->                fetch();
        $query =                $connection->query("UPDATE consultors SET LastLogin=NOW(), Logged='1', SessionID='$sID' WHERE ID='$ID'");
        $_SESSION['consultor'] = array("ID" => $ID, "SN" => $SN,"FirstName" => $FirstName, "LastName" => $LastName, "Email" => $Email, "Division" => $Division,
            "StartDate" => $StartDate, "EndDate" => $EndDate, "Type" => $Type, "Status" => $Status, "Login" => $Login, "Logged" => $Logged,
            "SessionID" => $SessionID, "Title" => $Title);
        
        $cookie_value =         array("ID" =>$ID, "First" => $FirstName, "Last" => $LastName);
        //$cookie_expire =        time() + 60*60*24*365;// 365 days
        $cookie_expire =        time() + 60*2;
        $cookie_path =          '/';
        setcookie('remember_me', serialize($cookie_value), $cookie_expire, $cookie_path);
        echo "success";
    }
    else{
        echo    "Wrong Credentials";
    }
    $stmt->                 close();
}
