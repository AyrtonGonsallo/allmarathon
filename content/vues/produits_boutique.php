<?php require '../classes/produits_aleatoires.php';
$copie = creer();
$random=affichage_aleatoire($copie);
?>
<!-- List group-->
<div class="disp-f">
    <div class="disp-f3">
        <!-- list group item-->
        <div class="list-prod show-on-mobile">
            <!-- Custom content-->
            <div class="media align-items-lg-center flex-column ">
                <a target="_blank" title="<?php echo $random[0]->getTitre()?>" href="<?php echo $random[0]->getHref()?>"
                    class="product-image image-loaded" tabindex="0">

                    <img src="<?php echo $random[0]->getImg()?>" alt="Generic placeholder image" width="200"
                        class="ml-lg-5 order-1 order-lg-2 img-size">
                    <div class="media-body order-2 ">
                        <h5 class="mt-0 font-weight-bold mb-2"><?php echo $random[0]->getTitre()?></h5>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <h6 class="font-weight-bold my-2"><?php echo $random[0]->getPrix()?></h6>
                        </div>
                    </div>
                </a>
            </div> <!-- End -->
        </div> <!-- End -->
        <!-- list group item-->
        <div class="list-prod hide-on-mobile">
            <!-- Custom content-->
            <div class="media align-items-lg-center flex-column ">
                <a target="_blank" title="<?php echo $random[1]->getTitre()?>" href="<?php echo $random[1]->getHref()?>"
                    class="product-image image-loaded" tabindex="0">

                    <img src="<?php echo $random[1]->getImg()?>" alt="Generic placeholder image" width="200"
                        class="ml-lg-5 order-1 order-lg-2 img-size">
                    <div class="media-body order-2 ">
                        <h5 class="mt-0 font-weight-bold mb-2"><?php echo $random[1]->getTitre()?></h5>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <h6 class="font-weight-bold my-2"><?php echo $random[1]->getPrix()?></h6>
                        </div>
                    </div>
                </a>
            </div> <!-- End -->
        </div> <!-- End -->
        <!-- list group item-->
        <div class="list-prod hide-on-mobile">
            <!-- Custom content-->
            <div class="media align-items-lg-center flex-column ">
                <a target="_blank" title="<?php echo $random[2]->getTitre()?>" href="<?php echo $random[2]->getHref()?>"
                    class="product-image image-loaded" tabindex="0">

                    <img src="<?php echo $random[2]->getImg()?>" alt="Generic placeholder image" width="200"
                        class="ml-lg-5 order-1 order-lg-2 img-size">
                    <div class="media-body order-2 ">
                        <h5 class="mt-0 font-weight-bold mb-2"><?php echo $random[2]->getTitre()?></h5>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <h6 class="font-weight-bold my-2"><?php echo $random[2]->getPrix()?></h6>
                        </div>
                    </div>
                </a>
            </div> <!-- End -->
        </div> <!-- End -->
    </div>
    <!-- list group item-->
    <div class="disp-f3 hide-on-mobile">
        <div class="list-prod">
            <!-- Custom content-->
            <div class="media align-items-lg-center flex-column ">
                <a target="_blank" title="<?php echo $random[3]->getTitre()?>" href="<?php echo $random[3]->getHref()?>"
                    class="product-image image-loaded" tabindex="0">

                    <img src="<?php echo $random[3]->getImg()?>" alt="Generic placeholder image" width="200"
                        class="ml-lg-5 order-1 order-lg-2 img-size">
                    <div class="media-body order-2 ">
                        <h5 class="mt-0 font-weight-bold mb-2"><?php echo $random[3]->getTitre()?></h5>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <h6 class="font-weight-bold my-2"><?php echo $random[3]->getPrix()?></h6>
                        </div>
                    </div>
                </a>
            </div> <!-- End -->
        </div> <!-- End -->
        <!-- list group item-->
        <div class="list-prod">
            <!-- Custom content-->
            <div class="media align-items-lg-center flex-column ">
                <a target="_blank" title="<?php echo $random[4]->getTitre()?>" href="<?php echo $random[4]->getHref()?>"
                    class="product-image image-loaded" tabindex="0">

                    <img src="<?php echo $random[4]->getImg()?>" alt="Generic placeholder image" width="200"
                        class="ml-lg-5 order-1 order-lg-2 img-size">
                    <div class="media-body order-2 ">
                        <h5 class="mt-0 font-weight-bold mb-2"><?php echo $random[4]->getTitre()?></h5>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <h6 class="font-weight-bold my-2"><?php echo $random[4]->getPrix()?></h6>
                        </div>
                    </div>
                </a>
            </div> <!-- End -->
        </div> <!-- End -->
        <!-- list group item-->
        <div class="list-prod">
            <!-- Custom content-->
            <div class="media align-items-lg-center flex-column ">
                <a target="_blank" title="<?php echo $random[5]->getTitre()?>" href="<?php echo $random[5]->getHref()?>"
                    class="product-image image-loaded" tabindex="0">

                    <img src="<?php echo $random[5]->getImg()?>" alt="Generic placeholder image" width="200"
                        class="ml-lg-5 order-1 order-lg-2 img-size">
                    <div class="media-body order-2 ">
                        <h5 class="mt-0 font-weight-bold mb-2"><?php echo $random[5]->getTitre()?></h5>
                        <div class="d-flex align-items-center justify-content-between mt-1">
                            <h6 class="font-weight-bold my-2"><?php echo $random[5]->getPrix()?></h6>
                        </div>
                    </div>
                </a>
            </div> <!-- End -->
        </div> <!-- End -->
    </div>
</div> <!-- End -->