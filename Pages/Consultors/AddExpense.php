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
            <script src="../Resources/Javascript/Expenses/ConsultorExpensesJS.js"></script>
            <link rel="stylesheet" href="../Resources/CSS/Timecards/Timecards_Layout.css">
            <link rel="stylesheet" href="../Resources/CSS/MasterCSS.css">
            <script>
                $( function() {
                    $( "#datepicker" ).datepicker({
                        altFormat: 'yyyy-mm-dd',  // Date Format used
                        firstDay: 0, // Start with Monday
                    });
                });
            </script>
            <meta charset="UTF-8">

            <title>
            </title>
        </head>
        <body>
            <div id="contenedor">
                <div class="titulo">ADD EXPENSES</div>
                <div id ="alertas"></div>
                <form id="formExpenses" onsubmit='return AssignExpense();' enctype="multipart/form-data">
                    <div class="Linea">
                        <div class="plaecHolder">
                            Travels
                        </div>
                        <div class="entrada">
                            <select id='Travel' class='unico'>
                                <?php
                                    $queryTravels =           $connection->query("SELECT ID, Name FROM travels WHERE AssignmentID IN (SELECT ID FROM assignment WHERE ConsultorID='".$_SESSION['consultor']['ID']."')");
                                    while($row = $queryTravels->fetch_array()){
                                        echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Category
                        </div>
                        <div class="entrada">
                            <select id='Category' class='unico'>
                                <?php
                                    $queryCategory =          $connection->query("SELECT ID, Name FROM expensecategory");
                                    while($row = $queryCategory->fetch_array()){
                                        echo "<option value='".$row['ID']."'>".$row['Name']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Name
                        </div>
                        <div class="entrada">
                            <input type='text' class='unico' id='Name' class='unico'>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Date
                        </div>
                        <div class="entrada">
                            <input type='text' class='unico' id='datepicker'>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Qty
                        </div>
                        <div class="entrada">
                            <input type='number' step='0.01' class='unico' id='Qty'>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Refundable: <input type='checkbox' id='Refundable'>
                        </div>
                    </div>
                    <div class="Linea">
                        <div class="plaecHolder">
                            Attachments
                        </div>
                        <div class="entrada">
                            <input type='file' name='file' multiple id='Attachments'>
                        </div>
                    </div>
                    <input type='submit' value='submit' id='submit' class='disabled'>
                </form>
            </div>
          </body>
      </html>
      <?php
  }else{
      header("Location: ../index.php");
  }
  ?>
