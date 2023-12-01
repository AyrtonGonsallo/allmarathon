<?php
include("/../classes/commentaire.php");
include("/../classes/user.php");
$user=new user();
$commentaire=new commentaire();
$coms=$commentaire->getCommentairesChampion(722)['donnees'];
?>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

<?php foreach ($coms as $key => $value) {
                            $user_name=$user->getUserById($value->getUser_id())['donnees']->getUsername();
                            $d_m=date("d/m", strtotime($value->getDate()));
                            $h_s=date("h:s", strtotime($value->getDate()));
                            echo '<li class="line-content">
                            <span class="meta"><strong>'.$user_name.'</strong> - le '.$d_m.' à '.$h_s.'</span>
                            <p>'.$value->getCommentaire().'</p>

                        </li>';
                        } ?>


<ul id="pagin">

</ul>

<script type="text/javascript">
pageSize = 5;

$(function() {
  var pageCount = Math.ceil($(".line-content").size() / pageSize);

  for (var i = 0; i < pageCount; i++) {
    if (i == 0)
      $("#pagin").append('<li><a class="current" href="#">' + (i + 1) + '</a></li>');
    else
      $("#pagin").append('<li><a href="#">' + (i + 1) + '</a></li>');
  }


  showPage(1);

  $("#pagin li a").click(function() {
    $("#pagin li a").removeClass("current");
    $(this).addClass("current");
    showPage(parseInt($(this).text()))
  });

})

showPage = function(page) {
  $(".line-content").hide();

  $(".line-content").each(function(n) {
    if (n >= pageSize * (page - 1) && n < pageSize * page)
      $(this).show();
  });
}</script>

<style type="text/css">
.current {
  color: green;
}

#pagin li {
  display: inline-block;
}</style>
