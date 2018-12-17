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
    
    $("#end, #start, #statusSearch, #assignmentStartSearch, #assignmentEndSearch").change(function(){
        searchCards();
    });
    
    $( ".searchFilters" ).keyup(function() {
        searchCards();
    });
    
    $(".searchFilters").focusout(function(){
        setTimeout(function() {
            var tcs =   document.getElementsByClassName('cardSearchOpt');
            for (var i = 0; i < tcs.length; i++) {
                tcs[i].style.display =  'none';
            }
        }, 500);
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
    a =                 e.parentNode.getElementsByClassName(opc);
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
    var Form =          document.getElementById('SearchTable');
    var section =       document.getElementsByTagName('h1');
    var url =           "";
    if(section[0].innerHTML.toLowerCase() === "timecards"){
        url =               "../Resources/WebResponses/TimecardsAJAX.php";
    }
    if(section[0].innerHTML.toLowerCase() === "contacts"){
        url =               "../Resources/WebResponses/AddUserAJAX.php";
    }
    if(section[0].innerHTML.toLowerCase() === "pos"){
        url =               "../Resources/WebResponses/PoAJAX.php";
    }
    if(section[0].innerHTML.toLowerCase() === "projects"){
        url =               "../Resources/WebResponses/ProjectAJAX.php";
    }
    if(section[0].innerHTML.toLowerCase() === "assignments"){
        url =               "../Resources/WebResponses/AssignmentAJAX.php";
    }
    if(section[0].innerHTML.toLowerCase() === "expenses"){
        url =               "../Resources/WebResponses/ExpensesAJAX.php";
    }
    var childs =        Form.elements;
    for(I = 0; I < childs.length - 1; I++) {
        var Value =       childs[I].value;
        info.push(Value);
    }
    var deleteClass =             document.getElementsByClassName('contacto');
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    url, //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {searchCards: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(e) {
            if(e === "No Results Found :("){
                DisplayError(e);
            }else{
                for(var j = 2; j < deleteClass.length; j = j++){
                    deleteClass[j].remove();
                }
                document.getElementById('tabla').insertAdjacentHTML('beforeend', e);
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