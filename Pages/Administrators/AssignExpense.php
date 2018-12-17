<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
session_start();
//echo $_SESSION['dataBase']. " ". $_SESSION['loggedin']. " ". $_SESSION['userID']. " ". $_SESSION['userName'];
$IDUsuario =            $_SESSION['consultor']["ID"];
$UserName =             $_SESSION['consultor']["SN"];
include('../../Resources/WebResponses/connection.php');
unset($_SESSION['fecha']);
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
    $queryDel =         $connection->query("DELETE FROM lineas WHERE ConsultorID = '".$_SESSION['consultor']['ID']."' AND TimecardID='1'");
?>
    <html>
        <head>
            <script src="../Resources/Javascript/Expenses/AdminExpensesJS.js"></script>
            <link rel="stylesheet" href="../Resources/CSS/Timecards/Timecards_Layout.css">
            <script>
                $( function() {
                    $( "#Start, #End" ).datepicker({
                        altFormat: 'yyyy-mm-dd',  // Date Format used
                        firstDay: 0, // Start with Monday
                    });
                });
            </script>
            <meta charset="UTF-8">

            <title>
            </title>
        </head>
        <?php

            $query =        $connection->query("SELECT Email, Firstname, Lastname FROM consultors WHERE Status=1 AND Type!=0");
            echo "<datalist id='Consultores'>";
            while($row = $query->fetch_array()){
                echo "<option value='".$row['Email']."'>".$row['Firstname']." ".$row['Lastname']."</option>";
            }
            echo "</datalist>"

         ?>
        <body>
                <div id="contenedor">
                    <div class="titulo">ASSIGN TRAVEL</div>
                    <div id ="alertas"></div>
                    <form id="formExpenses" onsubmit='return AssignExpense();'>
                          Consultor's Mail <input type='text' id='Consultor' list='Consultores' onchange='DisableFields();'> <button onclick='EnableFields();' form=''>Search</button><br>
                          Assignment  <select id='Assignment'>
                                          <option value='0'> No Consultor Selected </option>
                                      </select><br>
                          Name <input type='text' id='Name' class='disabled' disabled><br>
                          Start Date <input type='text' id='Start' class='disabled' disabled><br>
                          End Date <input type='text' id='End' class='disabled' disabled><br>
                          <input type='submit' value='submit' id='submit' class='disabled' disabled>

                    </form>
                </div>
          </body>
      </html>
      <?php
  }else{
      header("Location: ../index.php");
  }
  ?>
