/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


$(document).ready(function()
{
    /*var modal =                 document.getElementById('modal');
    var span =                  document.getElementById('close');
    span.onclick = function() {
        var hint = document.getElementById('modal');
        hint.className = hint.className !== 'show' ? 'show' : 'hide';
        if (hint.className === 'show') {
          setTimeout(function(){
            hint.style.display = 'block';
          },0); // timed to occur immediately
        }
        if (hint.className === 'hide') {
          setTimeout(function(){
            hint.style.display = 'none';
          },700); // timed to match animation-duration
        }
    };
    window.onclick = function(event) {
      if (event.target === modal) {
        var hint = document.getElementById('modal');
        hint.className = hint.className !== 'show' ? 'show' : 'hide';
        if (hint.className === 'show') {
          setTimeout(function(){
            hint.style.display = 'block';
          },0); // timed to occur immediately
        }
        if (hint.className === 'hide') {
          setTimeout(function(){
            hint.style.display = 'none';
          },150); // timed to match animation-duration
        }
      }
    };*/
});

function LoadPage(e){
    var frame = $("#load");
    //var goHome = $("#goHome");
    //frame.load(e);
    $("#load").empty();
    document.getElementById("load").innerHTML = "";
    frame.fadeOut(500, function () {
        //frame.attr('src', 'Warehouse/index.php');
        //goHome.attr('class', 'Warehouse/index.php');
        //frame.fadeIn(500);
        $("#load").load(e);
        frame.fadeIn(500);
    });
}

function Collapse(){
    var x =                   document.getElementById('collapse');
    var cajita =              document.getElementById('collapse').innerHTML;
    //if(cajita !== '<i class="fas fa-angle-down fa-lg"></i>')
    if(cajita !== '&nbsp;<i class="fas fa-angle-left fa-2x"></i>')
    {
        x.style.marginLeft =                                    "92%";
        x.innerHTML =                                           '&nbsp;<i class="fas fa-angle-left fa-2x"></i>';
        document.getElementById('contenido').style.width =      "82%";
        document.getElementById('menu').style.width =           "18%";
        document.getElementById('contenido').style.marginLeft = "18%";
        document.getElementById('profilepic').style.width =     "50px";
        document.getElementById('infoUsuario').style.display =  "block";
        document.getElementById('goHome').style.display =       "none";
        var x =                                                 document.getElementsByClassName("opcion");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "inline-block";
        }
    }else{
        document.getElementById('menu').style.width =           "0px";
        document.getElementById('contenido').style.width =      "100%";
        document.getElementById('contenido').style.marginLeft = "0%";
        document.getElementById('profilepic').style.width =     "0%";
        document.getElementById('infoUsuario').style.display =  "none";
        document.getElementById('goHome').style.display =       "block";
        x.style.marginLeft =                                    "0%";
        x.innerHTML =                                           '&nbsp;<i class="fas fa-angle-right fa-2x"></i>';
        var x =                                                 document.getElementsByClassName("opcion");
        for (i = 0; i < x.length; i++) {
            x[i].style.display = "none";
        }
    }
}

function Logout(){
    //alert("jej");
    var pdf = "../Logout.php";
    window.location.href = pdf;
}

function ScrolltoTop(){
    $('html,body').animate({ scrollTop: 0 }, 'slow');
    return false;
}
function DetectScroll(e){
    //if (!e){
        var scroll = $(window).scrollTop();
        if(scroll > 50){
            $("#TopButton").fadeIn("slow");
        }else{
            $("#TopButton").fadeOut("slow");
        }
    //}else{
      //  document.getElementById('TopButton').style.opacity =    "1";
    //}
}

function iframeLoaded(){
    var iFrameID = document.getElementById('load');
    if(iFrameID) {
          // here you can make the height, I delete it first, then I make it again
        iFrameID.height = "";
        var height = iFrameID.contentWindow.document.body.scrollHeight + 50;
        iFrameID.height = height + "px";
        //alert ("ME ESTAN CAMBIANDO JEJILLO "+iFrameID.contentWindow.document.body.scrollHeight);
    }
    iFrameID.style.opacity =      "1";
}
///////////////
var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
var eventer = window[eventMethod];
var messageEvent = eventMethod === "attachEvent" ? "onmessage" : "message";

// Listen to message from child window
window.addEventListener('message',function(e) {
    var key =     e.message ? 'message' : 'data';
    var data =    e[key];
    //alert(data + " " + e.origin);
    var res =     data.split(" ");
    if(res[0] === "ManageCustomers"){
        //document.getElementById('modal').style.display =    'block';
        var hint = document.getElementById('modal');
        hint.className = hint.className !== 'show' ? 'show' : 'hide';
        if (hint.className === 'show') {
          setTimeout(function(){
            hint.style.display = 'block';
          },0); // timed to occur immediately
        }
        if (hint.className === 'hide') {
          setTimeout(function(){
            hint.style.display = 'none';
          },700); // timed to match animation-duration
        }
    }
},false);

function goHome(e){
    var frame = $("#load");
    if(e.className !== "" && e.className !== null){
      frame.fadeOut(500, function () {
          frame.attr('src', e.className);
          frame.fadeIn(500);
      });
    }else{
        Collapse();
    }
}