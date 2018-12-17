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
//include('../Resources/WebResponses/connection.php');
function DisplayTimecards($connection, $ID){
  $totalCards =           0;
  $stmt =                 $connection->prepare("SELECT * FROM lineas WHERE AssignmentID=?");
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
      $totalCards++;
      foreach($rowa as $keya => $vala)
      {
          $d[$keya] = $vala;
      }
      $resultado[] = $d;
  }
  $stmt ->                close();

  echo "
    <div id='timecards' class='contOpc cont'>
        <div class='InfoAm'>
            $totalCards Timecards
        </div>
        <div class='Line' style='background-color: rgb(250, 250, 248)'>
            <div class='TCRDcolumna'>
                TIMECARD ID
            </div>
            <div class='TCRDcolumna'>
                START DATE
            </div>
            <div class='TCRDcolumna'>
                END DATE
            </div>
            <div class='more'>
            </div>
        </div>";
            if(!empty($resultado)){
                foreach($resultado as $fila){
                    echo "<div class='Line'>
                        <div class='TCRDcolumna'>
                            ".$fila['TimecardID']."
                        </div>
                        <div class='TCRDcolumna'>
                            ".substr($fila['StartingDay'], 0, 10)."
                        </div>
                        <div class='TCRDcolumna'>
                            ".substr($fila['CreatedDate'], 0, 10)."
                        </div>
                        <div class='more'>
                            &nbsp;<i class='far fa-caret-square-down'></i>
                        </div>
                    </div>";
                }
            }else{
                echo "
                    <div class='Line'>
                        No timecards found
                    </div>
                ";
            }
    echo "</div>";
}

function DisplayProjects($connection, $ID){
    $stmt =                 $connection->prepare("SELECT a.ManagerID, project.*, account_manager.Name as mName
                                                  FROM account a
                                                  INNER JOIN project ON (a.ManagerID = project.ManagerID)
                                                  INNER JOIN account_manager ON (project.ManagerID = account_manager.ID)
                                                  WHERE a.ID = ?");
    $stmt ->                bind_param('i', $I);
    $I =                    $ID;
    $stmt ->                execute();
    $meta =                 $stmt->result_metadata();
    $arregloProj  =         array();
    while ($field = $meta->fetch_field())
    {
        $paramasP[] = &$rowaP[$field->name];
    }
    call_user_func_array(array($stmt, 'bind_result'), $paramasP);
    while ($stmt->fetch()) {
        foreach($rowaP as $keyaP => $valaP)
        {
            $p[$keyaP] = $valaP;
            if($keyaP == 'ID'){
                array_push($arregloProj, $valaP);
            }
        }
        $resultadoP[] = $p;
    }
    $stmt ->                close();


    $content = "<div id='accounts' class='contOpc cont'>
        <div class='InfoAm'>
            Projects
        </div>
        <div class='Line' style='background-color: rgb(250, 250, 248)''>
            <div class='ACCcolumna'>
                Project Name
            </div>
            <div class='ACCcolumna'>
                Leader
            </div>
            <div class='ACCcolumna'>
                Manager
            </div>
            <div class='ACCcolumna'>
                Status
            </div>
            <div class='more'>
            </div>
        </div>";

            if(!empty($resultadoP)){
                foreach($resultadoP as $fila){
                  if($fila['Status'] == 0){
                      $status = "Inactive";
                  }else{
                      $status = "Active";
                  }
                  $content = $content."
                        <div class='Line'>
                            <div class='ACCcolumna'>
                                ".$fila['Name']."
                            </div>
                            <div class='ACCcolumna'>
                                ".$fila['PLeader']."
                            </div>
                            <div class='ACCcolumna'>
                                ".$fila['mName']."
                            </div>
                            <div class='ACCcolumna'>
                                ".$status."
                            </div>
                            <div class='more'>
                                &nbsp;<i class='far fa-caret-square-down'></i>
                            </div>
                        </div>
                    ";
                }
            }else{
                $content = $content."
                        <div class='Line'>
                            No Projects found
                        </div>
                ";
            }
    $content = $content."</div>";
    echo $content;
    //$respuesta = array($content, $arregloProj);
}

function DisplaySponsors($connection, $ID){
    $stmt =                 $connection->prepare("SELECT a.ManagerID, project.SponsorID, sponsor.*
                                                  FROM account a
                                                  INNER JOIN project ON (a.ManagerID = project.ManagerID)
                                                  INNER JOIN sponsor ON (project.SponsorID = sponsor.ID)
                                                  WHERE a.ID = ?
                                                  GROUP BY sponsor.Name");
    $stmt ->                bind_param('i', $I);
    $I =                    $ID;
    $stmt ->                execute();
    $meta =                 $stmt->result_metadata();
    $arregloProj  =         array();
    while ($field = $meta->fetch_field())
    {
        $paramasP[] = &$rowaP[$field->name];
    }
    call_user_func_array(array($stmt, 'bind_result'), $paramasP);
    while ($stmt->fetch()) {
        foreach($rowaP as $keyaP => $valaP)
        {
            $p[$keyaP] = $valaP;
            if($keyaP == 'ID'){
                array_push($arregloProj, $valaP);
            }
        }
        $resultadoP[] = $p;
    }
    $stmt ->                close();


    $content = "<div id='accounts' class='contOpc cont'>
        <div class='InfoAm'>
            Sponsors
        </div>
        <div class='Line' style='background-color: rgb(250, 250, 248)''>
            <div class='SponColumna'>
                Name
            </div>
            <div class='SponColumna'>
                Email
            </div>
            <div class='SponColumna'>
                Phone
            </div>
            <div class='more'>
            </div>
        </div>";

            if(!empty($resultadoP)){
                foreach($resultadoP as $fila){
                  $content = $content."
                        <div class='Line'>
                            <div class='SponColumna'>
                                ".$fila['Name']."
                            </div>
                            <div class='SponColumna'>
                                ".$fila['Email']."
                            </div>
                            <div class='SponColumna'>
                                ".$fila['Phone']."
                            </div>
                            <div class='more'>
                                &nbsp;<i class='far fa-caret-square-down'></i>
                            </div>
                        </div>
                    ";
                }
            }else{
                $content = $content."
                        <div class='Line'>
                            No Sponsors found
                        </div>
                ";
            }
    $content = $content."</div>";
    echo $content;
    //$respuesta = array($content, $arregloProj);
}

function DisplayDetails($connection, $ID){

  $query =                $connection->prepare("SELECT a.*, industries.Name as iName, divisions.Name as dName, account_manager.Name as aName
                                                FROM account a
                                                INNER JOIN industries ON (industries.ID = a.Industry)
                                                INNER JOIN divisions ON (a.Division = divisions.ID)
                                                INNER JOIN account_manager ON (account_manager.ID = a.ManagerID)
                                                WHERE a.ID=?");
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

  $content = "
  <div id='details' class='contOpc detOp'>
      <form id ='detailsForm'>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Account's Name
                  </div>
                  <div class='campoDato'>
                      ".$c['Name']."
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Industry
                  </div>
                  <div class='campoDato'>
                      ".$c['iName']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Division
                  </div>
                  <div class='campoDato'>
                      ".$c['dName']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Address
                  </div>
                  <div class='campoDato'>
                      ".$c['Address']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Company
                  </div>
                  <div class='campoDato'>
                      ".$c['Company']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Manager
                  </div>
                  <div class='campoDato'>
                      ".$c['aName']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
      </form>
  </div>";
  $respuesta = array($content, $c);
  return $respuesta;
}
?>
