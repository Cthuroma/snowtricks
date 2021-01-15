window.onload = function(){
  if(page === 0){
    document.getElementById('loadMore').remove();
  }else{
    document.getElementById('loadMore').onclick = function(){
      page = loadTricks(page);
    }
  }


  function loadTricks(page) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if(this.readyState === 4 && this.status === 200) {
        response = JSON.parse(this.responseText);
        document.getElementById('containsTrickRows').innerHTML += response.html;
        page = response.page;
        if(response.page === 0){
          document.getElementById('loadMore').remove();
        }
      }
    };
    xhttp.open("GET", "loadmore/"+page, true);
    xhttp.send();
    return 1;
  }
}
