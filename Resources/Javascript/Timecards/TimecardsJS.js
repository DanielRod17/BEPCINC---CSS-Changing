/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var row = "";
$(document).ready(function()
{
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
        source: "../Resources/WebResponses/Autocomplete/AutocompleteProjectUser.php",
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

function nuevoTimecard(){
    /*var w =         "AddTimecard.php";
    var frame = $('#load', window.parent.document);
    frame.fadeOut(500, function () {
        frame.attr('src', w);
        frame.fadeIn(500);
    });*/
    var frame = $("#load");
    var e = "Consultors/AddTimecard.php";
    //frame.load(e);
    frame.fadeOut(500, function () {
        //frame.attr('src', 'Warehouse/index.php');
        //goHome.attr('class', 'Warehouse/index.php');
        //frame.fadeIn(500);
        $("#load").load(e);
        frame.fadeIn(500);
    });
}

function actualizarTabla(e){
    var res =               e.value.split("/");
    var fecha =             res[2] + "-"+ res[0] + "-" + res[1];
    res[0]--;
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {fecha: fecha}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function() {
            var fechaInicial =      new Date(res[2], res[0], res[1]);
            var days = ['Sun', 'Sat', 'Fri', 'Thu', 'Wed', 'Tue', 'Mon'];
            //alert(fechaInicial.toDateString());
            for (var i = 0; i < 7 ; i++){
                var d2 = addDays(fechaInicial, i, '0');
                document.getElementById(days[i]).innerHTML = d2.toDateString().substring(0,10);
            }
        }
    });
}


function addDays(date, amount, type) {
    var tzOff = date.getTimezoneOffset() * 60 * 1000,
    t = date.getTime(),
    d = new Date(),
    tzOff2;

    if(type === '0')
        t -= (1000 * 60 * 60 * 24) * amount;
    else if(type === '1')
        t += (1000 * 60 * 60 * 24) * amount;
    d.setTime(t);

    tzOff2 = d.getTimezoneOffset() * 60 * 1000;
    if (tzOff != tzOff2) {
        if(type === '0'){
            var diff = tzOff2 - tzOff;
            t -= diff;
        }
        else if(type === '1'){
            var diff = tzOff2 + tzOff;
            t += diff;
        }
        d.setTime(t);
    }
    return d;
}

function DisplayEmployees(){
    document.getElementById("modalContent").style.display =   'none';
    document.getElementById("modalContent2").style.display =   'inline-block';
    var modales =                   document.getElementById("modal");
    modales.style.pointerEvents =   "auto";
    modales.style.display =         'inline-block';
    modales.className =             'w3-animate-show';
}

function DisplayProjects(e){
    row =                           e;
    document.getElementById("modalContent").style.display =   'inline-block';
    var modales =                   document.getElementById("modal");
    modales.style.pointerEvents =   "auto";
    modales.style.display =         'inline-block';
    modales.className =             'w3-animate-show';
}

function hideProjects(){
    document.getElementById("modalContent").style.display =   'none';
    var modales =                   document.getElementById("modal");
    modales.style.pointerEvents =   "none";
    modales.style.display =         'none';
    modales.className =             'w3-animate-hide';
}



function AssignName(e){
    //alert(e.innerHTML);
    var nombreInput = "project " + row;
    document.getElementsByClassName(nombreInput)[0].value = e.innerHTML;
    hideProjects();
}

function guardarTimecard(){
    var banderita =     0;
    var table =         document.getElementById('timeTable');
    var rowLength =     table.rows.length;
    var totalProjs =    new Array();
    var Names =         new Array();
    for(var i = 1; i < rowLength; i++){
        var row = table.rows[i];
        //your code goes here, looping over every row.
        //cells are accessed as easy
        //var cellLength = row.cells.length;
        if(row.cells[9].innerHTML === 'Saved'){
            banderita = 1;
        }
        var Name =  row.cells[0].children[1].value;
        var Mon =   row.cells[1].children[0].value;
        var Tue =   row.cells[2].children[0].value;
        var Wed =   row.cells[3].children[0].value;
        var Thu =   row.cells[4].children[0].value;
        var Fri =   row.cells[5].children[0].value;
        var Sat =   row.cells[6].children[0].value;
        var Sun =   row.cells[7].children[0].value;
        ;
        if(Name !== ""){
            var info = new Array(Name, Mon, Tue, Wed, Thu, Fri, Sat, Sun);
            totalProjs.push(info);
            Names.push(Name);
        }
    }
    //alert ("Nombres\n" + Names);
    //alert ("Projects\n" + totalProjs);
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {checkNames: '1', names: Names}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            if(data === "Alles gut"){
                $.ajax({ //PERFORM AN AJAX CALL
                    type:                   'post',
                    url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
                    data:                   {insertar: '1', lineas: totalProjs, delete: banderita}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
                    success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
                        alert(data);
                        DisplayError(data);
                        if(data === "Timecard Saved! Leaving the page will delete it"){
                            document.getElementById('approve').disabled =           false;
                            document.getElementById('guardar').disabled =           true;
                            for(var i = 1; i < rowLength; i++){
                                var row = table.rows[i];
                                if(row.cells[0].children[1].value !== ""){
                                    row.cells[9].innerHTML = 'Saved';
                                }
                            }
                            var r =     document.getElementById('deleteP');
                            if(r)
                                r.remove();
                            document.getElementById('boton').insertAdjacentHTML('afterend', "<span id='deleteP'>You have saved timecards, reload the page</span>");
                        }
                        /*window.parent.$("body").animate({scrollTop:0}, 'fast');
                        if(data == "User Added Successfully"){
                            //document.getElementById('newCustomer').reset();
                        }*/
                    }
                });
            }else{
                DisplayError(data);
            }
            /*window.parent.$("body").animate({scrollTop:0}, 'fast');
            if(data == "User Added Successfully"){
                //document.getElementById('newCustomer').reset();
            }*/
        }
    });
    return false;
}

function Reset(){
    var table =         document.getElementById('timeTable');
    var rowLength =     table.rows.length;
    for(var i = 1; i < rowLength; i++){
        var row = table.rows[i];
        //your code goes here, looping over every row.
        //cells are accessed as easy
        //var cellLength = row.cells.length;
        if(row.cells[9].innerHTML === 'Saved'){
            banderita = 1;
        }
        row.cells[0].children[1].value = "";
        row.cells[1].children[0].value = "";
        row.cells[2].children[0].value = "";
        row.cells[3].children[0].value = "";
        row.cells[4].children[0].value = "";
        row.cells[5].children[0].value = "";
        row.cells[6].children[0].value = "";
        row.cells[7].children[0].value = "";
        row.cells[9].innerHTML = '';
        row.cells[8].innerHTML = '';
    }
    document.getElementById('approve').disabled =           true;
    document.getElementById('guardar').disabled =           false;
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

function Approve(){
    //alert("Aprobada Lemao");
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {finishTimecard: '1'}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            DisplayError(data);
            alert(data);
            if(data === "Timecard Submitted!"){
                document.getElementById('approve').disabled =           true;
                LoadPage('Timecards.php');
            }
        }
    });
}

function weekChange(e){
    var primero =   "";
    var date =      document.getElementById('datepicker').value;
    var days =      ['Sun', 'Sat', 'Fri', 'Thu', 'Wed', 'Tue', 'Mon'];
    if(date !== ""){
        var res =               date.split("/");
        res[0]--;
        var fechaInicial =      new Date(res[2], res[0], res[1]);
        if(e === "0"){
            for (var i = 0; i < 7 ; i++){
                if(i === 6 && primero === ""){
                    primero = addDays(fechaInicial, 7, '0');
                }
            }
            var stringas = (primero.getMonth() + 1) + "/" + primero.getDate() + "/" + primero.getFullYear();
            document.getElementById('datepicker').value = stringas;
        }else if(e === "1"){
            for (var i = 7; i > 0 ; i--){
                var j = 7 - i;
                var d2 = addDays(fechaInicial, i, '1');
                if(primero === "")
                    primero = d2;
            }
            var stringas = (primero.getMonth() + 1) + "/" + primero.getDate() + "/" + primero.getFullYear();
            document.getElementById('datepicker').value = stringas;
        }
        actualizarTabla(document.getElementById('datepicker'));
    }
}

function editTimecard(e){
    var frame = $("#load");
    var e = "Administrators/AdminCards.php?id="+e;
    //frame.load(e);
    frame.fadeOut(500, function () {
        //frame.attr('src', 'Warehouse/index.php');
        //goHome.attr('class', 'Warehouse/index.php');
        //frame.fadeIn(500);
        $("#load").load(e);
        frame.fadeIn(500);
    });
}

function viewTimecard(e){
    alert("lalalalla");
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

function editTimecard(e){
    cargarTimecard(e);
}

function cargarTimecard(e){
    var table =         document.getElementById('timeTable');
    var rowLength =     table.rows.length;
    //alert(nambre + " " + fecha);
    //
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {cardSearch: e, consultor: '1'}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(d) {
            var info =        JSON.parse(d);
            var lineas =      info[0];
            var fecha =       info[1].substr(0, 10);
            var cadena =      fecha.split("-");
            var feca =        cadena[1] + "/" + cadena[2] + "/" + cadena[0];
            document.getElementById('datepicker').value = feca;
            actualizarTabla(document.getElementById('datepicker'));
            for(var i = 0; i < lineas.length; i++){
                    var row =   table.rows[i+1];
                //for(var j = 0; j <= lineas[i].length; j++){
                    row.cells[0].children[1].value = lineas[i]['Name'];
                    row.cells[1].children[0].value = lineas[i]['Mon'];
                    row.cells[2].children[0].value = lineas[i]['Tue'];
                    row.cells[3].children[0].value = lineas[i]['Wed'];
                    row.cells[4].children[0].value = lineas[i]['Thu'];
                    row.cells[5].children[0].value = lineas[i]['Fri'];
                    row.cells[6].children[0].value = lineas[i]['Sat'];
                    row.cells[7].children[0].value = lineas[i]['Sun'];
                //}
            }
        }
    });
}

function updateTimecard(){
    var banderita =     0;
    var table =         document.getElementById('timeTable');
    var rowLength =     table.rows.length;
    var totalProjs =    new Array();
    var Names =         new Array();
    for(var i = 1; i < rowLength; i++){
        var row = table.rows[i];
        //your code goes here, looping over every row.
        //cells are accessed as easy
        //var cellLength = row.cells.length;
        if(row.cells[9].innerHTML === 'Saved'){
            banderita = 1;
        }
        var Name =  row.cells[0].children[1].value;
        var Mon =   row.cells[1].children[0].value;
        var Tue =   row.cells[2].children[0].value;
        var Wed =   row.cells[3].children[0].value;
        var Thu =   row.cells[4].children[0].value;
        var Fri =   row.cells[5].children[0].value;
        var Sat =   row.cells[6].children[0].value;
        var Sun =   row.cells[7].children[0].value;
        ;
        if(Name !== ""){
            var info = new Array(Name, Mon, Tue, Wed, Thu, Fri, Sat, Sun);
            totalProjs.push(info);
            Names.push(Name);
        }
    }
    //alert ("Nombres\n" + Names);
    //alert ("Projects\n" + totalProjs);
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {checkNaems: '1', names: Names}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            if(data === "Alles gut"){
                $.ajax({ //PERFORM AN AJAX CALL
                    type:                   'post',
                    url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
                    data:                   {actualizar: '1', lineas: totalProjs, delete: banderita}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
                    success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
                        DisplayError(data);
                        alert(data);
                        var obj =   JSON.parse(data);
                        if(obj[0] === "Timecard Updated!"){
                            LoadPage('Timecard.php?id='+obj[1]);
                        }
                        /*window.parent.$("body").animate({scrollTop:0}, 'fast');
                        if(data == "User Added Successfully"){
                            //document.getElementById('newCustomer').reset();
                        }*/
                    }
                });
            }else{
                DisplayError(data);
            }
            /*window.parent.$("body").animate({scrollTop:0}, 'fast');
            if(data == "User Added Successfully"){
                //document.getElementById('newCustomer').reset();
            }*/
        }
    });
    return false;
}

function Aprobar(e){
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {aprobarCard: e}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) {
            if(data == "Timecard Submitted!"){
                alert(data);
                LoadPage('Timecard.php?id='+e);
            }else{
                alert("Something went wrong, try again");
            }
        }
    });
}