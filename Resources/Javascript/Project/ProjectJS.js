/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function()
{
    $( "#Sponsor" ).autocomplete({
        source: "../Resources/WebResponses/Autocomplete/AutocompleteSponsor.php",
        minLength: 0
    });

    $('#Project input').on( 'input', function() {
        var alertas = document.getElementById("alertas");
        setTimeout(() => {
            alertas.style.opacity = 0;
        }, 0);
    });
});


function RevisarInfo(){
    var info =          new Array();
    var Form =          document.getElementById('Project');
    var childs =        Form.elements;
    for(I = 0; I < childs.length - 1; I++) {
        var Value =       childs[I].value;
        info.push(Value);
    }
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/ProjectAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {newProject: '1', informacion: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            DisplayError(data);
            window.parent.$("body").animate({scrollTop:0}, 'fast');
            if(data === "Project Added"){
                document.getElementById('Project').reset();
                $(".sidebar-contact").removeClass("hide_menu");
                LoadPage('Projects.php');
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
