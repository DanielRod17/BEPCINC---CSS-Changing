$(document).ready(function(){
	$("#boton").click(function(){
		var clase = $(".sidebar-contact").attr("class");
		if (clase == "sidebar-contact"){
		$(".sidebar-contact").addClass("hide_menu");
		$(".modal").css("display", "inline-block");
                $(".opcContenido").css("display", "none");
	}

		else{ 
                    $(".sidebar-contact").removeClass("hide_menu");
                    $(".opcContenido").css("display", "inline-block");
                }
            });
        
        $("#cancel-boton").click(function(){
            $(".sidebar-contact").removeClass("hide_menu");
            $(".opcContenido").css("display", "inline-block");
        });
});