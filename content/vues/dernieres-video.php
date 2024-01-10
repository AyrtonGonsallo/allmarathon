<?php 
$vd=new video();
$videos=$vd->getLastNVideos(4);
?>
<div class="container  homepage">
    <!--
        <h2 class="h2-aside">Les dernières Vidéos</h2>
    -->
    <div >
        <div class="row">
            <?php 
            foreach($videos['donnees'] as $video ){
                $image=str_replace("/default","/mqdefault",$video->getVignette());
                echo '<div class="col-md-3 col-lg-3 col-xs-12 col-sm-6" >
                    <div class="row m-h-300">
                        <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12" style="margin-top:20px;margin-bottom:10px">
                            <a style="cursor:pointer" aria-label="Voir la video: '.$video->getTitre().'" href="video-de-marathon-'.$video->getId().'.html">
                                <div style="background-image:url('.$image .');" class="dernieres-video-image" >
                                <img src="../images/yt-yellow.png" alt="" width=100 height=auto />
                                </div>'
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
            
            
                
            }
            ?>
        </div>
    </div>
</div>
