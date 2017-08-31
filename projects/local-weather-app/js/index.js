  function getLocation(coord) {
    var lat = coord.split(",")[0];
    var long = coord.split(",")[1];

    return "https://api.openweathermap.org/data/2.5/weather?lat=" + lat + "&lon=" + long + "&appid=f522ef4f483c15db4c252c701fde11da&units=imperial";
  }

  //------------------------------------------------------------

  $.get("https://ipinfo.io", function(location) {
    var weatherURL = getLocation(location.loc);
    

    $.get(weatherURL, function(data) {
      if (data.main.temp < 45){
        $("body").css("background-image", 'url(' + 'http://eskipaper.com/images/nice-snow-wallpaper-3.jpg)'
      );}
      
      else if (data.main.temp > 44 && data.main.temp < 75){
        $("body").css("background-image", 'url(' + 'https://www.bhmpics.com/walls/nice_beach_scene-wide.jpg)'
      );}
      
      else if (data.main.temp > 74 && data.main.temp < 90){
        $("body").css("background-image", 'url(' + 'http://img.wallpaperfolder.com/f/4C3ECC3E87E8/sunny-day-pulse-back.jpg)'
      );}
      
      else {
        $("body").css("background-image", 'url(' + 'http://i.huffpost.com/gen/1080582/images/o-DC-WEATHER-HEAT-facebook.jpg)'
      );}
      
      
      
      var celsius = ((data.main.temp-32)* 5 / 9).toFixed(0);
      
      
      $("#data").html(location.city + ", " + location.country + "</br>") 
      $("#temp").html("<img src='https://openweathermap.org/img/w/" + data.weather[0].icon + ".png'>" +
                      Math.round(data.main.temp) + "\xB0 F</br>")
      $("#main").html(data.weather[0].main) 
      
      $(".unit").on("click", function() {
        if ($("#temp").is(":contains('\xB0 F')")) {
           $("#temp").html("<img src='http://openweathermap.org/img/w/" + data.weather[0].icon + ".png'>" +
                      celsius + "\xB0 C</br>");
        }
        else{
            $("#temp").html("<img src='http://openweathermap.org/img/w/" + data.weather[0].icon + ".png'>" +
                      Math.round(data.main.temp) + "\xB0 F</br>");
        }
      });

    }, 'json');

  }, 'json');