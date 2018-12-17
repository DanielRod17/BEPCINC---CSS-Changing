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
function DisplayBudgets($connection, $ID){
  $stmt =                 $connection->prepare("SELECT a.*, po.*
                                                FROM assignment a
                                                INNER JOIN po ON(a.PO = po.ID)
                                                WHERE ProjectID = ?");
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
    <div id='timecards' class='contOpc cont'>
        <div class='InfoAm'>
            BUDGET
        </div>
        <div class='Line' style='background-color: rgb(250, 250, 248)'>
            <div class='BUDcolumna'>
                ASSIGNMENT
            </div>
            <div class='BUDcolumna'>
                BR
            </div>
            <div class='BUDcolumna'>
                PR
            </div>
            <div class='BUDcolumna'>
                PO
            </div>
            <div class='BUDcolumna'>
                AMMOUNT
            </div>
            <div class='more'>
            </div>
        </div>";
            if(!empty($resultado)){
                foreach($resultado as $fila){
                    echo "<div class='Line'>
                        <div class='BUDcolumna'>
                            ".$fila['Name']."
                        </div>
                        <div class='BUDcolumna'>
                            $".$fila['BR']."
                        </div>
                        <div class='BUDcolumna'>
                            $".$fila['PR']."
                        </div>
                        <div class='BUDcolumna'>
                            ".$fila['NoPO']."
                        </div>
                        <div class='BUDcolumna'>
                            $".$fila['Ammount']."
                        </div>
                        <div class='more'>
                            &nbsp;<i class='far fa-caret-square-down'></i>
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

function DisplayHistory($connection, $ID){
    $stmt =                 $connection->prepare("SELECT l.*, assignment.Name as aName, consultors.Firstname, consultors.Lastname
                                                  FROM lineas l
                                                  INNER JOIN assignment ON (l.AssignmentID = assignment.ID)
                                                  INNER JOIN consultors ON (l.ConsultorID = consultors.ID)
                                                  WHERE l.ID=?");
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


    $content = "<div id='history' class='contOpc cont'>
        <div class='InfoAm'>
            Approval History
        </div>
        <div class='Line' style='background-color: rgb(250, 250, 248)''>
            <div class='TCRcolumna'>
                Name
            </div>
            <div class='TCRcolumna'>
                Date
            </div>
            <div class='TCRcolumna'>
                Status
            </div>
            <div class='more'>
            </div>
        </div>";

            if(!empty($resultadoP)){
                foreach($resultadoP as $fila){
                  $content = $content."
                        <div class='Line'>
                            <div class='TCRcolumna'>
                              ".$fila['Firstname']." ".$fila['Lastname']."
                            </div>
                            <div class='TCRcolumna'>
                              ".substr($fila['CreatedDate'], 0, 10)."
                            </div>
                            <div class='TCRcolumna'>
                              Submitted
                            </div>
                            <div class='more'>
                                &nbsp;<i class='far fa-caret-square-down'></i>
                            </div>
                        </div>
                    ";
                    if($fila['LEditDate'] != "0000-00-00 00:00:00"){
                        $content = $content."
                              <div class='Line'>
                                  <div class='TCRcolumna'>
                                    ".$fila['Firstname']." ".$fila['Lastname']."
                                  </div>
                                  <div class='TCRcolumna'>
                                    ".substr($fila['LEditDate'], 0, 10)."
                                  </div>
                                  <div class='TCRcolumna'>
                                    Last Edit
                                  </div>
                                  <div class='more'>
                                      &nbsp;<i class='far fa-caret-square-down'></i>
                                  </div>
                              </div>
                          ";
                    }
                    if($fila['RejectedDate'] != "0000-00-00 00:00:00"){
                        $content = $content."
                              <div class='Line'>
                                  <div class='TCRcolumna'>
                                    ".$fila['Firstname']." ".$fila['Lastname']."
                                  </div>
                                  <div class='TCRcolumna'>
                                    ".substr($fila['RejectedDate'], 0, 10)."
                                  </div>
                                  <div class='TCRcolumna'>
                                    Rejected
                                  </div>
                                  <div class='more'>
                                      &nbsp;<i class='far fa-caret-square-down'></i>
                                  </div>
                              </div>
                          ";
                    }
                    if($fila['ApprovedDate'] != "0000-00-00 00:00:00"){
                        $content = $content."
                              <div class='Line'>
                                  <div class='TCRcolumna'>
                                    ".$fila['Firstname']." ".$fila['Lastname']."
                                  </div>
                                  <div class='TCRcolumna'>
                                    ".substr($fila['ApprovedDate'], 0, 10)."
                                  </div>
                                  <div class='TCRcolumna'>
                                    Approved
                                  </div>
                                  <div class='more'>
                                      &nbsp;<i class='far fa-caret-square-down'></i>
                                  </div>
                              </div>
                          ";
                    }
                }
            }else{
                $content = $content."
                    <div id='timecardsLine'>
                        <div class='Line'>
                            No projects found
                        </div>
                    </div>
                ";
            }
    $content = $content."</div>";
    echo $content;
    //$respuesta = array($content, $arregloProj);
    //return $respuesta;
}


function DisplayDetails($connection, $ID){

  $query =                $connection->prepare("SELECT t.*, consultors.Firstname, consultors.Lastname, consultors.Title, consultors.Email, assignment.Name as aName
                                                FROM lineas t
                                                INNER JOIN consultors ON (consultors.ID = t.ConsultorID)
                                                INNER JOIN assignment ON (assignment.ID = t.AssignmentID)
                                                WHERE t.ID=?");
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
  $days =                 0;
  $hours =                0;
  $diasArray =            array('Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun');
  foreach($diasArray as $tag){
      $hours += $c[$tag];
      if(intval($c[$tag]) !== 0){
          $days++;
      }
  }
  $content = "
  <div id='details'  class='contOpc detOp'>
      <form id ='projDetails'>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Timecard ID
                  </div>
                  <div class='campoDato'>
                      ".$c['TimecardID']."
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Assignment
                  </div>
                  <div class='campoDato'>
                      ".$c['aName']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
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
              <div class='right'>
                  <div class='nombreDato'>
                      Days Worked
                  </div>
                  <div class='campoDato'>
                      ".$days."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Monday Hours
                  </div>
                  <div class='campoDato'>
                      ".$c['Mon']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Tuesday Hours
                  </div>
                  <div class='campoDato'>
                      ".$c['Tue']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Wednesday Hours
                  </div>
                  <div class='campoDato'>
                      ".$c['Wed']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Thursday Hours
                  </div>
                  <div class='campoDato'>
                      ".$c['Thu']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Friday Hours
                  </div>
                  <div class='campoDato'>
                      ".$c['Fri']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
              <div class='right'>
                  <div class='nombreDato'>
                      Saturday Hours
                  </div>
                  <div class='campoDato'>
                      ".$c['Sat']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
                  </div>
              </div>
          </div>
          <div class='datos'>
              <div class='left'>
                  <div class='nombreDato'>
                      Sunday Hours
                  </div>
                  <div class='campoDato'>
                      ".$c['Sun']."
                      <div class='lapiz'>
                          <i class='fas fa-pencil-alt'></i>
                      </div>
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
