/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var row = "";
$(document).ready(function()
{
    $( ".showPreview" ).click(function() {
        alert( "Handler for .click() called." );
    });
    
    $('#timeForms input').on( 'input', function() {
        var alertas = document.getElementById("alertas");
        setTimeout(() => {
            alertas.style.opacity = 0;
        }, 0);
    });
    ////////
    window.onclick = function(event) {
        var modal =     document.getElementById("modal");
        if (event.target === modal) {
            hideProjects();
        }
    }

    ////////
    $( ".project" ).autocomplete({
        source: "../Resources/WebResponses/AutocompleteProjectUser.php",
        minLength: 0
    });

    $('#Assignment input').on( 'input', function() {
        var alertas = document.getElementById("alertas");
        setTimeout(() => {
            alertas.style.opacity = 0;
        }, 0);
    });

    $( ".hourDay" ).change(function() {
        //alert( "jejillo" );
        var total =         0;
        var i =             0;
        $(this).closest('tr').find("input").each(function() {
            //alert(this.value);
            if(i > 0 && i < 9){
                total = +total + +this.value;
            }
            i++;
        });
        //alert(total);
        $(this).closest('tr').find('.sum')[0].innerHTML = total;
    });
});

function nuevoExpense(){
    var w =         "AddExpense.php";
    var frame = $('#load', window.parent.document);
    frame.fadeOut(500, function () {
        frame.attr('src', w);
        frame.fadeIn(500);
    });
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

function AssignExpense(){
    var formData =      new FormData();
    var Form =          document.getElementById('formExpenses');
    var childs =        Form.elements;
    for(I = 0; I < childs.length - 2; I++) {
        var Value =       childs[I].value;
        var Idi =         childs[I].id;
        formData.append(Idi, Value);
    }
    for (var i = 0, len = document.getElementById('Attachments').files.length; i < len; i++) {
            formData.append("file" + i, document.getElementById('Attachments').files[i]);
    }
    $.ajax({
        type:                 'post',
        cache:                false,
        contentType:          false,
        processData:          false,
        url:                  '../Resources/WebResponses/ExpensesAJAX.php',
        data:                 formData,
        success:              function(data){
            DisplayError(data);
            if(data === "Expense Added Successfully"){
                document.getElementById("formExpenses").reset();
            }
        }
    });
    return false;
}

function viewExpense(e){
    alert(e);
}

function agregarLinea(){
    var p =             document.getElementById("copy");
    //alert(p.innerHTML);
    var elemento =      "<div class=\"Linea\">"+p.innerHTML+"</div>";
    elemento =          elemento.replace('display: none;','');
    var div =           document.getElementById('formExpenses');
    div.insertAdjacentHTML('beforeend', elemento);
    
    var e =             document.getElementsByClassName('datepicker');
    //alert(e[0].value);
}

function deleteLinea(e){
    var padre =         e.parentNode.children[0];
    var parentDiv =     padre.parentNode.parentNode;
    var Lineas =        0;
    //alert(parentDiv.innerHTML);
    parentDiv.remove();
    
    //alert(parentDiv.innerHTML);
}

function getData(){
    var travel = document.getElementById('Travel').value;
    var counter =       0;
    var formData =      new FormData();
    formData.append('Travel', travel);
    var arrayTemp =     new Array();
    var arraySend =     new Array();
    var arreglo =       document.getElementsByClassName("entrada");
    for (var i = 1; i < arreglo.length; i++){
        var bill =          0;
        var ref =           0;
        //var boxR =          arreglo[i].getElementById("refundable");
        //var boxB =          arreglo[i].getElementById('billable');
        var lol = arreglo[i].querySelectorAll("input, select, checkbox, textarea");
        for (var j = 0; j < lol.length; j++){
            if(j < 5){
                formData.append('Travel'+i+"-"+j, lol[j].value);
            }else{
                formData.append('Travel'+i+"-"+j, lol[j].checked);
            }
        }
        //arraySend.push(arrayTemp);
    }
    
    for (var z = 0, len = document.getElementById('Attachments').files.length; z < len; z++) {
        formData.append("file" + z, document.getElementById('Attachments').files[z]);
    }
    $.ajax({
        type:                 'post',
        cache:                false,
        contentType:          false,
        processData:          false,
        url:                  '../Resources/WebResponses/ExpensesAJAX.php',
        data:                 formData,
        success:              function(data){
            DisplayError(data);
            if(data === "Expenses Saved"){
                LoadPage('Expenses.php');
            }
        }
    });
}

function RequestExpense(){
    var Form =          document.getElementById('formExpenses');
    var childs =        Form.elements;
    $.ajax({
        type:                 'post',
        url:                  '../Resources/WebResponses/ExpensesAJAX.php',
        data:                 { Request: '1', Destination: childs[0].value, Description: childs[1].value },
        success:              function(data){
            alert(data);
            DisplayError(data);
        }
    });
}

function showPreview(e){
    var element =               e.childNodes;
    var res =                   element[0].id.split(".");
    document.getElementById('imgPreview').src =      "../Resources/tinygif.gif";
    document.getElementById('docPreview').src =      "";
    document.getElementById('modal').style.display = "inline-block";
    if(res[res.length-1].toLowerCase() == "pdf"){
        document.getElementById('docPreview').src =      element[0].id;
    }else{
        document.getElementById('imgPreview').src =      element[0].id;
    }
}

function vanish(e){
    if(e.style.display == "inline-block"){
        e.style.display =       "none";
    }
}