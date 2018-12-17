/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function()
{
    $( "#SnChange" ).autocomplete({
        source: "../Resources/WebResponses/Autocomplete.php",
        minLength: 0
    });


    $('#newCustomer input').on( 'input', function() {
        var alertas = document.getElementById("alertas");
        setTimeout(() => {
            alertas.style.opacity = 0;
        }, 0);
    });
});

function GetUser() {
    var usuario =       document.getElementById('SnChange').value;
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/AddUserAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {usuario: usuario}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            var array = JSON.parse(data);
            if(array.SN !== "No User Found" ){
                for(var i = 1; i < document.getElementById('newCustomer').elements.length; i++){
                    document.getElementById('newCustomer').elements[i].disabled = false;
                }
                document.getElementById('SN').value =       array.SN;
                document.getElementById('First').value =    array.FirstName;
                document.getElementById('Last').value =     array.LastName;
                document.getElementById('Country').value =  array.Roster;
                document.getElementById('Email').value =    array.Email;
                document.getElementById('Phone').value =    array.Phone;
                if(array.Roster == "US"){
                    document.getElementById('State').value =    array.State;
                    document.getElementById('State').disabled = false;
                }
                document.getElementById('Type').value =     array.Type;
                var i = 1;
                $(".dayNum").each(function() {
                    $(this).val(array[i]);
                    i++;
                });
            }else{
                document.getElementById('newCustomer').reset();
                for(var i = 1; i < document.getElementById('newCustomer').elements.length; i++){
                    document.getElementById('newCustomer').elements[i].disabled = true;
                }
                DisplayError("User Not Found");
            }
        }
    });
}

function RevisarInfo(){
    var info =          new Array();
    var Form =          document.getElementById('AddContact');
    var childs =        Form.elements;
    for(I = 0; I < childs.length - 1; I++) {
        var Value =       childs[I].value;
        info.push(Value);
    }
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/AddUserAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {informacion: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            DisplayError(data);
            window.parent.$("body").animate({scrollTop:0}, 'fast');
            if(data == "User Added Successfully"){
                document.getElementById('AddContact').reset();
                LoadPage('Administrators/Contacts.php');
                $(".sidebar-contact").removeClass("hide_menu");
                
                //$("#tabla").load(" Administrators/Contacts.php #tabla");
            }
        }
    });
    return false;
}

function EnableStates(e){
    removeStates();
    removeCities();
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/AddUserAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {getStates: e}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            //DisplayError(data);
            var States =    JSON.parse(data);
            var Estados =   States[0];
            var Ciudades =  States[1];
            for(var i in Estados)
            {
                var id =            Estados[i].ID;
                var name =          Estados[i].Name;
                var option =        document.createElement("option");
                option.text =       name;
                option.value =      id;
                var select =        document.getElementById("State");
                select.appendChild(option);
            }
            ChangeCity(document.getElementById("State").value);
        }
    });
}

function ChangeCity(e){
    removeCities();
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/AddUserAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {getCities: e}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            //DisplayError(data);
            var Cities =    JSON.parse(data);
            if(Cities.length == 0){
                var option =        document.createElement("option");
                option.text =       "No Cities Registered";
                option.value =      0;
                var select =        document.getElementById("City");
                select.appendChild(option);
            }else{
                for(var i = 0; i <= Cities.length; i++){
                    var option =        document.createElement("option");
                    option.text =       Cities[i]['Name'];
                    option.value =      Cities[i]['ID'];
                    var select =        document.getElementById("City");
                    select.appendChild(option);
                }
            }
        }
    });
}

function removeStates(){
   var selectbox =      document.getElementById('State');
    var i;
    for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
    {
        selectbox.remove(i);
    }
}
function removeCities(){
   var selectbox =      document.getElementById('City');
    var i;
    for(i = selectbox.options.length - 1 ; i >= 0 ; i--)
    {
        selectbox.remove(i);
    }
}

function UpdateInfo(){
    var day;
    var sn =        document.getElementById('SN').value;
    var pass =      document.getElementById('Password').value;
    var cPass =     document.getElementById('CPassword').value;
    var first =     document.getElementById('First').value;
    var last =      document.getElementById('Last').value;
    var email =     document.getElementById('Email').value;
    var phone =     document.getElementById('Phone').value;
    var status =    document.getElementById('Status').options[document.getElementById('Status').selectedIndex].value;
    var country =   document.getElementById('Country').options[document.getElementById('Country').selectedIndex].value;
    var state =     document.getElementById('State').options[document.getElementById('State').selectedIndex].value;
    var type =      document.getElementById('Type').options[document.getElementById('Type').selectedIndex].value;
    var schedule =  document.getElementById('Schedule').options[document.getElementById('Schedule').selectedIndex].value;
    var sponsor =   document.getElementById('Sponsor').options[document.getElementById('Sponsor').selectedIndex].value;
    var assign =    document.getElementById('Assignment').options[document.getElementById('Assignment').selectedIndex].value;

    //alert (sn + " " + pass + " " + cPass + " " + first + " " + last + " " + country + " " + state + " " + type);
    var days =      new Array();
    if(sn.length >= 5){
        if(pass === cPass){
            var info = new Array(sn, pass, cPass, first, last, email, country, state, type, status, schedule, phone, sponsor, assign);
            $.ajax({ //PERFORM AN AJAX CALL
                type:                   'post',
                url:                    '../Resources/WebResponses/AddUserAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
                data:                   {updateInfo: '1', informacion: info}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
                success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
                    DisplayError(data);
                    window.parent.$("body").animate({scrollTop:0}, 'fast');
                    if(data == "User Updated Successfully"){
                        document.getElementById('newCustomer').reset();
                    }else if(data == "Passwords Must Be At Least 5 Characters Long"){
                        document.getElementById("Password").focus();
                        document.getElementById("Password").select();
                    }
                }
            });
        }else{
            document.getElementById("Password").focus();
            document.getElementById("Password").select();
            DisplayError("Passwords Must Match");
            window.parent.$("body").animate({scrollTop:0}, 'fast');
        }
    }else{
        //alert("Username Must Include At Least 5 characters");
        DisplayError("Username Must Include At Least 5 characters");
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
    
    setTimeout(() => {
        alertas.style.opacity = 0;
    }, 3000);
}
