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
include('../Resources/WebResponses/connection.php');
include('../Resources/InfoFill/ExpenseFill.php');
$queryTravel =          $connection->query("SELECT ID FROM travels WHERE ConsultorID='".$_SESSION['consultor']['ID']."' AND Status=1");
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
?>
    <html>
        <head>
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/Listas_Layout.css">
            <link rel="stylesheet" href="../Resources/CSS/Listas_Contenido/ListasPrincipales_Layout.css">
            <script src="../Resources/Javascript/TableFilters.js"></script>
            <?php
                if($_SESSION['consultor']['Type'] == '0'){
                    echo "<script src='../Resources/Javascript/Expenses/AdminExpensesJS.js'></script>";
                }else{
                    echo "<script src='../Resources/Javascript/Expenses/ConsultorExpensesJS.js'></script>";
                }
            ?>
            <script src="../Resources/Javascript/menu.js"></script>
            <meta charset="UTF-8">
            <title>
            </title>
        </head>
        <body>
            <!-- -->
            <?php 
                DisplayForma($connection, $queryTravel);
            ?>    
                </div>
                <div class="cont-boton">
                    <!--input type="submit" name="" value="ADD" id='submittir' form="formExpenses"-->
                    <?php
                        if($_SESSION['consultor']['Type'] != '0' && $queryTravel->num_rows == 0){
                    ?>
                            <input type="submit" name="" value="ADD" id='submittir' form="" onclick='RequestExpense();'>
                    <?php
                        }
                        else if($_SESSION['consultor']['Type'] != '0' && $queryTravel->num_rows > 0){
                            
                            echo "<input type='submit' name='' value='Add another' id='submittir' form='' onclick='agregarLinea();'>";
                            echo "<input type='file'  style='float: left; width: 80px !important;' name='file' multiple id='Attachments'>";
                            echo "<input type='submit' name='' value='ADD' id='submittir' onclick='getData();' form=''>";
                        }else if($_SESSION['consultor']['Type'] == 0){
                            echo "<input type='submit' name='' value='ADD' id='submittir' form='formExpenses'>";
                        }
                    ?>
                    <input type="button" id="cancel-boton" value="cancel">
                </div>
            </div>
            <!-- -->
            <div class="wrapper">
                <div>
                    <h1>Expenses</h1>
                </div>
                <div class="button" align="center">
                    <?php
                        if($_SESSION['consultor']['Type'] != 0 && $queryTravel->num_rows > 0){
                            echo "<input type='button' id='boton' value='ADD Expense'>";
                        }else{
                            echo "<input type='button' id='boton' value='Request Travel/Expense'>";
                        }
                    ?>
                </div>
            </div>
            </div>
            
            <div class="box">
                <div class="signup-box"> 
                    <div class="infoTable" id="tabla">
                        <div class="contacto" style='height:    30px;'>
                            <form class='searcFilters' id='SearchTable' onsubmit="return searchCards();">
                                <div class='eName searchParam'>
                                    <input type="text" placeholder="Name" class='searchFilters' id="assignmentSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Name FROM travels");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class='eTName searchParam'>
                                    <input type="text" placeholder="Project" class='searchFilters' id="assignmentSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Name FROM project");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class='eCName searchParam'>
                                    <input type="text" placeholder="Consultor" class='searchFilters' id="assignmentSearch" onkeyup="filter(this);">
                                    <?php
                                        $queryCards =           $connection->query("SELECT Firstname, Lastname FROM consultors");
                                        while($row = $queryCards->fetch_array()){
                                            echo "<div class='cardSearchOpt rs'>".$row[0]."</div>";
                                        }
                                        $queryCards ->  close();
                                    ?>
                                </div>
                                <div class='eCategory searchParam'>
                                    <input type="date" class='searchFilters' id="assignmentStartSearch" onkeyup="filter(this);">
                                </div>
                                <div class='eSubmitD searchParam'>
                                    <input type="date" class='searchFilters' id="assignmentEndSearch" onkeyup="filter(this);">
                                </div>
                                <div class='eQty searchParam'>
                                    <select id="statusSearch" class="searchParam" class='searchFilters'>
                                        <option value="0">Saved</option>
                                        <option value="1">Submitted</option>
                                    </select>
                                </div>
                                <div class='eStatus'>
                                    <input type="submit" id="searchParameters" value="Search">
                                </div>
                            </form>
                        </div>
                        <div class="contacto">
                            <div class='eName'>Expense Name</div>
                            <div class='eTName'>Project</div>
                            <div class='eCName'>Consultor</div>
                            <div class='eCategory'>From</div>
                            <div class='eSubmitD'>To</div>
                            <div class='eQty'>Expenses' Qty</div>
                            <div class='eStatus'>Status</div>
                        </div>
                <?php
                    if($_SESSION['consultor']['Type'] != '0'){
                        $query =            $connection->query("SELECT t.*, project.Name as pName, consultors.Firstname, consultors.Lastname, COUNT(expenses.ID) AS expQty
                                                                FROM travels t
                                                                INNER JOIN assignment ON (t.AssignmentID = assignment.ID)
                                                                INNER JOIN project ON (assignment.ProjectID = project.ID)
                                                                INNER JOIN consultors ON (consultors.ID = t.ConsultorID)
                                                                INNER JOIN expenses ON (expenses.TravelID = t.ID)
                                                                GROUP BY t.ID
                                                                WHERE t.ConsultorID =".$_SESSION['consultor']['Type']."");
                    }
                    else{
                        $query =            $connection->query("SELECT t.*, project.Name as pName, consultors.Firstname, consultors.Lastname, COUNT(expenses.ID) AS expQty
                                                                FROM travels t
                                                                INNER JOIN assignment ON (t.AssignmentID = assignment.ID)
                                                                INNER JOIN project ON (assignment.ProjectID = project.ID)
                                                                INNER JOIN consultors ON (consultors.ID = t.ConsultorID)
                                                                INNER JOIN expenses ON (expenses.TravelID = t.ID)
                                                                GROUP BY t.ID");

                    }
                    while($row = $query->fetch_array()){
                        if($row['Status'] == 0){
                            $status = 'Submitted';
                        }else if($row['Status'] == 1){
                            $status =   'Approved';
                        }
                        echo"
                            <div class='contacto'>
                                <div class='eName' style='cursor: pointer;' onclick=\"LoadPage('Expense.php?id=".$row['ID']."');\">".$row['Name']."</div>
                                <div class='eTName'>".$row['pName']."</div>
                                <div class='eCName'>".$row['Firstname']." ".$row['Lastname']."</div>
                                <div class='eCategory'>".substr($row['FromDate'], 0, 10)."</div>
                                <div class='eSubmitD'>".substr($row['ToDate'], 0, 10)."</div>
                                <div class='eQty'>".$row['expQty']."</div>
                                <div class='eStatus'>".$status."</div>
                            </div>
                       ";
                    }
                ?>
                    </div>
                </div>
            </div>
        </body>
    </html>
    <?php
}else{
    header("Location: ../index.php");
}
$queryTravel -> close();
?>
