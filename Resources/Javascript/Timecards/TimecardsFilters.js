/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function(){
    
    $( ".cardSearchOpt" ).on('click', function() {
        var clases =            this.parentNode.childNodes[3].className.split(" ");
        this.parentNode.childNodes[1].value =   this.innerHTML;
        var tcs =   document.getElementsByClassName(clases[1]);
        for (var i = 0; i < tcs.length; i++) {
            tcs[i].style.display =  'none';
        }
        searchCards();
    });
    
    $("#end, #start, #statusSearch").change(function(){
        searchCards();
    });
    
    $( ".searchFilters" ).keyup(function() {
        searchCards();
    });
    
    $(".searchFilters").focusout(function(event){
        event.stopPropagation();
        var tcs =   document.getElementsByClassName('cardSearchOpt');
        for (var i = 0; i < tcs.length; i++) {
            tcs[i].style.display =  'none';
        }
    });
    /*
     * 
    
     */
});

function filter(e){
    var opcs =          e.parentNode.childNodes[3].className.split(" ");;
    var opc =           opcs[1];
    var input, filter, ul, li, a, i, div;
    input =             document.getElementById(e.id);
    filter =            input.value.toUpperCase();
    a =                 document.getElementsByClassName(opc);
    if(input.value !== ""){
        for (i = 0; i < a.length; i++) {
            if (a[i].innerHTML.toUpperCase().indexOf(filter) > -1) {
              a[i].style.display = "inline-block";
            } else {
              a[i].style.display = "none";
            }
        }
    }
}

function searchCards(){
    var info =          new Array();
    var Form =          document.getElementById('CardSearch');
    var childs =        Form.elements;
    for(I = 0; I < childs.length - 1; I++) {
        var Value =       childs[I].value;
        info.push(Value);
    }
    alert(info);
    /*var deleteClass =             document.getElementsByClassName('contacto');
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {searchCards: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(e) {
            var cadena = "";
            var objetos =   JSON.parse(e);
            if(objetos[0].constructor === Array){
                for(var j = 2; j < deleteClass.length; j = j++){
                    deleteClass[j].remove();
                }
                for (var o = 0; o < objetos.length; o++){
                    cadena += "<div class='contacto'>" +
                                        "<div class='timeCard' style='cursor: pointer;' onclick=\"LoadPage('Timecard.php?id=" +objetos[o][0]+"');\">" + objetos[o][1] +"</div>" +
                                        "<div class='resource'>" +objetos[o][2]+"</div>" +
                                        "<div class='tProj'>" + objetos[o][3] +"</div>"+
                                        "<div class='startD'>" +objetos[o][4]+"</div>" +
                                        "<div class='endD'>"+objetos[o][5]+"</div>" +
                                        "<div class='status'>"+objetos[o][6]+"</div>" +
                                        "<div class='totalDays'>"+objetos[o][7]+"</div>" +
                                        "<div class='totalHours'>"+objetos[o][8]+"</div>" +
                                    "</div>" ;
                }
                document.getElementById('tabla').insertAdjacentHTML('beforeend', cadena);
            }else{
                alert("No results found :(");
            }
        }
    });*/
    return false;
}