<!-- Begin Brevo Form -->
<!-- START - We recommend to place the below code in head tag of your website html  -->
<style>
  @font-face {
    font-display: block;
    font-family: Roboto;
    src: url(https://assets.brevo.com/font/Roboto/Latin/normal/normal/7529907e9eaf8ebb5220c5f9850e3811.woff2) format("woff2"), url(https://assets.brevo.com/font/Roboto/Latin/normal/normal/25c678feafdc175a70922a116c9be3e7.woff) format("woff")
  }

  @font-face {
    font-display: fallback;
    font-family: Roboto;
    font-weight: 600;
    src: url(https://assets.brevo.com/font/Roboto/Latin/medium/normal/6e9caeeafb1f3491be3e32744bc30440.woff2) format("woff2"), url(https://assets.brevo.com/font/Roboto/Latin/medium/normal/71501f0d8d5aa95960f6475d5487d4c2.woff) format("woff")
  }

  @font-face {
    font-display: fallback;
    font-family: Roboto;
    font-weight: 700;
    src: url(https://assets.brevo.com/font/Roboto/Latin/bold/normal/3ef7cf158f310cf752d5ad08cd0e7e60.woff2) format("woff2"), url(https://assets.brevo.com/font/Roboto/Latin/bold/normal/ece3a1d82f18b60bcce0211725c476aa.woff) format("woff")
  }

  #sib-container input:-ms-input-placeholder {
    text-align: left;
    font-family: "Poppins-regular", sans-serif;
    color: #ccc;
  }
  .sib-form input:focus,.sib-form input:focus-within {
    outline: none !important;
  }
  .sib-form .entry__field:focus-within{
    box-shadow: none !important;
  }
.sib-form input{
  background-attachment: fixed;
    font-size: 16px;
    font-family: "Poppins-Bold",Roboto,sans-serif;
    padding: 32px 12px 32px;
    margin: 100px 0 0 0;
    border:solid 1px #000;
}
  #sib-container input::placeholder {
    text-align: left;
    font-family: "Poppins-Bold", sans-serif;
    color: #ccc;
  }
  .g-recaptcha{
    display: flex;
    justify-content: center;
  }
  #sib-container textarea::placeholder {
    text-align: left;
    font-family: "Poppins-regular", sans-serif;
    color: #c0ccda;
  }
  .sib-form .entry__field {
    border: 1px solid #d3d3d3 !important;
}
</style>
<link rel="stylesheet" href="https://sibforms.com/forms/end-form/build/sib-styles.css">
<!--  END - We recommend to place the above code in head tag of your website html -->

<!-- START - We recommend to place the below code where you want the form in your website html  -->
<div class="sib-form" style="text-align: center;
         background-color: #eeeff0;                                 ">
  <div id="sib-form-container" class="sib-form-container">
    <div id="error-message" class="sib-form-message-panel" style="font-size:16px; text-align:left; font-family:&quot;Poppins-regular&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;max-width:1040px;">
      <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
        <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
          <path d="M256 40c118.621 0 216 96.075 216 216 0 119.291-96.61 216-216 216-119.244 0-216-96.562-216-216 0-119.203 96.602-216 216-216m0-32C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm-11.49 120h22.979c6.823 0 12.274 5.682 11.99 12.5l-7 168c-.268 6.428-5.556 11.5-11.99 11.5h-8.979c-6.433 0-11.722-5.073-11.99-11.5l-7-168c-.283-6.818 5.167-12.5 11.99-12.5zM256 340c-15.464 0-28 12.536-28 28s12.536 28 28 28 28-12.536 28-28-12.536-28-28-28z" />
        </svg>
        <span class="sib-form-message-panel__inner-text">
                          Nous n&#039;avons pas pu confirmer votre inscription.
                      </span>
      </div>
    </div>
    <div></div>
    <div id="success-message" class="sib-form-message-panel" style="font-size:16px; text-align:left; font-family:&quot;Poppins-regular&quot;, sans-serif; color:#085229; background-color:#e7faf0; border-radius:3px; border-color:#13ce66;max-width:1040px;">
      <div class="sib-form-message-panel__text sib-form-message-panel__text--center">
        <svg viewBox="0 0 512 512" class="sib-icon sib-notification__icon">
          <path d="M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 464c-118.664 0-216-96.055-216-216 0-118.663 96.055-216 216-216 118.664 0 216 96.055 216 216 0 118.663-96.055 216-216 216zm141.63-274.961L217.15 376.071c-4.705 4.667-12.303 4.637-16.97-.068l-85.878-86.572c-4.667-4.705-4.637-12.303.068-16.97l8.52-8.451c4.705-4.667 12.303-4.637 16.97.068l68.976 69.533 163.441-162.13c4.705-4.667 12.303-4.637 16.97.068l8.451 8.52c4.668 4.705 4.637 12.303-.068 16.97z" />
        </svg>
        <span class="sib-form-message-panel__inner-text">
                          Votre inscription est confirmée.
                      </span>
      </div>
    </div>
    <div></div>
    <div id="sib-container" class="sib-container--large sib-container--horizontal" style="text-align:center; background-color:#eeeff0; max-width:1040px; border-radius:3px; border-width:1px; border-color:#eeeff0; border-style:solid; direction:ltr">
      <form id="sib-form" method="POST" action="https://a7a299f6.sibforms.com/serve/MUIFAJrM3rzrdCMuGUntYoxy9SMgaJEXENagxzWTmFusWDNdRdZU44laVzETYW5fJnoPzmESRYDZb2NH6uq5wtfJV_6yUfdg9b_mz0ERSz8UKCBocNU7xeA-Xj6SnUX_38NPEyVxmWUZJOMs0JvpnLzsKu0l5J0aQB1rtLeoBsAjX-9ji42Iso0EuBBhvARjurk6B2o_O6g1Gkar" data-type="subscription">
        <div style="padding: 8px 0;">
          <div class="sib-form-block" style="font-size:36px; text-align:center; font-weight:700; font-family:&quot;Poppins-ExtraBold&quot;, sans-serif; color:#000000; background-color:transparent; text-align:center">
            <p>Abonnez-vous à la newsletter</p>
          </div>
        </div>
        <div style="padding: 8px 0;">
          <div class="sib-form-block" style="font-size:15px; text-align:center; font-family:&quot;Poppins-regular&quot;, sans-serif; color:#3C4858; background-color:transparent; text-align:center">
            <div class="sib-text-form-block">
              <p>Restez informé de ce qui se passe sur la planète marathon !</p>
            </div>
          </div>
        </div>
        <div class="row">
            <div style="padding: 8px 0;" class="col-sm-6">
            <div class="sib-input sib-form-block">
                <div class="form__entry entry_block">
                <div class="form__label-row form__label-row--horizontal">

                    <div class="entry__field">
                    <input class="input " maxlength="200" type="text" id="NOM" name="NOM" autocomplete="off" placeholder="Nom" data-required="true" required />
                    </div>
                </div>

                <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Poppins-regular&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                </label>
                </div>
            </div>
            </div>
            <div style="padding: 8px 0;" class="col-sm-6">
            <div class="sib-input sib-form-block">
                <div class="form__entry entry_block">
                <div class="form__label-row form__label-row--horizontal">

                    <div class="entry__field">
                    <input class="input " type="text" id="EMAIL" name="EMAIL" autocomplete="off" placeholder="E-mail" data-required="true" required />
                    </div>
                </div>

                <label class="entry__error entry__error--primary" style="font-size:16px; text-align:left; font-family:&quot;Poppins-regular&quot;, sans-serif; color:#661d1d; background-color:#ffeded; border-radius:3px; border-color:#ff4949;">
                </label>
                </div>
                
                
            </div>
            </div>
            <div style="padding: 8px 0 0px 0;" class="col-sm-12">
            <div class="sib-input sib-form-block">
                
                <div class="form__label-row form__label-row--horizontal" style="margin:0">
                    <div class="entry__choice" >
                    <label>
                        <input type="checkbox" class="input_replaced" value="1" id="OPT_IN" name="OPT_IN" />
                        <span class="checkbox checkbox_tick_positive" style="margin-left:"></span>
                        <span style="float: right;margin-left: 25px;font-size:9px; text-align:left; font-family:&quot;Poppins-regular&quot;, sans-serif; color:#b6b7b7; background-color:transparent;">
                            <p>J'accepte de recevoir vos e-mails et confirme avoir pris connaissance de votre politique de confidentialité et 
                                mentions légales. Nous utilisons Brevo en tant que plateforme marketing. En soumettant ce formulaire, vous 
                                reconnaissez que les informations que vous allez fournir seront transmises à Brevo en sa qualité de processeur 
                                de données; et ce conformément à ses <a target="_blank" class="clickable_link" style="font-size: inherit; font-family: inherit; line-height: normal" href="https://www.brevo.com/fr/legal/termsofuse/">conditions générales d'utilisation</a></p>
                        </span> 
                           
                </label>
                    </div>
                </div>
                
                
            </div>
            </div>
            <div style="padding: 0px 0 8px 0;" class="col-sm-12">
            <div class="g-recaptcha" id="g-recaptcha" style="padding:0 0 16px 0px"
                                data-sitekey="6LdcITUpAAAAAJNe_-kxs-4q4Xy9_HrQnk3FvTkx"></div>
            </div>
            
        </div>
        <div style="padding: 0px 0 8px 0;">
          <div class="sib-form-block" style="text-align: center">
            <button class="sib-form-block__button sib-form-block__button-with-loader" style="font-size:16px; text-align:center; font-weight:700; font-family:&quot;Poppins-regular&quot;, sans-serif; color:#000000; background-color:#95d7fe; border-radius:3px; border-width:0px;" form="sib-form" type="submit">
              <svg class="icon clickable__icon progress-indicator__icon sib-hide-loader-icon" viewBox="0 0 512 512">
                <path d="M460.116 373.846l-20.823-12.022c-5.541-3.199-7.54-10.159-4.663-15.874 30.137-59.886 28.343-131.652-5.386-189.946-33.641-58.394-94.896-95.833-161.827-99.676C261.028 55.961 256 50.751 256 44.352V20.309c0-6.904 5.808-12.337 12.703-11.982 83.556 4.306 160.163 50.864 202.11 123.677 42.063 72.696 44.079 162.316 6.031 236.832-3.14 6.148-10.75 8.461-16.728 5.01z" />
              </svg>
              Je m'abonne
            </button>
          </div>
        </div>
        

        <input type="text" name="email_address_check" value="" class="input--hidden">
        <input type="hidden" name="locale" value="fr">
      </form>
    </div>
  </div>
</div>
<!-- END - We recommend to place the below code where you want the form in your website html  -->

<!-- START - We recommend to place the below code in footer or bottom of your website html  -->
<script>
  window.REQUIRED_CODE_ERROR_MESSAGE = 'Veuillez choisir un code pays';
  window.LOCALE = 'fr';
  window.EMAIL_INVALID_MESSAGE = window.SMS_INVALID_MESSAGE = "Les informations que vous avez fournies ne sont pas valides. Veuillez vérifier le format du champ et réessayer.";

  window.REQUIRED_ERROR_MESSAGE = "Vous devez renseigner ce champ. ";

  window.GENERIC_INVALID_MESSAGE = "Les informations que vous avez fournies ne sont pas valides. Veuillez vérifier le format du champ et réessayer.";




  window.translation = {
    common: {
      selectedList: '{quantity} liste sélectionnée',
      selectedLists: '{quantity} listes sélectionnées'
    }
  };

  var AUTOHIDE = Boolean(0);
</script>
<script data-type='lazy' ddata-src="https://sibforms.com/forms/end-form/build/main.js"></script>
<script async src="https://static.linguise.com/script-js/switcher.bundle.js?d=pk_VU5C4h0YOZqUtZn2ha54fz8fJjLGIsPz"></script>
<script data-type='lazy' ddata-src="https://www.google.com/recaptcha/api.js?hl=fr"></script>

<!-- END - We recommend to place the above code in footer or bottom of your website html  -->
<!-- End Brevo Form -->
<footer>
    <script type="text/javascript">
    $(document).ready(function() {

    });
    </script>
    <div class="container">
        <div class="main">

            <div class="row">
                <div class="">
                    <div class="col-sm-2">
                        <dt>allmarathon</dt>
                        <dd>
                            <ul>
                                <li><a href="/mentions.html">Mentions légales</a></li>
                                <li><a href="/politique-de-confidentialite.html">Politique de confidentialité</a></li>
								<li><a href="/statistiques.php">Statistiques</a></li>
                                <li><a href="/contact.html">Contact</a></li>
                                <li><a href="/partenaires.php">Partenariats</a></li>
                            </ul>
                        </dd>

                        

                    </div>
                    <div class="col-sm-2">
                        
                        <dt>Suivez-nous</dt>
                        <dd>
                                        <ul>
                                            <li><a href="https://www.facebook.com/dev.allmarathon.fr"
                                                    target="_blank">
                                                    Facebook</a></li>
                                            <li><a href="https://www.instagram.com/dev.allmarathon.fr"
                                                    target="_blank">Instagram</a></li>
                                                    <li><a href="https://www.pinterest.fr/allmarathon/"
                                            target="_blank">Pinterest</a></li>
                                            <li><a href="https://whatsapp.com/channel/0029Va3y67f0G0Xmv2z5jC2A"
                                            target="_blank">Whatsapp</a></li>
                                            <li><a href="/flux-rss.xml"
                                            target="_blank">Flux RSS</a></li>
                                        </ul>
                                    </dd>
                        
                    </div>
                    
                    <div class="col-sm-2">
                        <dt>athlètes</dt>
                        <dd>
                            <ul>
                                <li><a href="/athlete-36-eliud-kipchoge.html">Eliud Kipchoge</a></li>
                                <li><a href="/athlete-4438-abebe-bikila.html">Abebe Bikila</a>
                                </li>
                                <li><a href="/athlete-1060-mohamed-farah.html">Mohamed Farah</a></li>
                                <li><a href="/athlete-4245-waldemar-cierpinski.html">Waldemar Cierpinski</a></li>
                                <li><a href="/athlete-38-hassan-chahdi.html">Hassan Chahdi</a></li>
                                <li><a href="/athlete-1079-kelvin-kiptum.html">Kelvin Kiptum</a></li>
                            </ul>
                        </dd>
                    </div>
                    <div class="col-sm-2">
                        <dt>marathons</dt>      
                        <dd>
                            <ul>
                                <li><a href="/marathons-20-berlin.html">Berlin</a></li>
                                <li><a href="/marathons-8-boston.html">Boston</a></li>
                                <li><a href="/marathons-102-bank-of-america-chicago.html">Chicago</a></li>
                                <li><a href="/marathons-61-londres.html">Londres</a></li>
                                <li><a href="/marathons-128-new-york.html">New York</a></li>
                                <li><a href="/marathons-34-schneider-electric-paris.html">Paris</a></li>
                                <li><a href="/marathons-52-tokyo.html">Tokyo</a></li>
                            </ul>
                        </dd>         
                    </div>
                    <div class="col-sm-4 ">
                        
                        <dt>Outils</dt>
                        <dd>
                            <ul>
                                        <li><a href="/calculateur-de-temps-de-passages.html">Calculateur de temps de passages en course</a></li>
                                        <li><a href="/convertisseur-allure-vitesse.html">Convertisseur allure-vitesse et vitesse-allure</a></li>

                            </ul>
                        </dd>
                    </div>
                </div>
               
                
                
            </div>
        </div> 
    </div>
    <div class="copyright">
        <!--
        <p><i class="fa fa-copyright"></i> allmarathon 2023 - <?php //echo date("Y");?></p>
        <a href="https://nash-digital.com/" target="_blank">Nash agence web offshore</a>
-->
        <p><i class="fa fa-copyright"></i> Tous droits réservés, propriété de <a href="https://nash-digital.com/" target="_blank">NASH</a></p>
    </div>
</footer>