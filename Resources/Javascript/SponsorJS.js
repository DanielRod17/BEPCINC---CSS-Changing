/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function()
{
    $( "#MName" ).autocomplete({
        source: "../Resources/WebResponses/Autocomplete/AutocompleteManagers.php",
        minLength: 0
    });

    $('#Sponsor input').on( 'input', function() {
        var alertas = document.getElementById("alertas");
        setTimeout(() => {
            alertas.style.opacity = 0;
        }, 0);
    });
});


function RevisarInfo(){
    var info =          new Array();
    var Form =          document.getElementById('Sponsor');
    var childs =        Form.elements;
    for(I = 0; I < childs.length - 1; I++) {
        var Value =       childs[I].value;
        info.push(Value);
    }
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/SponsorAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {newSponsor: '1', informacion: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            DisplayError(data);
            window.parent.$("body").animate({scrollTop:0}, 'fast');
            if(data === "Sponsor Added"){
                document.getElementById('Sponsor').reset();
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
}
