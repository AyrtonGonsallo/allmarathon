<?php 
$vd=new video();
$videos=$vd->getLastNVideos(4);
?>
<div class="container  homepage">
    <!--
        <h2 class="h2-aside">Les dernières Vidéos</h2>
    -->
    <script src="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js
    "></script>
    <link href="
    https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css
    " rel="stylesheet">
    

    

    <section class="splide" aria-labelledby="carousel-heading">
        <div class="splide__track">
                <ul class="splide__list">
                <?php 
                foreach($videos['donnees'] as $video ){
                    $image=str_replace("/default","/mqdefault",$video->getVignette());
                    echo '<li class="splide__slide">
                        <div class="row">
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
                        </li>';
                    }
                ?>
                </ul>
        </div>
    </section>
    <script>
    new Splide( '.splide', {
        type   : 'loop',
        perPage: 2,
        breakpoints: {
            467: {
                perPage: 1,
        
            },
            768: {
                perPage: 2,
            
            }
            },
        }  ).mount();
    </script>




    
</div>
