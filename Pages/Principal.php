<?php
session_start();
$IDUsuario =            $_SESSION['consultor']["ID"];
$UserName =             $_SESSION['consultor']["SN"];
include('../Resources/WebResponses/connection.php');
if (isset($_SESSION['consultor']['Login']) && $_SESSION['consultor']['Login'] == true){
?>
    <html>
        <head>

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
            <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
            <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" href="../Resources/CSS/Principal_Layout.css">
            <link href="https://fonts.googleapis.com/css?family=Montserrat|Cairo" rel="stylesheet">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
            <meta charset="UTF-8">
            <title></title>
        </head>
        <body onscroll="DetectScroll();" onload=" DetectScroll();">
            <div id ="alertas"></div>
            <div id="contenedorPrincipal">
                <div id="menu">
                    <div id="informacionPrincipal">
                        <div id="logo" style="background-color: rgba(200, 20, 20, 20, 0.8);" onclick='Logout();'>
                            <img id="logoIMG" src="../Resources/bee-logo1.png"/>
                        </div>
                        <div id="nombreUsuario" style="color: black; position: absolute; margin-top: 90px; margin-left: 125px;">
                            <?php echo $_SESSION['consultor']['FirstName']." ".$_SESSION['consultor']['LastName']." - ".$_SESSION['consultor']['Title'];  ?>
                        </div>
                        <!--div id="busqueda">
                            <button style=" width:  70px; height: 100%; border: none; background-color: white; float: left;" >Hola</button>
                            <input style="width: calc(100% - 70px); height: calc(100% - 1px);" type="text" placeholder="Search projects and more">
                        </div-->
                        <!--div id="user">
                            <div class="circulito" id="profilepic">
                            </div>
                            <div class="circulito" id="profilepic">
                            </div>
                            <div class="circulito" id="profilepic">
                            </div>
                            <div class="circulito" id="profilepic">
                            </div>
                            <div class="circulito" id="profilepic">
                                <?php
                                    /*foreach (glob("./Resources/Images/UserPic/$IDUsuario.*") as $filename) {
                                        echo "<img style='width: 100%; height: 100%; border-radius: 50px;' src='$filename'>";
                                    }*/
                                ?>
                            </div>
                        </div-->
                        <div id='opcionesGrles'>
                            <?php
                                if($_SESSION['consultor']['Type'] == '0'){
                            ?>
                                <div class='opGral'onclick="LoadPage('Administrators/Contacts.php');">
                                    CONTACTS
                                </div>
                                <div class='opGral' onclick="LoadPage('Administrators/Accounts.php');">
                                    ACCOUNTS
                                </div>
                                <div class='opGral' onclick="LoadPage('Administrators/POs.php');">
                                    BUDGET
                                </div>
                            <?php
                                }
                            ?>
                            <div class='opGral' onclick="LoadPage('Projects.php');">
                                PROJECTS
                            </div>
                            <div class='opGral' onclick="LoadPage('Timecards.php');" >
                                TIMECARDS
                            </div>
                            <div class='opGral'onclick="LoadPage('Assignments.php');">
                                ASSIGNMENTS
                            </div>
                            <div class='opGral'onclick="LoadPage('Expenses.php');">
                                EXPENSES
                            </div>
                        </div>
                    </div>
                </div>
                    <!--div id="opciones">
                        <div class="opcion" onclick="LoadPage('Dashboard.php')">
                            Dashboard
                        </div>
                        <div class="opcion" onclick="<?php if($_SESSION['consultor']['Type'] == '0'){ echo "LoadPage('Timecards.php');\" >Admin. Cards</div>";}else{  echo "LoadPage('Timecards.php');\" >My Timecards</div>"; } ?>
                        <div class="opcion">Expenses <div class='dropdown-content'>
                            <div class='links' onclick="LoadPage('Expenses.php');">Expenses</div>
                            <div class='links' onclick="<?php if($_SESSION['consultor']['Type'] == '0'){ echo "LoadPage('Administrators/AssignExpense.php');\" >Admin. Expenses</div>";}else{  echo "LoadPage('Consultors/AddExpense.php');\" >Add Expense</div>"; } ?>
                          </div>
                        </div>
                        <div class='opcion'>
                            Projects
                            <div class="dropdown-content">
                                <div class='links' onclick="LoadPage('Projects.php');" >Projects</div>
                                <div class='links' onclick="LoadPage('Administrators/AddProject.php');">Add Project</div>
                            </div>
                        </div>
                        <div class='opcion'>
                            Assignments
                            <div class="dropdown-content">
                                <div class='links' onclick="LoadPage('Assignments.php');">Assignments</div>
                                <div class='links' onclick="LoadPage('Administrators/AddAssignment.php');">Add Assignment</div>
                            </div>
                        </div>
                        <?php
                            if($_SESSION['consultor']['Type'] == '0'){
                                ?>
                                <div class='opcion'>
                                    Contacts
                                    <div class="dropdown-content">
                                        <div class='links' onclick="LoadPage('Administrators/Contacts.php');" >Users</div>
                                        <div class='links' onclick="LoadPage('Administrators/EditUser.php');" >Update User</div>
                                    </div>
                                </div>
                                <div class='opcion' onclick="LoadPage('Administrators/CreateSchedule.php');" >
                                    Schedules
                                </div>
                                <div class='opcion'>
                                    POs
                                    <div class="dropdown-content">
                                        <div class='links' onclick="LoadPage('Administrators/POs.php');" >POs</div>
                                        <div class='links' onclick="LoadPage('Administrators/AddPO.php');"  >Add PO</div>
                                    </div>
                                </div>
                                <div class='opcion' onclick="LoadPage('Administrators/AddSponsor.php');" >Sponsors</div>
                                <div class='opcion' onclick="LoadPage('Administrators/Accounts.php');" >
                                    Accounts
                                </div>
                                  <?php
                              }
                          ?>
                    </div>

                </div-->
                <!--div id='barrita'>
                </div-->
                <div id="contenidoPrincipal">
                    <div id="load" onload="iframeLoaded();" scrolling='no' style="min-height: calc(100% - 175px) !important;">

                    </div>
                </div>
            </div>
            <div id="TopButton" onclick="ScrolltoTop();">
                &nbsp;<i class="fas fa-chevron-up"></i>
            </div>
        </body>
        <script src="../Resources/Javascript/PrincipalJS.js"></script>
    </html>
<?php
}else{
    header("Location: ../index.php");
}
?>
