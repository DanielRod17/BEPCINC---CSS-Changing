<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
 if(!session_status() == PHP_SESSION_NONE){
    session_start();
 }
//echo $_SESSION['dataBase']. ' '. $_SESSION['loggedin']. ' '. $_SESSION['userID']. ' '. $_SESSION['userName'];
$IDUsuario =            $_SESSION['consultor']['ID'];
$UserName =             $_SESSION['consultor']['SN'];
$resultado =            array();
include('../Resources/WebResponses/connection.php');

function DisplayForma($connection, $queryTravel){
    
    if($_SESSION['consultor']['Type'] != '0' && $queryTravel->num_rows > 0){
        echo "
        <div class='sidebar-contact' style='width: 70%; margin-left: -70%;'>
        <div class='contenedor'>
            <h2>ASSIGN EXPENSE</h2>
            <hr id='line'>
            <form id='formExpenses' onsubmit='return AssignExpense();' enctype='multipart/form-data'>
                <div class='Linea'>
                    <div class='plaecHolder'>
                        Travels
                    </div>
                    <div class='entrada'>
                        <select id='Travel' class='unico'>";
                        $queryTravels =           $connection->query("SELECT ID, Name FROM travels WHERE AssignmentID IN (SELECT ID FROM assignment WHERE ConsultorID='".$_SESSION['consultor']['ID']."')");
                        while($row = $queryTravels -> fetch_array()){
                            echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                        }
                    echo "
                        </select>
                    </div>
                </div>
                <label style='margin-left: 86%; font-size:  10px;'>Billable</label>
                <label style='font-size:  10px; margin-left: 15px;'>Refundable</label>
                <div class='Linea' id='copy'>
                    <div class='plaecHolder'>
                        <button form='' onclick='deleteLinea(event.target);' style='display: none;'>Delete</button>
                    </div>
                    <div class='entrada'>
                        <select id='Category' class='unico' style='width: 17%; float: left; margin-right: 3%;'>";
                                $queryCategory =          $connection->query("SELECT ID, Name FROM expensecategory");
                                while($row = $queryCategory->fetch_array()){
                                    echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                }
                        echo "
                        </select>
                        <input type='date' class='unico datepicker' placeholder='MM-DD-YYYY' style='width: 15%; float: left;  margin-right: 3%;'>
                        <input type='text' class='unico' placeholder='Description' id='description' style='width: 15%; float: left;  margin-right: 3%;'>
                        <input type='number' step='0.01' class='unico' placeholder='Amount' id='ammount' style='width: 15%; float: left;  margin-right: 3%;'>
                        <select id='Currency' class='unico' style='width: 10%; float: left; margin-right: 3%;'>
                            <option value='0'>MXN</option>
                            <option value='1'>USD</option>
                        </select>
                        <input type='checkbox' id='billable' style=' display: inline-block !important; width: 2%; height: 20px; float: left !important;  margin-right: 3%;'>
                        <input type='checkbox' id='refundable' style=' display: inline-block !important; width: 2%; height: 20px; float: left !important;  margin-right: 3%;'>
                    </div>
                </div>
            </form>";
    }else if($_SESSION['consultor']['Type'] != 0 && $queryTravel->num_rows == 0){
        echo "
        <div class='sidebar-contact'>
        <div class='contenedor'>
            <h2>REQUEST EXPENSE</h2>
            <hr id='line'>
            <form id='formExpenses' onsubmit='return RequestExpense();'>
                Destination <input type='text' id='ExpDesc'>
                Description  <br><textarea rows='9' cols='60' placeholder='List the consultors, define dates, project, etc. and describe the travel...'></textarea><br>
            </form>";
    }else{
        $query =        $connection->query("SELECT Email, Firstname, Lastname FROM consultors WHERE Status=1 AND Type!=0");
        echo "<datalist id='Consultores'>";
        while($row = $query->fetch_array()){
            echo "<option value='".$row['Email']."'>".$row['Firstname']." ".$row['Lastname']."</option>";
        }
        echo "</datalist>
        <div class='sidebar-contact'>
            <div class='contenedor'>
                <h2>ASSIGN EXPENSE</h2>
                <hr id='line'>
                <form id='formExpenses' onsubmit='return AssignExpense();'>
                      Consultor's Mail <input type='text' id='Consultor' list='Consultores' onchange='DisableFields();'> <button onclick='EnableFields();' form=''>Search</button><br>
                      Assignment  <select id='Assignment'>
                                      <option value='0'> No Consultor Selected </option>
                                  </select><br>
                      Name <input type='text' id='Name' class='disabled' disabled><br>
                      Start Date <input type='date' id='Start' class='disabled' disabled><br>
                      End Date <input type='date' id='End' class='disabled' disabled><br>
                </form>";

    }
}
function DisplayFiles($connection, $ID){
    $totalFiles =           0;
    $email =                "";
    $stmt =                 $connection->prepare("  SELECT e.Attachments, consultors.Email, e.ExpenseDate 
                                                    FROM expenses e 
                                                    INNER JOIN travels ON(e.TravelID = travels.ID)
                                                    INNER JOIN consultors ON (consultors.ID = travels.ConsultorID)
                                                    WHERE e.TravelID=?");
    $stmt ->                bind_param('i', $I);
    $I =                    $ID;
    $stmt ->                execute();
    $stmt ->                bind_result($a, $e, $d);
    while ($stmt->fetch()) {
        $resultado[] = array($a, $d);
        if($email == ""){ $email = $e;  }
    }
    $stmt ->                close();
    $archivos =             array();
    foreach($resultado as $files){
        $file =                 explode("~", $files[0]);
        foreach($file as $f){
            if (!in_array($f, $archivos) && $f != "") {
                $totalFiles++;
                $archivos[$f] =       array($f, $files[1]);
            }
        }
    }
    $totalFiles = count($archivos);
    $extensions = array('jpg', 'png', 'bmp');
    echo "
    <div id='timecards' class='contOpc cont'>
        <div class='InfoAm'>
            $totalFiles Files
        </div>";
            if(!empty($archivos)){
                foreach($archivos as $archivo){
                    $ext = explode(".", $archivo[0]);
                    $ext =  end($ext);
                    echo "<div class='Line'>";
                        echo "<div class='preview' onclick='showPreview(this);'>";
                        $src =      "../Files/Expenses/$e/$archivo[0]";
                            if(in_array(strtolower($ext), $extensions)){
                                echo "<img width='177' id='$src' height='100' src='$src'/>";
                            }else if(strtolower($ext) == "pdf"){
                                echo "<i class='far fa-file-pdf' id='$src'></i>";
                            }
                        echo "</div>";
                        echo "<div class='info'>";
                            $info =     explode("/", $archivo[0]);
                            echo "Submitted Date: ".substr($info[0],0 ,10)."<br><br>Filename: ".$info[1]."<br><br><button onclick='Download(this);'>Download</button></br>";
                        echo "</div>";
                    echo "</div>";
                }
            }else{
                echo "
                    <div class='Line'>
                        No files found
                    </div>
                ";
            }
    echo "</div>";
}


function DisplayExpenses($connection, $ID){
    $stmt =                 $connection->prepare("SELECT e.*, expensecategory.Name as catName
                                                FROM expenses e
                                                INNER JOIN expensecategory ON (e.Category = expensecategory.ID)
                                                WHERE TravelID=?");
  $stmt ->                bind_param('i', $I);
  $I =                    $ID;
  $stmt ->                execute();
  $meta =                 $stmt->result_metadata();
  while ($field = $meta->fetch_field())
  {
      $paramas[] = &$rowa[$field->name];
  }
  call_user_func_array(array($stmt, 'bind_result'), $paramas);
  while ($stmt->fetch()) {
      foreach($rowa as $keya => $vala)
      {
          $d[$keya] = $vala;
      }
      $resultado[] = $d;
  }
  $stmt ->                close();

  echo "
    <div id='expenses' class='contOpc cont' style='margin-bottom: 20px;'>
        <div class='InfoAm'>
            Expenses
        </div>
        <div class='Linea' style='background-color: rgb(250, 250, 248)'>
            <div class='EXPcolumna'>
                Category
            </div>
            <div class='EXPcolumna'>
                Date
            </div>
            <div class='EXPcolumna'>
                Qty
            </div>
            <div class='EXPcolumna'>
                Currency
            </div>
            <div class='EXPcolumna'>
                Billable
            </div>
            <div class='EXPcolumna'>
                Refundable
            </div>
        </div>";
            if(!empty($resultado)){
                foreach($resultado as $fila){
                    $currency =         "MXN";
                    $billable =         "No";
                    $refundable =       "No";
                    
                    if($fila['Currency'] == "1")
                        $currency =         "USD";
                    if($fila['Billable'] == "1")
                        $billable =         "Yes";
                    if($fila['Refundable'] == "1")
                        $refundable =       "Yes";
                    
                    echo "<div class='Linea'>
                        <div class='EXPcolumna'>
                            ".$fila['catName']."
                        </div>
                        <div class='EXPcolumna'>
                            ".substr($fila['ExpenseDate'],0 ,10)."
                        </div>
                        <div class='EXPcolumna'>
                            $".$fila['Quantity']."
                        </div>
                        <div class='EXPcolumna'>
                            ".$currency."
                        </div>
                        <div class='EXPcolumna'>
                            ".$billable."
                        </div>
                        <div class='EXPcolumna'>
                            ".$refundable."
                        </div>
                    </div>";
                }
            }else{
                echo "<div id='timecardsLine'>
                    <div class='Line'>
                        No timecards found
                    </div>
                </div>";
            }
    echo "</div>";
}


function DisplayDetails($connection, $ID){

    $query =                  $connection->prepare("SELECT t.*, project.Name as pName, consultors.Firstname, consultors.Lastname, COUNT(expenses.ID) AS expQty
                                                    FROM travels t
                                                    INNER JOIN assignment ON (t.AssignmentID = assignment.ID)
                                                    INNER JOIN project ON (assignment.ProjectID = project.ID)
                                                    INNER JOIN consultors ON (consultors.ID = t.ConsultorID)
                                                    INNER JOIN expenses ON (expenses.TravelID = t.ID)
                                                    WHERE t.ID =?");
    $query ->               bind_param('i', $I);
    $I =                    $ID;
    $query ->               execute();
    $meta =                 $query->result_metadata();
    while ($field = $meta->fetch_field())
    {
        $params[] = &$row[$field->name];
    }
    call_user_func_array(array($query, 'bind_result'), $params);
    while ($query->fetch()) {
        foreach($row as $key => $val)
        {
            $c[$key] = $val;
        }
        $result[] = $c;
    }
    $query ->               close();

    $queryCategory =          $connection->prepare("SELECT  ec.*, delv.totalDelivered, delv.Currency
                                                  FROM expensecategory ec 
                                                  LEFT JOIN (SELECT Category, Currency, SUM(Quantity) AS 'totalDelivered' FROM expenses WHERE TravelID=?  GROUP BY Category, Currency) AS delv 
                                                  ON ec.ID =  delv.Category");
    $queryCategory ->         bind_param('i', $I);
    $I =                      $ID;
    $queryCategory ->         execute();
    $queryCategory ->         bind_result($IDI, $Nome, $totalDel, $Currency);
    $arrayCategories =        array();
    while($queryCategory -> fetch()){
        $cu =       "USD";
        if($Currency == "0")
            $cu =   "MXN";
        if(!array_key_exists("$Nome",       $arrayCategories)){
            $arrayCategories["$Nome"] =     array(array($totalDel, $cu));
        }else{
            array_push($arrayCategories["$Nome"], array($totalDel, $cu));
        }
    }
    $queryCategory ->         close();

    $content = "
    <div id='details' class='contOpc detOp'>
        <form id ='detailsForm'>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Project
                  </div>
                  <div class='campoDato'>
                      ".$c['pName']."
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      From
                  </div>
                  <div class='campoDato'>
                      ".substr($c['FromDate'], 0, 10)."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      To
                  </div>
                  <div class='campoDato'>
                      ".substr($c['ToDate'], 0, 10)."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
                <div class='right'>
                    <div class='nombreDato'>
                        Consultor
                    </div>
                    <div class='campoDato'>
                        ".$c['Firstname']." ".$c['Lastname']." 
                        <div class='lapiz'>
                            <i class='fas fa-pencil-alt'></i>
                        </div>
                    </div>
                </div>
            </div>";
            $lineaI =       0; 
            foreach($arrayCategories as $key=>$value){
                $align =    "right";
                
                if($lineaI % 2 == 0){
                    $align =    "left";
                    $content = $content."<div class='datos'>";
                }
                
                $content = $content."
                    <div class='$align'>
                        <div class='nombreDato'>
                            $key Expenses
                        </div>
                        <div class='campoDato'>";
                                if($value[0][0] !== null)
                                    $content = $content."$".$value[0][0]." ".$value[0][1];
                                else
                                    $content = $content."N/A";
                                if(array_key_exists(1, $value))
                                    $content = $content." -  $".$value[1][0]." ". $value[1][1];
                                $content = $content."<div class='lapiz'>
                                <i class='fas fa-pencil-alt'></i>
                            </div>
                        </div>
                    </div>";
                if($lineaI % 2 != 0){
                    $content = $content."</div>";
                }
                $lineaI++;
            }
        $content = $content."</div>
        </form>
  </div>";
  $respuesta = array($content, $c);
  return $respuesta;
}
?>
