<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
ini_set('max_execution_time', 0);
include('connection.php');
$CD =           date("d-m-Y");
$DBd =          date("Y-m-d");
$CD =           explode("-", $CD);
$d =            $CD[0];
$m =            $CD[1];
$y =            $CD[2];
$query =        $connection->query("SELECT ID FROM dof WHERE DATE(Day) = DATE('$DBd')");
if($query -> num_rows == 0){
    $url =          "https://dof.gob.mx/indicadores_detalle.php?cod_tipo_indicador=158&dfecha=$d%2F$m%2F$y&hfecha=$d%2F$m%2F$y";
    $ch =           curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $flag = 0;
    while($flag == 0){
        $result =   curl_exec($ch);
        preg_match_all('/<tr class=\"Celda 1\">(.*?)<\/tr>/s', $result, $matches);
        if(!empty($matches[1])){
            $opcs =             explode(" ", $matches[1][0]);
            $valor =            substr($opcs[14], 12, 5);
            echo $valor;
            $insertar =         $connection->query("INSERT INTO dof (ID, Day, Valor) VALUES (NULL, '$DBd', '$valor')");
            $flag = 1;
        }
    }
}else{
    echo "Already";
}