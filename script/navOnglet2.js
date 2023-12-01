$(document).ready(function(){


    $(".tabContent").each(function(i){
         this.id = "#" + this.id;
    });

    //$(".tabContent:not(:first)").hide();
	$(".tabContent").not(":first").hide();

	$("#listSites a:first").addClass("current");

    $("#listSites a").click(function() {
         var idTab = $(this).attr("href");
         $(".tabContent").hide();
		 $("#listSites a").removeClass();
         $("div[id='" + idTab + "']").show();
		 $(this).addClass("current");
         return false;
    });

});