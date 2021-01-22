window.onload = function(){
  if(page === 0){
    document.getElementById('loadMore').remove();
  }else{
    document.getElementById('loadMore').onclick = function(){
      loadTricks(page);
      page++;
    }
  }


  function loadTricks(page) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if(this.readyState === 4 && this.status === 200) {
        response = JSON.parse(this.responseText);
        document.getElementById('containsTrickRows').innerHTML += response.html;
        page = response.page;
        if(page === 4){
          document.getElementById('go-top').style.display = 'block';
        }
        if(response.page === 0){
          document.getElementById('loadMore').remove();
        }
      }
    };
    xhttp.open("GET", "loadmore/"+page, true);
    xhttp.send();
    return page;
  }
}
