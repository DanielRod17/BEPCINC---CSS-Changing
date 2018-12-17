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
function DisplayDetails($connection, $ID){
  $query =                $connection->prepare("SELECT p.*, project.Name as pName, assignment.ProjectID as aID
                                                FROM po p
                                                INNER JOIN assignment ON (assignment.PO = p.ID)
                                                INNER JOIN project ON (project.ID = assignment.ProjectID)
                                                WHERE p.Status='1'
                                                GROUP BY project.Name");
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
  if($row['Currency'] == '0')
      $currency =       "MXN";
  else
      $currency =       "USD";

  if($row['Status'] == '0')
      $status =         "Inactive";
  else if($row['Status'] == '1')
      $status =         "Active";
  else
      $status =         "Temporal";
  $content = "
  <div id='details' class='contOpc detOp'>
      <form id ='detailsForm'>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      PO Number
                  </div>
                  <div class='campoDato'>
                      ".$c['NoPO']."
                  </div>
              </div>
              <div class='right'>
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
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Ammount
                  </div>
                  <div class='campoDato'>
                      $".$c['Ammount']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Currency
                  </div>
                  <div class='campoDato'>
                      ".$currency."
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
                      ".$status."
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

function DisplayTimecards($connection, $ID){
  $totalCards =           0;
  $stmt =                 $connection->prepare("SELECT *
                                                FROM lineas
                                                WHERE AssignmentID IN (SELECT ID
                                                                      FROM assignment
                                                                      WHERE PO=?)");
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
?>
