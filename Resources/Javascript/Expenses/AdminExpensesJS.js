var row = "";
$(document).ready(function()
{
    $(".projName").click(function(){
        var Nombre =    $(this).attr('id');
        var w =         "Administrators/Project.php?id="+Nombre;
        //alert(Nombre + " " + w);
        var frame = $('#load', window.parent.document);
        frame.fadeOut(300, function () {
            frame.attr('src', w);
            frame.fadeIn(300);
        });
    });
    
});


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

function nuevoExpense(){
    var w =         "Administrators/AssignExpense.php";
    //alert(Nombre + " " + w);
    var frame = $('#load', window.parent.document);
    frame.fadeOut(300, function () {
        frame.attr('src', w);
        frame.fadeIn(300);
    });
}

function AssignExpense(){
    var info =              [];
    var forma =             document.getElementById('formExpenses');
    var inputs =            forma.getElementsByTagName('input');
    var selected =          document.getElementById('Assignment').value;
    for(var i = 0; i < inputs.length; i++){
        info.push(inputs[i].value);
    }
    info.push(selected);
    
    $.ajax({
        type:                 'post',
        url:                  '../Resources/WebResponses/ExpensesAJAX.php',
        data:                 {asignarExpense: info},
        success:              function(data){
            if(data === "Yes"){
                data =          "Expense Created";
                DisplayError(data);
                LoadPage('Expenses.php');
            }else{
                DisplayError(data);
            }
        }
    });
    
    return false;
}

function EnableFields(){
    var nombre =            document.getElementById('Consultor').value;
    var select =            document.getElementById("Assignment");
    $.ajax({ //PERFORM AN AJAX CALL
        type:                   'post',
        url:                    '../Resources/WebResponses/ExpensesAJAX.php', //PHP CONTAINING ALL THE FUNCTIONS
        data:                   {searchConsultor: nombre}, //SEND THE VALUE TO EXECUTE A QUERY WITH THE PALLET ID
        success: function(data) { //IF THE REQUEST ITS SUCCESSFUL
            var assignments =     JSON.parse(data);
            if(assignments.length > 0){
                removeOptions();
                document.getElementById('submittir').disabled =       false;
                document.getElementById('Start').disabled =     false;
                document.getElementById('End').disabled =       false;
                document.getElementById('Name').disabled =       false;
                for(var i = 0; i<assignments.length; i++){
                      var option =        document.createElement("option");
                      option.text =       assignments[i].Proj;
                      option.value =      assignments[i].ID;
                      select.appendChild(option);
                }
            }else{
                removeOptions();
                var option =        document.createElement("option");
                option.text =       "No Consultor Selected";
                option.value =      0;
                select.appendChild(option);
                DisableFields();
                DisplayError("No Assignments Found");
            }
            //$(this).parent().next(".contactoInfo").slideToggle(300);
        }
    });
}

function removeOptions(){
   var selectbox =      document.getElementById('Assignment');
    var i;
    for(i = selectbox.options.length - 1; i >= 0 ; i--)
    {
        selectbox.remove(i);
    }
}

function DisableFields(){
  var inputs = document.getElementsByClassName('disabled');
  var j =       0;
  while(j < inputs.length) {
      inputs[j].disabled = true;
      j++;
  }
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

function Download(e){
    var fileLoc =       e.parentNode.parentNode.childNodes[0].childNodes[0].id;
    let a =             document.createElement('a');
    a.href =            fileLoc;
    a.download =        "BeTrackingFile";
    a.click();
    a.remove();
}