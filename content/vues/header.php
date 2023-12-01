<ul class="topnav" id="myTopnav">
  <li><a class="active" href="#home"><img src="../../img/logo-allmarathon.png" class="img-responsive" alt=""/></a></li>
  <li><a href="http://localhost/allmarathon_nv/www/content/vues/news.php">ACTUALITE</a></li>
  <li><a href="http://localhost/allmarathon_nv/www/content/vues/resultats.php">RÉSULTATS</a></li>
  <li><a href="http://localhost/allmarathon_nv/www/content/vues/calendrier.php">CALENDRIER</a></li>
  <li><a href="http://localhost/allmarathon_nv/www/content/vues/athlètes.php">athlèteS</a></li>
  <li><a href="#">VIDÉOS</a></li>
  <li><a href="#"><i class="fa fa-search" type="submit"></i></a></li>
  <li><div class="search_nav_bar"><input class="searchTerm" /></div></li>
  <li><a href="#" class="direct_event"><img src="../../img/direct/direct_envent.jpg"> DIRECT</a></li>
  <li><a href="#" class="shop"><span>Découvrez</span><br>allmarathonshop</a></li>
  <li><a href="#">SIGN IN</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="myFunction()">☰</a>
  </li>
</ul>
 
<script>
function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}
</script>
<style type="text/css">

ul.topnav {
  list-style-type: none;
  margin: 0;
  padding: 0;
  overflow: hidden;
  background-color: red;
}

ul.topnav li {float: left;}

ul.topnav li a, .search_nav_bar {
  display: inline-block;
  color: #f2f2f2;
  text-align: center;
  padding: 14px 16px;
  text-decoration: none;
  transition: 0.3s;
  font-size: 14px;
  padding-bottom: 0px;
  font-family: MuseoSans-500;
}
ul.topnav li .shop{
	line-height: 1;
	font-size: 12px;
}
ul.topnav li .shop span{
	color: #ffd621;
}
ul.topnav li .direct_event img{
	width: 28px;
}
ul.topnav li .direct_event {
	padding-top: 9px;
}
/*ul.topnav li a:hover {background-color: #555;}*/

ul.topnav li.icon {display: none;}
ul.topnav > li:first-child{
	background: white;
}
ul.topnav > li:first-child img{
	width: 130px;
}
.search_nav_bar {
    padding: 14px 0 !important;
    width: 300px;
} 
@media screen and (max-width:680px) {
  ul.topnav li:not(:first-child) {display: none;}
  ul.topnav li.icon {
    float: right;
    display: inline-block;
  }
}

@media screen and (max-width:680px) {
  ul.topnav.responsive {position: relative;}
  ul.topnav.responsive li.icon {
    position: absolute;
    right: 0;
    top: 0;
  }
  ul.topnav.responsive li {
    float: none;
    display: inline;
  }
  ul.topnav.responsive li a {
    display: block;
    text-align: left;
  }
}

.searchTerm {
    -moz-box-sizing: border-box;
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    width: 100%;
    border: none;
    padding: 5px;
    height: 30px;
    outline: none;
    background: #ff6666;
    margin-top: -4px;
}


</style>