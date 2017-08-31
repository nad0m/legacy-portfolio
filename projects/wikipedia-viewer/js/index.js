$(".enter").keydown(function(event) {
  if (event.keyCode == 13) {
    $("#buttonID").click();
  }
});

function callWiki() {
  var text = $("#getText").val();
  var wiki = "https://en.wikipedia.org/w/api.php?format=json&action=query&generator=search&gsrnamespace=0&gsrlimit=10&prop=pageimages|extracts&pilimit=max&exintro&explaintext&exsentences=1&exlimit=max&gsrsearch=";

  $(".page").html("<h3 class='results'></h3><div class='info container'></div></br></br>");

  $(".results").html("Showing results for <i>" + text + ":</i>");

  $.get(wiki + text, function(search) {

    var results = search.query.pages;

    $.each(results, function(key, obj) {
      $(".info").append("<a href='https://en.wikipedia.org/?curid=" + obj.pageid + "' target='_blank'><div class='entry container " + key + " col-xs-2'> <h3>" + obj.title + "</h3></div></a>");

      if (obj.hasOwnProperty("thumbnail")) {
        var imgURL = obj.thumbnail.source.split("px-")[0] + "0px-" +
          obj.thumbnail.source.split("px-")[1];
        $("." + key).append("<img src='" + imgURL + "'>");

      }

      $("." + key).append("<p>" + obj.extract + "</p>");

      console.log(obj);

      //</div>

    })
  }, 'jsonp');
}