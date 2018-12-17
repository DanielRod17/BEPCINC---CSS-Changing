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
    
    
    $('#Schedule input').on( 'input', function() {
        var alertas = document.getElementById("alertas");
        setTimeout(() => {
            alertas.style.opacity = 0;
        }, 0); 
    }); 
});


function RevisarInfo(){
    var day;
    var schedule =      document.getElementById('Nombre').value;
    var country =       document.getElementById('Country').options[document.getElementById('Country').selectedIndex].value;
    var state =         document.getElementById('State').options[document.getElementById('State').selectedIndex].value;
    var doubleAf =      document.getElementById('doubleAf').value;
    var tripleAf =      document.getElementById('tripleAf').value;
    var days =      new Array();
    var flag =      0;
    $(".dayNum").each(function() {
        //alert($(this).val());
        day =           $(this).val();
        if(day !== null && day !== '' && day !== 0 && day !== '0'){
            flag =          1;
        }
        days.push(day);
    });
    if(schedule.length >= 5){
        if(flag === 1){
            var info = new Array(schedule, country, state, days, doubleAf, tripleAf);
            $.ajax({ //PERFORM AN AJAX CALL
                type:                   'post', 
                url:                    '../Resources/WebResponses/SchedulesAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
                data:                   {newSchedule: '1', informacion: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
                success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
                    DisplayError(data);
                    window.parent.$("body").animate({scrollTop:0}, 'fast');
                    if(data === "Schedule Created Successfully"){
                        document.getElementById('Schedule').reset();
                        document.getElementById('State').disabled = true;
                    }
                }
            });
        }else{
            DisplayError("Set At Least One Day's Hour");
        }
    }else{
        DisplayError("Name Must Be At Least 5 Characters Long");
        window.parent.$("body").animate({scrollTop:0}, 'fast');
    }
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

function EnableStates(e){
    if(e == "US"){
        document.getElementById('State').disabled = false;
    }else{
        document.getElementById('State').disabled = true;
    }
}

