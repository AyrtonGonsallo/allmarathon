<?php

$cal_events=$evenement->getEventByTrimestre($tab,$type_event,$categorie_age)['donnees'];
?>

<?php
                    $tab_events_a_afficher = array();
                    $indice=0;
                    foreach ($cal_events as $ev) {
                        $ev_intitule=$event->getEventCatEventByID($ev->getCategorieId())['donnees']->getIntitule();
                        $ev_cat_age_intitule=$age->getEventCatAgeByID($ev->getCategorieageID())['donnees']->getIntitule();
                        $tab_indice=array('id'=>$ev->getId(),'sexe'=>$ev->getSexe(),'intitule'=>$ev_intitule,'cat_age'=>$ev_cat_age_intitule,'ville'=>$ev->getNom(),'date'=>$ev->getDateDebut(),'date_fin'=>$ev->getDateFin(),'type'=>$ev->getType(),'tel'=>$ev->getTelephone(),'contact'=>$ev->getContact(),'mail'=>$ev->getMail(),'web'=>$ev->getWeb(),'presentation'=>$ev->getPresentation(),'pack'=>$ev->getPack(),'document'=>$ev->getDocument3(),'compteur'=>$ev->getCompteur(),'insta'=>$ev->getInsta(),'facebook'=>$ev->getFacebook(),'youtube'=>$ev->getYoutube());
                        $tab_events_a_afficher=$evenement->search_array($tab_events_a_afficher,$tab_indice);
                    }

                    

                          foreach ($tab_events_a_afficher as $key => $ev) {
                            $timestamp_debut=strtotime($ev['date']);
                            //$timestamp_fin=strtotime($ev['date_fin']);
                            $lien_youtube=($ev['youtube'])?'<a href="'.$ev['youtube'].'" target="_blank"><i
                            class="fa fa-youtube"></i></a>':'';
                            $lien_facebook=($ev['facebook'])?'<a href="'.$ev['facebook'].'" target="_blank"><i
                            class="fa fa-facebook"></i></a>':'';
                            $lien_insta=($ev['insta'])?'<a href="'.$ev['insta'].'" target="_blank"><i
                            class="fa fa-instagram"></i></a>':'';
                             if($ev['pack']==1) {
                              $class_titre ="title-red";
                              $class_btn="btn-red"; 
                            }else {
                                $class_titre="title-grey";
                                $class_btn="btn-black";
                            }
                            ($ev['mail']!="" && $ev['mail']!=NULL ) ? $mail= '<li><a href="mailto:'.$ev['mail'].'" class="'.$class_btn.'"><i class="fa fa-envelope"></i> Email</a></li>' : $mail='';
                            ($ev['tel']!="" && $ev['tel']!=NULL ) ? $tel= '<li><i class="fa fa-phone"></i>  '.$ev['tel'].'</li>' : $tel='';
                            ($ev['contact']!="" && $ev['contact']!=NULL ) ? $contact= '<li><i class="fa fa-user"></i>  '.$ev['contact'].'</li>' : $contact='';
                            ($ev['web']!="" && $ev['web']!=NULL ) ? $web= '<li><a target="_blank" href="'.$ev['web'].'" class="'.$class_btn.'"><i class="fa fa-globe"></i> site web</a></li>' : $web='';
                            ($ev['presentation']!="" && $ev['presentation']!=NULL ) ? $presentation= '<div class="row">
                                                <div class="col-sm-11">
                                                    '.$ev['presentation'].'
                                                </div> </div><br>' : $presentation='';
                            $sexe_cal="";
                            if($ev['sexe']=="MF") $sexe_cal="<li>Masculin - Féminin</li>";
                            if($ev['sexe']=="F") $sexe_cal="<li>Féminin</li>";
                            if($ev['sexe']=="M") $sexe_cal="<li>Masculin</li>";
                            
                            ($ev['document']!='') ? $doc='<a href="/dossier_presentation-'.$ev['id'].'.html" target="_blank" class="'.$class_btn.'"><i class="fa fa-download"></i> Dossier de présentation ['.$ev['compteur'].' vues]</a>': $doc="";
                            echo '<dt class="'.$class_titre.'">
                            <h2 class="calendrier-events-title"> '.$ev['intitule'].' '.$ev['ville'].'</h2>
                            <div class="calendrier-events-medias">
                                     '.$lien_facebook.'
                                     '.$lien_insta.'
                                     '.$lien_youtube.'
                                     
                                </div>
                            </dt>
                                        <dd class="dd_calendrier">
                                            <ul class="event-infos list-inline">
                                                <li class="event-date"><span>'.date("M",$timestamp_debut ).'</span>'. date("d",$timestamp_debut ).'</li>
                                                <li class="event-date-mail">
                                                    <ul>
                                                        <li>Le '. date("d/m/Y",$timestamp_debut ).' </li>
                                                        '.$sexe_cal.'
                                                    </ul>
                                                </li>
                                                <li>
                                                    <ul>
                                                        '.$contact.'
                                                        '.$tel.'
                                                    </ul>
                                                </li>
                                                <li class="event-links">
                                                    <ul>
                                                       '.$mail.'
                                                       '.$web.'
                                                    </ul>
                                                </li>
                                            </ul>
                                            
                                            '.$presentation.'
                                            '.$doc.'
                                        
                                        </dd>
                            


            ';
            if($presentation!="" || $doc!="") echo '<hr>';
        
                  
    }
                          ?>

 <script type="text/javascript" >
    function sortResultEvent(){
        
        selected_age = document.getElementById('age').selectedIndex;
        age = document.getElementById('age')[selected_age ].value;

        selected_type = document.getElementById('type').selectedIndex;
        type = document.getElementById('type')[selected_type].value;

        condition='';
        if (type!=''){if(condition==''){condition+='?type='+type;}else {condition+='&type='+type;}} 
        if (age!=''){if(condition==''){condition+='?age='+age;}else {condition+='&age='+age;}} 
        condition= type+'-'+age;
        if(condition!=''  ){
            // alert("condition : "+condition);
        window.location.href = '/calendrier-marathon-'+condition+".html";  
        }
        else{
            // alert("rien");
        window.location.href = '/calendrier-marathon.html';
        }
    }

</script>       

   
