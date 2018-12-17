/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function()
{
    /*$( "#SnChange" ).autocomplete({
        source: "../Resources/WebResponses/Autocomplete.php",
        minLength: 0
    });*/
    $('#PO input').on( 'input', function() {
        var alertas = document.getElementById("alertas");
        setTimeout(() => {
            alertas.style.opacity = 0;
        }, 0);
    });
});


function RevisarInfo(){
    var info =          new Array();
    var Form =          document.getElementById('PO');
    var childs =        Form.elements;
    for(I = 0; I < childs.length - 1; I++) {
        var Value =       childs[I].value;
        info.push(Value);
    }
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/PoAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {newPO: '1', informacion: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            DisplayError(data);
            window.parent.$("body").animate({scrollTop:0}, 'fast');
            $("#tabla").load(" Administrators/POs.php #tabla");
            if(data === "PO Added"){
                document.getElementById('PO').reset();
                LoadPage('Administrators/POs.php');
                $(".sidebar-contact").removeClass("hide_menu");
            }
        }
    });
    return false;
}

function DisplayError(e){
    var alertas = document.getElementById("alertas");
    alertas.innerHTML = "";
    alertas.innerHTML = e;
    setTimeout(() => {
        alertas.style.opacity = 1;
    }, 0);
    
    setTimeout(() => {
        alertas.style.opacity = 0;
    }, 3000);
}

function EnableStates(e){
    if(e == "US"){
        document.getElementById('State').disabled = false;
    }else{
        document.getElementById('State').disabled = true;
    }
}

function Displayear(e){
    var displays =                  document.getElementsByClassName("cont");
    var warning =                   document.getElementById('advertenquia');
    var details =                   document.getElementById('details');
    if(e == 1){ //
        for(var i = 0; i < displays.length ; i++){
            displays[i].style.display =       'none';
            warning.style.display =           'none';
        }
        details.style.display =               'inline-block';
    }
    else{
      for(var i = 0; i < displays.length ; i++){
          displays[i].style.display =       'inline-block';
          warning.style.display =           'inline-block';
      }
      details.style.display =               'none';
    }
}
