/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


var popElement = document.getElementsByClassName("noteDay");
var popWeek =    document.getElementsByClassName("noteWeek");
document.addEventListener('click', function(event) {
    
    //alert(event.target.className);
    if (event.target.className != 'noteDay' && event.target.className != 'notaDesc' && event.target.className == 'noteWeek') {
        for(var i = 0; i < popElement.length; i++){
            popElement[i].style.display =   'none';
        }
    }
    if(event.target.className == 'noteDay' && event.target.className == 'notaDesc' && event.target.className != 'noteWeek'){
        for(var i = 0; i < popWeek.length; i++){
            popWeek[i].style.display =   'none';
        }
    }
    if(event.target.className != 'noteDay' && event.target.className != 'notaDesc' && event.target.className != 'noteWeek'){
        for(var i = 0; i < popElement.length; i++){
            popElement[i].style.display =   'none';
        }for(var i = 0; i < popWeek.length; i++){
            popWeek[i].style.display =   'none';
        }
    }
    
    if(event.target.className == 'far fa-caret-square-up'){
        var flechas = document.getElementsByClassName('sort-timecard');
        for(var i = 0; i < flechas.length; i++){
            flechas[i].id = '';
        }
        event.target.parentNode.id = 'changed';
        event.target.parentNode.innerHTML = '<i class="far fa-caret-square-down"></i>';
    }
    
    if(event.target.className == 'far fa-caret-square-down'){
        var flechas = document.getElementsByClassName('sort-timecard');
        for(var i = 0; i < flechas.length; i++){
            flechas[i].id = '';
        }
        event.target.parentNode.id = 'changed';
        event.target.parentNode.innerHTML = '<i class="far fa-caret-square-up"></i>';
    }
    
    
});



document.body.addEventListener( 'change', function ( event ) {
    if( event.srcElement.className == 'notaDesc' ) {
        AgregarNotaSemanal(event.srcElement);
    };
});

function AgregarNotaSemanal(){
    setTimeout(function(){
        for(j = 0; j < document.getElementsByClassName('noteWeek').length ; j++){
            for(p = 0; p < 7; p++){
                document.getElementsByClassName('noteWeek')[j].getElementsByClassName('diaSemana')[p].innerHTML = document.getElementsByClassName('updateDay')[p].innerHTML;
                document.getElementsByClassName('noteWeek')[j].getElementsByClassName('diaNote')[p].innerHTML =   document.getElementsByClassName('DaysInput')[j].getElementsByClassName('notaDesc')[p].value;  
            }
            //alert(document.getElementsByClassName('DaysInput')[j + 1].getElementsByClassName('notaDesc')[0].value);//getElementsByClassName('notaDesc')[p]);
        }
    }, 100);
}

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
        ActualizarTotales($(this));
    });
});

function ActualizarTotales(e){
    e = e || this;
    var dias =    new Array(0, 0, 0, 0, 0, 0, 0);
    //alert( "jejillo" );
    var total =         0;
    var i =             0;
    $(e).closest('tr').find("input").each(function() {
        //alert(this.value);
        if(i > 0 && i < 9){
            if(!isNaN(this.value)){
                total = +total + +this.value;
            }
        }
        i++;
    });
    $(e).closest('tr').find('.sum')[0].innerHTML = total;
    var table =         document.getElementById('timeTable');
    var rowLength =     table.rows.length;
    for(var i = 1; i < rowLength-1; i++){
        var row = table.rows[i];
        dias[0] =   + parseInt(dias[0]) + + row.cells[1].children[0].value;
        dias[1] =   + parseInt(dias[1]) + + row.cells[2].children[0].value;
        dias[2] =   + parseInt(dias[2]) + + row.cells[3].children[0].value;
        dias[3] =   + parseInt(dias[3]) + + row.cells[4].children[0].value;
        dias[4] =   + parseInt(dias[4]) + + row.cells[5].children[0].value;
        dias[5] =   + parseInt(dias[5]) + + row.cells[6].children[0].value;
        dias[6] =   + parseInt(dias[6]) + + row.cells[7].children[0].value;
    }
    var suma =      0;
    for(var j = 1; j < ((table.rows[rowLength-1].childNodes.length)/2) - 2; j++){
        table.rows[rowLength-1].childNodes[j*2].innerHTML = dias[j-1];
        suma += dias[j-1];
    }
    document.getElementById('totalSum').innerHTML = suma;
}

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

function PreviousCard(){
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {previous: '1'}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(e) {
            if(e !== "No Results Found :("){
                Reset();
                cargarCards(JSON.parse(e));
            }else{
                alert(e);
            }
            AgregarNotaSemanal();
        }
    });
}

function actualizarTabla(e){
    for(var i = 0; i < document.getElementsByClassName('statusCard').length; i++){
        document.getElementsByClassName('statusCard')[i].innerHTML = '';
    }
    if(e.value != ""){
        var res =               e.value.split("/");
        var fecha =             res[2] + "-"+ res[0] + "-" + res[1];
        res[0]--;
        $.ajax({ //PERFORM AN AJAX CALL
            type:                   'post',
            url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
            data:                   {fecha: fecha}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
            success: function(e) {
                if(e !== "No Results Found :("){
                    cargarCards(JSON.parse(e));
                }else{
                    var today =             new Date();
                    var hoy =               today.getTime();
                    var date =              document.getElementById('datepicker').value;
                    var ras =               date.split("/");
                    ras[0]--;
                    var fechaInicial =      new Date(ras[2], ras[0], ras[1]);
                    var checar =            fechaInicial.getTime();
                    var difference_ms =     hoy - checar;
                    var one_day =           1000*60*60*24;
                    var diff =              Math.round(difference_ms/one_day);
                    if(diff > 3 && document.getElementById('guardar')){
                        document.getElementById("guardar").remove();
                        if(document.getElementById('approve')){
                            document.getElementById("approve").remove();
                        }
                        document.getElementById("addLineas").remove();
                        document.getElementById('addMore').remove();
                        document.getElementById('previousCard').remove();
                    }
                    for(var p = document.getElementsByClassName('DaysInput').length; p > 6; p-- ){
                        document.getElementsByClassName('DaysInput')[p-2].remove();
                    }
                    $('#timeTable').find('input[type=number], input[type=text]').each(function(){
                        $(this).val($(this).attr("data-default"));
                    });

                    var table =         document.getElementById('timeTable');
                    var rowLength =     table.rows.length;
                    for(var i = 0; i < 5; i++){
                        var row =   table.rows[i+1];
                        ActualizarTotales(row.cells[3].children[0]);
                    } 
                }
                var fechaInicial =      new Date(res[2], res[0], res[1]);
                var mes =               res[0] + 1;
                var holiDays =          res[2] + "-" + mes + "-" + res[1];
                var days = ['Sun', 'Sat', 'Fri', 'Thu', 'Wed', 'Tue', 'Mon'];
                $.ajax({
                    type:       'post',
                    url:        '../Resources/WebResponses/TimecardsAJAX.php',
                    data:       {holidaysFecha: holiDays},
                    success : function (e){
                        var holiDayColl =   JSON.parse(e);
                        var table =         document.getElementById('timeTable');
                        var rowLength =     table.rows.length;
                        for(var j = 0; j < holiDayColl.length; j++){
                            for(var i = 0; i < rowLength ; i++){
                                var row = table.rows[i];
                                row.cells[holiDayColl[j]].style.backgroundColor = "rgb(191, 222, 239)";
                                if(i < rowLength - 1 && i > 0){
                                    row.cells[holiDayColl[j]].children[0].style.backgroundColor = "rgb(191, 222, 239)";
                                }
                            }
                        }
                    }
                });
                //alert(fechaInicial.toDateString());
                for (var i = 0; i < 7 ; i++){
                    var d2 = addDays(fechaInicial, i, '0');
                    document.getElementById(days[i]).innerHTML = d2.toDateString().substring(0,10);
                }
                AgregarNotaSemanal();
            }
        });
    }
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
    var banderita =     1;
    var table =         document.getElementById('timeTable');
    var rowLength =     table.rows.length;
    var totalProjs =    new Array();
    var Names =         new Array();
    for(var i = 1; i < rowLength - 1; i++){
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
        var MonN =  row.cells[1].children[1].children[0].value;
        var TueN =  row.cells[2].children[1].children[0].value;
        var WedN =  row.cells[3].children[1].children[0].value;
        var ThuN =  row.cells[4].children[1].children[0].value;
        var FriN =  row.cells[5].children[1].children[0].value;
        var SatN =  row.cells[6].children[1].children[0].value;
        var SunN =  row.cells[7].children[1].children[0].value;
        ;
        if(Name !== ""){
            var info = new Array(Name, Mon, Tue, Wed, Thu, Fri, Sat, Sun, MonN, TueN, WedN, ThuN, FriN, SatN, SunN);
            totalProjs.push(info);
            Names.push(Name);
        }
    }
    var fecha =         document.getElementById('datepicker').value;
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/TimecardsAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {checkNames: '1', names: Names, fechaCheck: fecha}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
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
                            for(var i = 1; i < rowLength - 1; i++){
                                var row = table.rows[i];
                                if(row.cells[0].children[1].value !== ""){
                                    row.cells[9].innerHTML = 'Saved';
                                }
                            }
                            var r =     document.getElementById('deleteP');
                            if(r)
                                r.remove();
                            document.getElementById('boton').insertAdjacentHTML('afterend', "<span id='deleteP'>You have saved timecards, reload the page</span>");
                        }///////////////////////*/
                    }
                });
            }else{
                DisplayError(data);
                alert(data);
            }
        }
    });
    return false;
}

function Reset(){
    var table =         document.getElementById('timeTable');
    var rowLength =     table.rows.length;
    for(var i = 1; i < rowLength - 1; i++){
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
    for(var i = 0; i < document.getElementsByClassName('notaDesc').length; i++){
        document.getElementsByClassName('notaDesc')[i].value = "";
    }
    if(document.getElementById('guardar') && document.getElementById('guardar').disabled == true){
        document.getElementById('approve').disabled =           true;
        document.getElementById('guardar').disabled =           false;
    }
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
        Reset();
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

function NotaDia(e){
    e.parentNode.childNodes[1].style.display = 'block';
    e.parentNode.childNodes[1].childNodes[1].focus();
    for(var i = 0; i < popWeek.length; i++){
        popWeek[i].style.display =   'none';
    }
}

function NotaSemana(e){
    for(var i = 0; i < popWeek.length; i++){
        popWeek[i].style.display =   'none';
    }
    for(var i = 0; i < popElement.length; i++){
        popElement[i].style.display =   'none';
    }
    setTimeout(function(){
        e.childNodes[1].style.display = 'block';
    }, 100);
}

function cargarCards(e){
    var table =         document.getElementById('timeTable');
    var Mon, Tue, Wed, Thu, Fri, Sat, Sun, flag = 0;
    //alert(nambre + " " + fecha);
    var info =        e;
    var lineas =      info;
    if(e.length > document.getElementsByClassName('DaysInput').length - 1){
        AgregarLineas(e.length - 5);
    }else if(e.length <= 5 && document.getElementsByClassName('DaysInput').length > 6){
        for(var p = document.getElementsByClassName('DaysInput').length; p > 6; p-- ){
            document.getElementsByClassName('DaysInput')[p-2].remove();
        }
    }
    for(var i = 0; i < lineas.length; i++){
        var row =   table.rows[i+1];
        if(lineas[i]['Mon'].split(".")[1] === "00"){  Mon = lineas[i]['Mon'].split(".")[0]; }else{ Mon = lineas[i]['Mon']; }
        if(lineas[i]['Tue'].split(".")[1] === "00"){  Tue = lineas[i]['Tue'].split(".")[0]; }else{ Tue = lineas[i]['Tue']; }
        if(lineas[i]['Wed'].split(".")[1] === "00"){  Wed = lineas[i]['Wed'].split(".")[0]; }else{ Wed = lineas[i]['Wed']; }
        if(lineas[i]['Thu'].split(".")[1] === "00"){  Thu = lineas[i]['Thu'].split(".")[0]; }else{ Thu = lineas[i]['Thu']; }
        if(lineas[i]['Fri'].split(".")[1] === "00"){  Fri = lineas[i]['Fri'].split(".")[0]; }else{ Fri = lineas[i]['Fri']; }
        if(lineas[i]['Sat'].split(".")[1] === "00"){  Sat = lineas[i]['Sat'].split(".")[0]; }else{ Sat = lineas[i]['Sat']; }
        if(lineas[i]['Sun'].split(".")[1] === "00"){  Sun = lineas[i]['Sun'].split(".")[0]; }else{ Sun = lineas[i]['Sun']; } 
        row.cells[0].children[1].value = lineas[i]['Name'];
        row.cells[1].children[0].value = Mon;
        row.cells[2].children[0].value = Tue;
        row.cells[3].children[0].value = Wed;
        row.cells[4].children[0].value = Thu;
        row.cells[5].children[0].value = Fri;
        row.cells[6].children[0].value = Sat;
        row.cells[7].children[0].value = Sun;
        ///////////////////
        row.cells[1].children[1].children[0].value = lineas[i]['MonNote'];
        row.cells[2].children[1].children[0].value = lineas[i]['TueNote'];
        row.cells[3].children[1].children[0].value = lineas[i]['WedNote'];
        row.cells[4].children[1].children[0].value = lineas[i]['ThuNote'];
        row.cells[5].children[1].children[0].value = lineas[i]['FriNote'];
        row.cells[6].children[1].children[0].value = lineas[i]['SatNote'];
        row.cells[7].children[1].children[0].value = lineas[i]['SunNote'];
        if(lineas[i]['Submitted'] == '1'){
            flag =              1;
        }
        //alert(row.cells[3].innerHTML);
        ActualizarTotales(row.cells[3].children[0]);
    }
    var today =             new Date();
    var hoy =               today.getTime();
    var day =               today.getDay();
    var date =              document.getElementById('datepicker').value;
    var ras =               date.split("/");
    ras[0]--;
    var fechaInicial =      new Date(ras[2], ras[0], ras[1]);
    var checar =            fechaInicial.getTime();
    var difference_ms =     hoy - checar;
    var one_day =           1000*60*60*24;
    var diff =              Math.round(difference_ms/one_day);
    if(flag == '0' && !document.getElementById('approve') && !document.getElementById('guardar') && diff <= 3 ){
        var botones = "<input style='float: left; height:  30px; width: 100px; margin-top: 0px; margin-left: 15px;' id='guardar' type='submit' form='timeForms' value='Save'>";
        
        if(day == 5 || day == 6 ||day == 0){
            botones = botones + "<input style='float: left; height:  30px; width: 100px; margin-top: 0px; margin-left: 15px;' type='submit' form='' onclick='Approve();' disabled id='approve' value='Submit'>"
            ;
        }
        document.getElementById('adelante').insertAdjacentHTML('afterend', botones);
        
        botones = "<select id='addLineas' style='float: left; width: 50px; margin-right: 10px; margin-top: 20px;'>" +
                    "<option value='1'>1</option>" +
                    "<option value='2'>2</option>" +
                    "<option value='3'>3</option>" +
                "</select>" +
                "<input type='submit' name='' value='Add another' id='addMore' onclick='AgregarLineas();'>";
        document.getElementById('cancel-boton').insertAdjacentHTML('beforebegin', botones);
        
        if(!document.getElementById('previousCard')){
            botones = "<input type='button' id='previousCard' value='Copy Previous' onclick='PreviousCard();'>";
            document.getElementById('line').insertAdjacentHTML('beforebegin', botones);
        }
    }else if(flag == '1' || diff > 3 && document.getElementById('guardar')){
        document.getElementById("guardar").remove();
        if(document.getElementById('approve')){
            document.getElementById("approve").remove();
        }
        document.getElementById("addLineas").remove();
        document.getElementById('addMore').remove();
        document.getElementById('previousCard').remove();
    }
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

function AgregarLineas(e){
    e = e || document.getElementById('addLineas').value;
    var cadena =        "";
    var claseIndex =    document.getElementsByClassName('DaysInput').length;
    var elements =      document.getElementsByClassName('DaysInput');
    for(var i = 0; i < e; i ++){
        cadena = cadena + "<tr class='DaysInput "+claseIndex+"'>" +
                "<td class='updateProj'>" +
                "<i class='icon fas fa-search' onclick=\"DisplayProjects('"+claseIndex+"');\" ></i>" +
                "<input type='text' placeholder='Select Assigment' class='project "+claseIndex+" ui-autocomplete-input' autocomplete='off'></td>" +
                "<td class='updateDay'><input type='number' ondblclick='NotaDia(this);' step='0.01' class ='hourDay' min='0' max='24'><div class='noteDay'>Monday Notes <textarea class='notaDesc'></textarea></div></td>" +
                "<td class='updateDay'><input type='number' ondblclick='NotaDia(this);' step='0.01' class ='hourDay' min='0' max='24'><div class='noteDay'>Tuesday Notes <textarea class='notaDesc'></textarea></div></td>" +
                "<td class='updateDay'><input type='number' ondblclick='NotaDia(this);' step='0.01' class ='hourDay' min='0' max='24'><div class='noteDay'>Wednesday Notes <textarea class='notaDesc'></textarea></div></td>" +
                "<td class='updateDay'><input type='number' ondblclick='NotaDia(this);' step='0.01' class ='hourDay' min='0' max='24'><div class='noteDay'>Thursday Notes <textarea class='notaDesc'></textarea></div></td>" +
                "<td class='updateDay'><input type='number' ondblclick='NotaDia(this);' step='0.01' class ='hourDay' min='0' max='24'><div class='noteDay'>Friday Notes <textarea class='notaDesc'></textarea></div></td>" +
                "<td class='updateDay' style='background-color: rgb(220, 220, 220);'><input type='number' ondblclick='NotaDia(this);' step='0.01' class ='hourDay' min='0' max='24' style='background-color: rgb(220, 220, 220);'><div class='noteDay'>Saturday Notes <textarea class='notaDesc'></textarea></div></td>" +
                "<td class='updateDay' style='background-color: rgb(220, 220, 220);'><input type='number' ondblclick='NotaDia(this);' step='0.01' class ='hourDay' min='0' max='24' style='background-color: rgb(220, 220, 220);'><div class='noteDay'>Sunday Notes <textarea class='notaDesc'></textarea></div></td>" +
                "<td class='sum'></td>" +
                "<td class='statusCard'></td>" +
                "<td class='weekNotes' onclick='NotaSemana(this);'><i class='far fa-file'></i><div class='noteWeek'>Week Notes" +
                "<div class='weekNoteDay'><div class='diaSemana'></div><div class='diaNote'></div></div>" +
                "<div class='weekNoteDay'><div class='diaSemana'></div><div class='diaNote'></div></div>" +
                "<div class='weekNoteDay'><div class='diaSemana'></div><div class='diaNote'></div></div>" +
                "<div class='weekNoteDay'><div class='diaSemana'></div><div class='diaNote'></div></div>" +
                "<div class='weekNoteDay'><div class='diaSemana'></div><div class='diaNote'></div></div>" +
                "<div class='weekNoteDay'><div class='diaSemana'></div><div class='diaNote'></div></div>" +
                "<div class='weekNoteDay'><div class='diaSemana'></div><div class='diaNote'></div></div>" +
                "</div></td>" +
            "</tr>";
        claseIndex++;
    }
    //alert(elements[elements.length - 1].innerHTML);
    elements[elements.length - 2].insertAdjacentHTML('afterend', cadena);
    
    RefreshSomeEventListener();
    AgregarNotaSemanal();
}

function RefreshSomeEventListener() {
    // Remove handler from existing elements
    $(".hourDay").off(); 

    // Re-add event handler for all matching elements
    $(".hourDay").on("change", function() {
       var dias =    new Array(0, 0, 0, 0, 0, 0, 0);
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
        $(this).closest('tr').find('.sum')[0].innerHTML = total;
        var table =         document.getElementById('timeTable');
        var rowLength =     table.rows.length;
        for(var i = 1; i < rowLength-1; i++){
            var row = table.rows[i];
            dias[0] =   + parseInt(dias[0]) + + row.cells[1].children[0].value;
            dias[1] =   + parseInt(dias[1]) + + row.cells[2].children[0].value;
            dias[2] =   + parseInt(dias[2]) + + row.cells[3].children[0].value;
            dias[3] =   + parseInt(dias[3]) + + row.cells[4].children[0].value;
            dias[4] =   + parseInt(dias[4]) + + row.cells[5].children[0].value;
            dias[5] =   + parseInt(dias[5]) + + row.cells[6].children[0].value;
            dias[6] =   + parseInt(dias[6]) + + row.cells[7].children[0].value;
        }
        var suma =      0;
        for(var j = 1; j < ((table.rows[rowLength-1].childNodes.length)/2) - 2; j++){
            table.rows[rowLength-1].childNodes[j*2].innerHTML = dias[j-1];
            suma += dias[j-1];
        }
        document.getElementById('totalSum').innerHTML = suma;
    });
}