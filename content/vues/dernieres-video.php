<?php 
$vd=new video();
$videos=$vd->getLastNVideos(4);
?>
<h2 class="h2-aside">Les dernières Vidéos</h2>
<div >
    <div class="row">
        <?php $i=1;
        foreach($videos['donnees'] as $video ){
            $image=str_replace("/default","/mqdefault",$video->getVignette());
            echo '<div class="col-md-6 col-lg-6 col-xs-6 col-sm-12" >
                <div class="row">
                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="margin-top:20px;margin-bottom:10px">
                        <a style="cursor:pointer" aria-label="Voir la video: '.$video->getTitre().'" href="video-de-marathon-'.$video->getId().'.html">
                            <div style="width:auto;height:175px;background-position: center;background-image:url('.$image .');background-size:cover" ></div>'
                        .'</a>
                    </div>
                    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
                        <a class="titre-videos" aria-label="Voir la video: '.$video->getTitre().'" href="video-de-marathon-'.$video->getId().'.html">
                            <b>'.
                            $video->getTitre().
                            '</b>
                        </a>
                    </div>
                </div>
            </div>';
            if($i==2){
                echo '</div><div class="row premiere-ligne-videos">';
            }
            $i++;
            
        }
        ?>
    </div>
</div>
