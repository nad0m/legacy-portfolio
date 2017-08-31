
function getDataAll() {
     
   var streamNames = ["ESL_SC2", "OgamingSC2", "brunofin", "freecodecamp", "comster404", "TSM_Doublelift", "AdmiralBulldog", "PRO_DOTA2_TV", "ESL_CSGO", "ESL_DOTA2", "Dianaclx", "Attackerdota", "2mgovercsquared", "Mrtweeday"];

  
   streamNames.forEach(function(name) {    
      
      $.getJSON("https://api.twitch.tv/kraken/streams/" + name, function(dataList) {
         
         console.log(dataList.stream);
        if (dataList.stream == null) {
            $(".info").append("<div class='entryOffline " +name+ "  row' container-fluid><div class='col-xs-2'><img class='logo' src='https://pbs.twimg.com/profile_images/509073338191183872/fYdty6yd.png'></div><a href='https://www.twitch.tv/"+name+"' class='link col-xs-2 ' target='_blank'>" +name+ "</a><h4 class='col-xs-6'>OFFLINE</h4></div>");
         } 
      else {
            $(".info").append("<div class='entry " +name+ "  row'><div class='col-xs-2'><img class='logo' src='" + dataList.stream.channel.logo + "'></div><a class='link col-xs-2' href='https://www.twitch.tv/"+name+"' target='_blank'>" +name+ "</a><p class='col-xs-3'>"+ dataList.stream.channel.status  +"</p><div class='col-xs-2'><img class='preview' src='" + dataList.stream.preview.large + "'></div></div>");
         }
         
         streamNames=destroyer(streamNames, name);//FCC basic algorithm
         
         if (streamNames.length < 3){
            streamNames.forEach(function(name) { 
      $(".info").append("<div class='entryOffline " +name+ " container row'><div class='col-xs-2'><img class='logo' src='https://pbs.twimg.com/profile_images/509073338191183872/fYdty6yd.png'></div><h3 class='link col-xs-2'>" +name+ "</h3><h4 class='col-xs-6'>ACCOUNT CLOSED</h4></div>");
      
      });
            
         }


      });

      
   });
   
   
   
}
//-----------------------------------------------------------
function destroyer(arr, a) {
  // Remove all the values  
  arr = arr.filter(function(val){   
    return val !== a;   
  });
  
  return arr;
}

$(document).ready(function() {
   

   getDataAll();

   $("#online").click(function() {
      $(".entryOffline").addClass("hide");
      $(".entry").removeClass("hide");
   });
   $("#offline").click(function() {
      $(".entry").addClass("hide");
      $(".entryOffline").removeClass("hide");
   });
   $("#all").click(function() {
      $(".entry").removeClass("hide");
      $(".entryOffline").removeClass("hide");

   });

});