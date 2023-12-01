
<!doctype html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="utf-8"><script type="text/javascript">window.NREUM||(NREUM={}),__nr_require=function(e,t,n){function r(n){if(!t[n]){var o=t[n]={exports:{}};e[n][0].call(o.exports,function(t){var o=e[n][1][t];return r(o||t)},o,o.exports)}return t[n].exports}if("function"==typeof __nr_require)return __nr_require;for(var o=0;o<n.length;o++)r(n[o]);return r}({1:[function(e,t,n){function r(){}function o(e,t,n){return function(){return i(e,[(new Date).getTime()].concat(u(arguments)),t?null:this,n),t?void 0:this}}var i=e("handle"),a=e(2),u=e(3),c=e("ee").get("tracer"),f=NREUM;"undefined"==typeof window.newrelic&&(newrelic=f);var s=["setPageViewName","setCustomAttribute","setErrorHandler","finished","addToTrace","inlineHit"],l="api-",p=l+"ixn-";a(s,function(e,t){f[t]=o(l+t,!0,"api")}),f.addPageAction=o(l+"addPageAction",!0),f.setCurrentRouteName=o(l+"routeName",!0),t.exports=newrelic,f.interaction=function(){return(new r).get()};var d=r.prototype={createTracer:function(e,t){var n={},r=this,o="function"==typeof t;return i(p+"tracer",[Date.now(),e,n],r),function(){if(c.emit((o?"":"no-")+"fn-start",[Date.now(),r,o],n),o)try{return t.apply(this,arguments)}finally{c.emit("fn-end",[Date.now()],n)}}}};a("setName,setAttribute,save,ignore,onEnd,getContext,end,get".split(","),function(e,t){d[t]=o(p+t)}),newrelic.noticeError=function(e){"string"==typeof e&&(e=new Error(e)),i("err",[e,(new Date).getTime()])}},{}],2:[function(e,t,n){function r(e,t){var n=[],r="",i=0;for(r in e)o.call(e,r)&&(n[i]=t(r,e[r]),i+=1);return n}var o=Object.prototype.hasOwnProperty;t.exports=r},{}],3:[function(e,t,n){function r(e,t,n){t||(t=0),"undefined"==typeof n&&(n=e?e.length:0);for(var r=-1,o=n-t||0,i=Array(o<0?0:o);++r<o;)i[r]=e[t+r];return i}t.exports=r},{}],ee:[function(e,t,n){function r(){}function o(e){function t(e){return e&&e instanceof r?e:e?c(e,u,i):i()}function n(n,r,o){if(!d){e&&e(n,r,o);for(var i=t(o),a=v(n),u=a.length,c=0;c<u;c++)a[c].apply(i,r);var f=s[y[n]];return f&&f.push([b,n,r,i]),i}}function p(e,t){w[e]=v(e).concat(t)}function v(e){return w[e]||[]}function g(e){return l[e]=l[e]||o(n)}function m(e,t){f(e,function(e,n){t=t||"feature",y[n]=t,t in s||(s[t]=[])})}var w={},y={},b={on:p,emit:n,get:g,listeners:v,context:t,buffer:m,abort:a};return b}function i(){return new r}function a(){d=!0,s=p.backlog={}}var u="nr@context",c=e("gos"),f=e(2),s={},l={},p=t.exports=o(),d=!1;p.backlog=s},{}],gos:[function(e,t,n){function r(e,t,n){if(o.call(e,t))return e[t];var r=n();if(Object.defineProperty&&Object.keys)try{return Object.defineProperty(e,t,{value:r,writable:!0,enumerable:!1}),r}catch(i){}return e[t]=r,r}var o=Object.prototype.hasOwnProperty;t.exports=r},{}],handle:[function(e,t,n){function r(e,t,n,r){o.buffer([e],r),o.emit(e,t,n)}var o=e("ee").get("handle");t.exports=r,r.ee=o},{}],id:[function(e,t,n){function r(e){var t=typeof e;return!e||"object"!==t&&"function"!==t?-1:e===window?0:a(e,i,function(){return o++})}var o=1,i="nr@id",a=e("gos");t.exports=r},{}],loader:[function(e,t,n){function r(){if(!h++){var e=b.info=NREUM.info,t=l.getElementsByTagName("script")[0];if(setTimeout(f.abort,3e4),!(e&&e.licenseKey&&e.applicationID&&t))return f.abort();c(w,function(t,n){e[t]||(e[t]=n)}),u("mark",["onload",a()],null,"api");var n=l.createElement("script");n.src="https://"+e.agent,t.parentNode.insertBefore(n,t)}}function o(){"complete"===l.readyState&&i()}function i(){u("mark",["domContent",a()],null,"api")}function a(){return(new Date).getTime()}var u=e("handle"),c=e(2),f=e("ee"),s=window,l=s.document,p="addEventListener",d="attachEvent",v=s.XMLHttpRequest,g=v&&v.prototype;NREUM.o={ST:setTimeout,CT:clearTimeout,XHR:v,REQ:s.Request,EV:s.Event,PR:s.Promise,MO:s.MutationObserver},e(1);var m=""+location,w={beacon:"bam.nr-data.net",errorBeacon:"bam.nr-data.net",agent:"js-agent.newrelic.com/nr-995.min.js"},y=v&&g&&g[p]&&!/CriOS/.test(navigator.userAgent),b=t.exports={offset:a(),origin:m,features:{},xhrWrappable:y};l[p]?(l[p]("DOMContentLoaded",i,!1),s[p]("load",r,!1)):(l[d]("onreadystatechange",o),s[d]("onload",r)),u("mark",["firstbyte",a()],null,"api");var h=0},{}]},{},["loader"]);</script>
        <title>Sign in to Newsweek</title>

        

        <link rel="stylesheet" href="../../css/bootstrap.min.css">
        <link rel="stylesheet" href="../../css/font-awesome.min.css">
        <link rel="stylesheet" href="../../css/fonts.css">
        <link rel="stylesheet" href="../../css/slider-pro.min.css"/>
        <link rel="stylesheet" href="../../css/main.css">
        <link rel="stylesheet" href="../../css/authentification.css">

         <script>
        $(document).ready(function () {
            $("form").unbind("submit").submit(function (e) {
                e.preventDefault();
                                    $.ajax({
                    cache: false,
                    url      : "http://sns.europe.newsweek.com/auth/login/SYSTEM?ack=?",
                    dataType : "jsonp",
                    data     : {
                        'email'    : $("#email").val(),
                        "password" : $("#password").val(),
                        "referer"  : $("#referer").val(),
                        "option"   : $("#option").val()
                    },
                    success  : function (json) {
                        if (json.msg && json.msg == 100) {
                            if (json.option) {
                                parent.postMessage("donextthing", "http://europe.newsweek.com");
                            } else {
                                login_success(json.u_session, json.referer);
                            }
                        }
                        else if (json.msg) {
                            $(".fail").css("display", "none");
                            $($(".fail")[json.msg]).css("display", "block").delay(1000).fadeOut(3000);
                        }
                    }
                });
                        });
                    $('.form-wrap input').focusout(function () {
            var text_val = $(this).val();
                    if (text_val === "") {
            $(this).removeClass('has-value');
            } else {
            $(this).addClass('has-value');
            }
            });
                    $(".sns-signin .bg-btn").click(function (e) {
            e.preventDefault();
                    window.open($(this).attr("href"), "Sign in", "width=700,height=600");
            });
                    $(".resend").click(function (e) {
            e.preventDefault();
                    $.ajax({
                    cache: false,
                            url: "https://sns.europe.newsweek.com/auth/resend?ack=?",
                            data: {'email': $("#email").val()},
                            dataType: "jsonp",
                            success: function (json) {
                            if (json.msg)
                            {
                            $(".fail").css("display", "none");
                                    $($(".fail")[json.msg]).css("display", "block").delay(1000).fadeOut(3000);
                            }
                            }
                    });
            });
                                                            parent.postMessage("close_check", "http://europe.newsweek.com"); // different host


                    $(".link").unbind('click').click(function (e) {
            e.preventDefault();
                    parent.postMessage("link" + $(this).attr("href"), "http://europe.newsweek.com");
            });
                    $("#donext").click(function (e) {
            e.preventDefault();
                    parent.postMessage("donextthing", "http://europe.newsweek.com"); // different host
            });
            });
                    window.addEventListener("message", get_msg, false);
                    function get_msg(event)
                    {
                    if (event.origin !== "http://europe.newsweek.com")
                            return;
                            if (event.data.substr(11) == "1")
                    {
                    $('<a href="#" class="close-windows bg-btn">Close</a>').insertAfter(".overlay");
                            $(".overlay").add(".close-windows").unbind('click').click(function (e) {
                    e.preventDefault();
                            e.stopPropagation();
                                                });
                    }
//	var val = event.data.substr(0,13);

                    }

    var login_success = function (val, burl, option) {
        var hasOption = false;
        var isPopup   = false;

        // Pass through registry of URLs
        var urls = {
            sns: 'http://sns.europe.newsweek.com'
        };

        function forceClosePopup() {
            if (hasOption) {
                parent.postMessage("donextthing", "http://europe.newsweek.com");
                parent.postMessage("donextthing", "http://sns.europe.newsweek.com"); //same host
            } else if (isPopup) {
                parent.postMessage("success_login:" + val, "http://europe.newsweek.com"); // different host
                parent.postMessage("success_login:" + val, "http://sns.europe.newsweek.com"); //same host
            } else {
                if (!burl) {
                    burl = 'http://sns.europe.newsweek.com/user/profile';
                }
    
                location.href = burl; // not iframe
            }
        }

        if (!$('form').hasClass('require-subscription')) {
            return forceClosePopup();
        }

        var user = new BrowserUser({urls: urls});

        user.checkIsSubscribed(function (isSubscribed) {
            if (isSubscribed) {
                forceClosePopup();
            } else {
                location.href = 'http://sns.europe.newsweek.com/newsletter/paywall?popup=1';
            }
        });
    }

            var closeFrame = function () {

                                history.back();
                    //location.href = "/user"; // not iframe
                                }

            </script>

    </head>
    <body>
        <div class="overlay overlay__black"></div>

        <div class="auth registration">

            <div class="auth--header">
                Sign in
            </div>
            <div class="auth--body">
                <div class="col-md-12 fail" style="display:none;">

                </div>
                
                <form name="signup" id="form" method="post" class="clearfix signup-form relative " action="/allmarathon_nv/www/content/modules/login.php">
                        <input type="hidden" name="referer" id="referer" value="" />
                        <input type="hidden" name="option" id="option" value="" />
                        <div class="col-md-12 form-wrapper">
                            <label for="email">Nom d'utilisateur </label>
                            <input type="text" class="input_auth" name="name_user" id="name_user" value="" required />
                        </div>
                        <div class="col-md-12 form-wrapper" style="margin-top: 20px !important;">
                            <label for="password">Mot de passe</label>
                            <input type="password" class="input_auth" name="password" id="password_auth" value="" required />
                            <a href="/forgot.php" class="link">Mot de passe oublié?</a>
                        </div>
                        <div class="col-md-12 t-align--right form-wrapper">
                            <input type="submit" name="submit" id="submit" value="Se connecter" class="btn_auth button__red" />
                        </div>
                    </form>

                
            </div>

                            <div class="auth--footer">
                    Pas encore inscrit ? <a href="/formulaire-inscription.php" class="link">Inscrivez-vous aujourd'hui ! </a>
                </div>
                    </div>
        <!-- New Google Analytics Code -->
        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-61704137-1', 'auto');
            ga('require', 'displayfeatures');
            ga('send', 'pageview', {
                'dimension2': 'Signin', // page type
            });
        </script>
        <!-- End New Google Analytics Code -->
        <!-- silverpop tracking -->
        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
<script>window.jQuery || document.write('<script src="../../js/vendor/jquery-1.12.0.min.js"><\/script>')</script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/plugins.js"></script>
<script src="../../js/jquery.jcarousel.min.js"></script>
<script src="../../js/jquery.sliderPro.min.js"></script>
<script src="../../js/main.js"></script>

    <script async src="http://contentz.mkt61.net/lp/static/js/iMAWebCookie.js?8d52568-15439653d0e-b292eee3e12767e0c1a23a3c31e9c522&h=www.pages06.net" type="text/javascript"></script>
    <script type="text/javascript">window.NREUM||(NREUM={});NREUM.info={"beacon":"bam.nr-data.net","licenseKey":"a865865828","applicationID":"6460424","transactionName":"ZVADMRAHWhVWW0JaDFwaNBcLSV0IU11OHRNaRQ==","queueTime":0,"applicationTime":4,"atts":"SRcAR1gdSRs=","errorBeacon":"bam.nr-data.net","agent":""}</script></body>
</html>