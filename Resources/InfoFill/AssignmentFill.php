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
    $stmt =                 $connection->prepare("SELECT p.*, sponsor.Name as SName
                                                  FROM project p
                                                  INNER JOIN sponsor ON (sponsor.ID = p.SponsorID)
                                                  WHERE p.ID IN (SELECT ProjectID FROM assignment WHERE ConsultorID=?)");
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


    $content = "<div id='projects' class='contOpc cont'>
        <div class='InfoAm'>
            Projects
        </div>
        <div class='Line' style='background-color: rgb(250, 250, 248)''>
            <div class='PRJcolumna'>
                Name
            </div>
            <div class='PRJcolumna'>
                Sponsor
            </div>
            <div class='PRJcolumna'>
                Project Leader
            </div>
            <div class='more'>
            </div>
        </div>";

            if(!empty($resultadoP)){
                foreach($resultadoP as $fila){
                  $content = $content."
                        <div class='Line'>
                            <div class='PRJcolumna'>
                              ".$fila['Name']."
                            </div>
                            <div class='PRJcolumna'>
                                ".$fila['SName']."
                            </div>
                            <div class='PRJcolumna'>
                                ".$fila['PLeader']."
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
    $respuesta = array($content, $arregloProj);
    return $respuesta;
}

function DisplayAssignments($connection, $ID, $arregloProj){

      if(!empty($arregloProj)){
          $Assignments =  array();
          $ids =          join(",",$arregloProj);
          //$sql =          "SELECT * FROM assignment WHERE ProjectID IN ($ids)";
          $sql =          "SELECT a.*, po.NoPO, project.Name as PName
                          FROM assignment a
                          INNER JOIN po ON (a.PO = po.ID)
                          INNER JOIN project ON (a.ProjectID = project.ID)
                          WHERE a.ConsultorID = $ID";
          $queryAss =     $connection->query($sql);
          while($row = $queryAss->fetch_array()){
              array_push($Assignments, $row);
          }
          $resultadinho = $Assignments;
      }else{
          $resultadinho = array();
      }

      echo "<div id='assignment' class='contOpc cont'>
          <div class='InfoAm'>
              Assignments
          </div>
          <div class='Line' style='background-color: rgb(250, 250, 248)''>
              <div class='ASScolumna'>
                  Name
              </div>
              <div class='ASScolumna'>
                  BR
              </div>
              <div class='ASScolumna'>
                  PR
              </div>
              <div class='ASScolumna'>
                  Project
              </div>
              <div class='ASScolumna'>
                  PO
              </div>
              <div class='more'>
              </div>
          </div>";

              //var_dump($resultadinho);
              //var_dump($ids);
              if(!empty($resultadinho)){
                  foreach($resultadinho as $fila){
                      echo "
                          <div class='Line'>
                              <div class='ASScolumna'>
                                  ".$fila['Name']."
                              </div>
                              <div class='ASScolumna'>
                                  $".$fila['BR']."
                              </div>
                              <div class='ASScolumna'>
                                  $".$fila['PR']."
                              </div>
                              <div class='ASScolumna'>
                                  ".$fila['PName']."
                              </div>
                              <div class='ASScolumna'>
                                  ".$fila['NoPO']."
                              </div>
                              <div class='more'>
                                  &nbsp;<i class='far fa-caret-square-down'></i>
                              </div>
                          </div>";
                  }
              }else{
                  echo "
                          <div class='Line'>
                              No assignments found
                          </div>
                      ";
              }
        echo "</div>";
    }

function DisplayDetails($connection, $ID){

  $query =                $connection->prepare("SELECT a.*, project.Name as pName, consultors.Firstname, consultors.Lastname, po.NoPO
                                                FROM assignment a
                                                INNER JOIN project ON (project.ID = a.ProjectID)
                                                INNER JOIN consultors ON (consultors.ID = a.ConsultorID)
                                                INNER JOIN po ON (po.ID = a.PO)
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
                      Assignment's Name
                  </div>
                  <div class='campoDato'>
                      ".$c['Name']."
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Start Date
                  </div>
                  <div class='campoDato'>
                      ".substr($c['StartDate'], 0, 10)."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      End Date
                  </div>
                  <div class='campoDato'>
                      ".substr($c['EndDate'], 0, 10)."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      PO
                  </div>
                  <div class='campoDato'>
                      ".$c['NoPO']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Project
                  </div>
                  <div class='campoDato'>
                      ".$c['pName']."
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
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Status
                  </div>
                  <div class='campoDato'>
                      ".$c['Status']."
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
