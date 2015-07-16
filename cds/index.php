<?php
    header('Content-Type: text/html; charset=utf-8');
    require_once( 'cds_service_request.php' );

    $page = 'search';
    if (isset($_REQUEST['page'])) {
        $page = $_REQUEST['page'];
    }
    // if keywords in faceted search, use keys page for keywords
    if (isset($_REQUEST['c']) && $_REQUEST['c'] === 'keys') {
        $page = 'keys';
    }
    $unitSystem = 'english';
?>
<!DOCTYPE html>
<html lang="en-US" prefix="og: http://ogp.me/ns#">
<head>
<meta charset="UTF-8" />

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href='http://fonts.googleapis.com/css?family=Ruda:400,700,900' rel='stylesheet' type='text/css'>

<link rel="pingback" href="http://www.gilmanprecision.com/xmlrpc.php" />
<!-- Google Analytics Content Experiment code -->
<script>function utmx_section(){}function utmx(){}(function(){var
k='60906687-0',d=document,l=d.location,c=d.cookie;
if(l.search.indexOf('utm_expid='+k)>0)return;
function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
</script><script>utmx('url','A/B');</script>
<!-- End of Google Analytics Content Experiment code -->

<title>Products | Gilman Precision, USA</title>

<link rel="alternate" type="application/rss+xml" title=" &raquo; Feed" href="http://www.gilmanprecision.com/feed" />
<link rel="alternate" type="application/rss+xml" title=" &raquo; Comments Feed" href="http://www.gilmanprecision.com/comments/feed" />
<link rel="alternate" type="application/rss+xml" title=" &raquo; DOVETAIL SLIDES Comments Feed" href="http://www.gilmanprecision.com/machine-slide-stages/dovetail-slides/feed" />
<link rel='stylesheet' id='tubepress-css'  href='http://www.gilmanprecision.com/wp-content/plugins/tubepress/src/main/web/css/tubepress.css?ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='contact-form-7-css'  href='http://www.gilmanprecision.com/wp-content/plugins/contact-form-7/includes/css/styles.css?ver=3.7.2' type='text/css' media='all' />
<link rel='stylesheet' id='responsive-lightbox-fancybox-front-css'  href='http://www.gilmanprecision.com/wp-content/plugins/responsive-lightbox/assets/fancybox/jquery.fancybox-1.3.4.css?ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='theme-stylesheet-css'  href='http://www.gilmanprecision.com/wp-content/themes/definition/style.css?ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='woo-layout-css'  href='http://www.gilmanprecision.com/wp-content/themes/definition/css/layout.css?ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='easymedia_styles-css'  href='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/css/frontend.css?ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='easymedia_paganimate-css'  href='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/css/animate.css?ver=4.1.4' type='text/css' media='all' />
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-includes/js/jquery/jquery.js?ver=1.11.1'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.2.1'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/tubepress/src/main/web/js/tubepress.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/jquery/jquery.fittext.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/mootools/mootools-core-1.4.5-min.js?ver=4.1.4'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var EasyM = {"nblaswf":"http:\/\/www.gilmanprecision.com\/wp-content\/plugins\/easy-media-gallery-dev\/includes\/swf\/NonverBlaster.swf","audiovol":"75","audioautoplay":"false","audioloop":"false","vidautopa":"","vidautopb":"","vidautopc":"0","vidautopd":"false","drclick":"true","swcntr":"true","pageffect":"flipInX","ajaxconid":"#content","defstyle":"Light","mediaswf":"http:\/\/www.gilmanprecision.com\/wp-content\/plugins\/easy-media-gallery-dev\/includes\/addons\/mediaelement\/flashmediaelement.swf","ajaxpth":"\/wp-content\/plugins\/easy-media-gallery-dev\/includes\/easyloader.php","ovrlayop":"0.75","closepos":"1","sospos":"0"};
/* ]]> */
</script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/mootools/easymedia.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/jquery/jquery.isotope.min.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/func/frontend.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/jquery/jPages.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/jquery/jquery.lazyload.min.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/responsive-lightbox/assets/fancybox/jquery.fancybox-1.3.4.js?ver=4.1.4'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var rlArgs = {"script":"fancybox","selector":"lightbox","custom_events":"","activeGalleries":"1","modal":"0","showOverlay":"1","showCloseButton":"1","enableEscapeButton":"1","hideOnOverlayClick":"1","hideOnContentClick":"0","cyclic":"0","showNavArrows":"1","autoScale":"1","scrolling":"yes","centerOnScroll":"1","opacity":"1","overlayOpacity":"70","overlayColor":"#666","titleShow":"1","titlePosition":"outside","transitions":"fade","easings":"swing","speeds":"300","changeSpeed":"300","changeFade":"100","padding":"5","margin":"5","videoWidth":"1080","videoHeight":"720"};
/* ]]> */
</script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/responsive-lightbox/js/front.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/themes/definition/includes/js/third-party.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/themes/definition/includes/js/general.js?ver=4.1.4'></script>
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="http://www.gilmanprecision.com/xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="http://www.gilmanprecision.com/wp-includes/wlwmanifest.xml" />
<meta name="generator" content="WordPress 4.1.4" />
<link rel='shortlink' href='http://www.gilmanprecision.com/?p=212' />
<link rel="alternate stylesheet" title="Dark" type="text/css" media="screen,projection" href="http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/css/styles/mediabox/Dark.css" />
<link rel="alternate stylesheet" title="Light" type="text/css" media="screen,projection" href="http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/css/styles/mediabox/Light.css" />
<link rel="alternate stylesheet" title="Transparent" type="text/css" media="screen,projection" href="http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/css/styles/mediabox/Transparent.css" />

<script src="http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/addons/mediaelement/mediaelement-and-player.min.js"></script>
<link href="http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/addons/mediaelement/mediaelementplayer-skin-yellow.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/dynamic-style.php" type="text/css" media="screen" />

<!-- Easy Media Gallery Dev START (version 1.5.0.0)-->

 <!--[if lt IE 9]>
<script src="http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/func/html5.js" type="text/javascript"></script>
<![endif]-->

 <!--[if lt IE 9]>
<script src="http://www.gilmanprecision.com/wp-content/plugins/easy-media-gallery-dev/includes/js/func/html5shiv.js" type="text/javascript"></script>
<![endif]-->

<!-- Easy Media Gallery Dev END  -->

    <script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-32562435-1']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>

<script type="text/javascript">var TubePressJsConfig = {"urls":{"base":"http:\/\/www.gilmanprecision.com\/wp-content\/plugins\/tubepress","usr":"http:\/\/www.gilmanprecision.com\/wp-content\/tubepress-content"}};</script>

<!-- Theme version -->
<meta name="generator" content="Definition 1.3.3" />
<meta name="generator" content="WooFramework 5.5.3" />

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

<!--  Mobile viewport scale | Disable user zooming as the layout is optimised -->
<meta content="initial-scale=1.0; maximum-scale=1.0; user-scalable=no" name="viewport"/>
<!--[if lt IE 9]>
<script src="https://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!-- Woo Custom Styling -->
<style type="text/css">
#wrapper { background: #FFFFFF !important; }
a { color: #0066CC !important; }
a:hover, .post-more a:hover, .post-meta a:hover, .post p.tags a:hover { color: #FF0000 !important; }
</style>

<!-- Google Webfonts -->
<link href="http://fonts.googleapis.com/css?family=Titillium+Web:400,400italic,600,600italic,700,700italic" rel="stylesheet" type="text/css" />

<!-- Woo Custom Typography -->
<style type="text/css">
body { font:normal 1.3em/1.5em "Helvetica Neue", Helvetica, sans-serif;color:#666666; }
.nav a { font:300 1em/1.4em "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", sans-serif;color:#ffffff; }
.page header h1 { font:bold 1.7em/1em "Myriad Pro", Myriad, sans-serif;color:#003366; }
.post header h1, .post header h1 a:link, .post header h1 a:visited { font:bold 1.3em/1em "Myriad Pro", Myriad, sans-serif;color:#003366; }
.post-meta { font:300 1em/1em "Helvetica Neue", Helvetica, sans-serif;color:#003366; }
.entry, .entry p { font:normal 1em/1.5em "Helvetica Neue", Helvetica, sans-serif;color:#666666; } h1, h2, h3, h4, h5, h6 { font-family: "Myriad Pro", Myriad, sans-serif, arial, sans-serif; }
.widget h3 { font:bold 1em/1em "Myriad Pro", Myriad, sans-serif;color:#003366; }
.widget h3 { font:bold 1em/1em "Myriad Pro", Myriad, sans-serif;color:#003366; }
</style>

<!-- Alt Stylesheet -->
<link href="http://www.gilmanprecision.com/wp-content/themes/definition/styles/default.css" rel="stylesheet" type="text/css" />

<!-- Custom Favicon -->
<link rel="shortcut icon" href="http://www.gilmanprecision.com/wp-content/uploads/2013/04/favicon.ico"/>
<!-- Options Panel Custom CSS -->
<style type="text/css">
body {
background:#FFFFFF!important;
}
#header {
    padding: 1.5em 13em;
}
.flex-direction-nav a {
color: #C6C6C6 !important;
}

@media all and (max-width: 699px) {
#header {
padding:1.5em 0;
}
a.moduleItemReadMore, a.k2ReadMore, input[type="submit"], button, div.itemCommentsForm form input#submitCommentButton {
margin-top:0;
}
#top {
margin: -1.5em -1.618em 0;
}
}
</style>


<!-- Woo Shortcodes CSS -->
<link href="http://www.gilmanprecision.com/wp-content/themes/definition/functions/css/shortcodes.css" rel="stylesheet" type="text/css" />

<!-- Custom Stylesheet -->
<link href="http://www.gilmanprecision.com/wp-content/themes/definition/custom.css" rel="stylesheet" type="text/css" />
<link href="font-awesome-4.3.0/css/font-awesome.css" rel="stylesheet">

<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js" type="text/javascript"></script>
<script src="http://<?php echo htmlspecialchars($host) ?>/catalog3/js/cds-catalog-min.js"></script>
<link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/themes/smoothness/jquery-ui.min.css" type="text/css" rel="stylesheet" />
<link href="http://<?php echo htmlspecialchars($host) ?>/catalog3/css/catalog-3.1.css" rel="stylesheet" />
<link href="catalog.css" rel="stylesheet">
</head>
<body class="page page-id-212 page-child parent-pageid-209 page-template page-template-template-fullwidth page-template-template-fullwidth-php gecko alt-style-default layout-left-content">

<div id="wrapper">


    <div id="top">
        <nav class="col-full" role="navigation">
            <ul id="top-nav" class="nav fl"><li id="menu-item-16" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-16"><a href="http://www.gilmanprecision.com/precision-machine-spindles">SPINDLES</a>
<ul class="sub-menu">
    <li id="menu-item-100" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-100"><a href="http://www.gilmanprecision.com/precision-machine-spindles/belt-driven-spindles">BELT DRIVEN</a></li>
    <li id="menu-item-101" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-101"><a href="http://www.gilmanprecision.com/precision-machine-spindles/motorized-spindles">MOTORIZED SPINDLES</a></li>
</ul>
</li>
<li id="menu-item-211" class="menu-item menu-item-type-post_type menu-item-object-page current-page-ancestor current-menu-ancestor current-menu-parent current-page-parent current_page_parent current_page_ancestor menu-item-has-children menu-item-211"><a href="http://www.gilmanprecision.com/machine-slide-stages">SLIDES</a>
<ul class="sub-menu">
    <li id="menu-item-214" class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-212 current_page_item menu-item-214"><a href="http://www.gilmanprecision.com/machine-slide-stages/dovetail-slides">DOVETAIL SLIDES</a></li>
    <li id="menu-item-217" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-217"><a href="http://www.gilmanprecision.com/machine-slide-stages/linear-guide-slides">LINEAR GUIDE SLIDES</a></li>
    <li id="menu-item-226" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-226"><a href="http://www.gilmanprecision.com/machine-slide-stages/hardened-box-way-slides">HARDENED BOX WAY SLIDES</a></li>
</ul>
</li>
<li id="menu-item-223" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-223"><a href="http://www.gilmanprecision.com/machine-modules">MACHINE MODULES</a></li>
<li id="menu-item-225" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-225"><a href="http://www.gilmanprecision.com/slide-spindle-repair-services">SERVICE</a></li>
<li id="menu-item-15" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-15"><a href="http://www.gilmanprecision.com/machine-spindle-slide-design-resources">RESOURCES</a>
<ul class="sub-menu">
    <li id="menu-item-109" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-109"><a href="http://www.gilmanprecision.com/machine-spindle-slide-design-resources/product-catalogs">PRODUCT CATALOGS</a></li>
    <li id="menu-item-271" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-271"><a href="http://www.gilmanprecision.com/machine-spindle-slide-design-resources/idea-bulletins">IDEA BULLETINS</a></li>
    <li id="menu-item-330" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-330"><a href="http://www.gilmanprecision.com/machine-spindle-slide-design-resources/cad-drawings">CAD DRAWINGS</a></li>
    <li id="menu-item-452" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-452"><a href="http://www.gilmanprecision.com/video-gallery">VIDEO GALLERY</a></li>
</ul>
</li>
<li id="menu-item-144" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-144"><a href="http://www.gilmanprecision.com/category/news-events">NEWS / EVENTS</a></li>
<li id="menu-item-365" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-365"><a href="http://www.gilmanprecision.com/contact-us/rep-locator">REP LOCATOR</a></li>
<li id="menu-item-13" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-13"><a href="http://www.gilmanprecision.com/contact-us">CONTACT US</a>
<ul class="sub-menu">
    <li id="menu-item-31" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-31"><a href="http://www.gilmanprecision.com.au">AUSTRALIA</a></li>
    <li id="menu-item-112" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-112"><a href="http://www.gilmanprecision.com.sg">SINGAPORE</a></li>
</ul>
</li>
</ul>       </nav>
    </div><!-- /#top -->


    <header id="header">
            <div class="header-logosearch">
    <a id="logo" href="http://www.gilmanprecision.com/" title="">
        <img src="http://www.gilmanprecision.com/wp-content/uploads/2013/04/logo.png" alt="" />
    </a>
</div>
<div class="col-full">
<div id="header-country-image">
<div class="search_main fix"><form role="search" class="searchform" method="get" id="searchform" class="searchform" action="http://www.gilmanprecision.com/">
                <div>
                    <label class="screen-reader-text" for="s">Search for:</label>
                    <input type="text" value="" name="s" class="field s" placeholder="Search..." id="s" />
                    <input type="submit" class="search-submit" id="searchsubmit" value="Search" />
                </div>
            </form></div><div class="social-header">
<span class="fa-stack fa-lg" id="m-head-icon">
<a href="https://www.facebook.com/pages/Gilman-Precision/437280603016566" target="_blank"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-facebook fa-stack-1x fa-inverse"></i></a>
</span>
<span class="fa-stack fa-lg" id="m-head-icon">
<a href="http://www.youtube.com/user/gilmanprecision" target="_blank"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-youtube fa-stack-1x fa-inverse"></i></a>
</span>
<span class="fa-stack fa-lg" id="m-head-icon">
<a href="https://www.linkedin.com/company/2522677?trk=tyah&trkInfo=tarId%3A1404998755073%2Ctas%3Agilman%20precision%20%2Cidx%3A1-1-1" target="_blank"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-linkedin fa-stack-1x fa-inverse"></i></a>
</span>
<span class="fa-stack fa-lg">
<a href="https://plus.google.com/115379256453731136459/about" target="_blank"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-google-plus fa-stack-1x fa-inverse"></i></a>
</span>
</div>
</div>



        </div><!-- /.col-full -->
    </header><!-- /#header -->




        <section id="main" class="fullwidth">

<?php
    if ($page === 'search') {
        require_once( 'search.php' );
    } elseif ($page === 'product') {
        require_once( 'products.php' );
    } elseif ($page === 'keys') {
        require_once( 'keys.php' );
    } elseif ($page === 'compare') {
        require_once( 'compare.php' );
    } elseif ($page === 'cart') {
        require_once( 'cart.php' );
    }
?>


        </section><!-- /#main -->


    </div><!-- /#content -->


        <div style="clear:both;"></div>
    <footer id="footer">


            <section id="footer-widgets" class="col-full col-3 fix">


                <div class="block footer-widget-1">
                    <div id="text-21" class="widget widget_text"><h3>Find A Rep</h3>            <div class="textwidget"><span class="footer-map"><img alt="Rep Locator" src="http://www.gilmanprecision.com/wp-content/uploads/2014/04/rep_icon.png"></span>
<p style="color:#ffffff;">Find a Gilman Precision representative in your area.</p><div class="bannerMore"><a target="_self" href="http://www.gilmanprecision.com/contact-us/rep-locator" class="moduleItemReadMore" style="margin-top:0!important;">Read More</a></div></div>
        </div>              </div>


                <div class="block footer-widget-2">
                    <div id="text-14" class="widget widget_text"><h3>USA</h3>           <div class="textwidget"><p style="color:#ffffff;">1230 Cheyenne Avenue, P.O. Box 5<br />
Grafton, WI 53024-0005<br />
<a href="mailto:sales@gilmanprecision.com">sales@gilmanprecision.com</a></p>

<h3 style="color:#ffffff;">+1-262-377-2434</h3></div>
        </div>              </div>


                <div class="block footer-widget-3">
                    <div id="text-20" class="widget widget_text">           <div class="textwidget"><a rel="wp-video-lightbox" href="http://www.youtube.com/watch?v=QkiyOB6A5CI"><img width="303px" alt="Slide &amp; Spindle Repair at Gilman Precision" title="Slide &amp; Spindle Repair at Gilman Precision" src="http://www.gilmanprecision.com/wp-content/uploads/2014/03/video_thumb.png"></a>
<h3 style="margin-top:15px; text-align:center;"><a target="_blank" href="http://www.youtube.com/user/gilmanprecision">Follow us on <span style="margin-right:5px;" class="fa-stack"><i class="fa fa-circle fa-stack-2x"></i><i class="fa fa-youtube fa-stack-1x fa-inverse"></i></span>YouTube →</a></h3></div>
        </div>              </div>


            </section><!-- /#footer-widgets  -->


        <div class="col-full">

            <div id="credit" class="col-right">

                            <!--<p>Powered by <a href="http://www.wordpress.org">WordPress</a>. Designed by <a href="http://www.woothemes.com"><img src="http://www.gilmanprecision.com/wp-content/themes/definition/images/woothemes.png" width="74" height="19" alt="Woo Themes" /></a></p>-->

            </div>

            <div id="copyright" class="col-left">
            © 2014 Gilman Precision  |  <a href="http://www.gilmanprecision.com/privacy-policy"><span style="color:#003366"><strong>Privacy Policy</strong></span></a>  |  1230 Cheyenne Avenue, P.O. Box 5, Grafton, WI 53024-0005  |  +1-262-377-2434           </div>

        </div>

    </footer><!-- /#footer  -->

</div><!-- /#wrapper -->

<!-- Google Code for Remarketing Tag -->
<!--------------------------------------------------
Remarketing tags may not be associated with personally identifiable information or placed on pages related to sensitive categories. See more information and instructions on how to setup the tag on: http://google.com/ads/remarketingsetup
--------------------------------------------------->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 992904719;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/992904719/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-P6ZD4V"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-P6ZD4V');</script>
<!-- End Google Tag Manager -->
        <style>
                    .wptgg_loading{
                        background-image: url( 'http://www.gilmanprecision.com/wp-content/plugins/wp_trigger/img/ajax-loader.gif' ) ;
                        padding:0px 7px;
                        background-repeat: no-repeat;
                    }
                </style><script type='text/javascript'>var wptgg_ajaxurl = 'http://www.gilmanprecision.com/wp-admin/admin-ajax.php'</script><!--[if lt IE 9]>
<script src="http://www.gilmanprecision.com/wp-content/themes/definition/includes/js/respond.js"></script>
<![endif]-->
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-includes/js/comment-reply.min.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/contact-form-7/includes/js/jquery.form.min.js?ver=3.50.0-2014.02.05'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var _wpcf7 = {"loaderUrl":"http:\/\/www.gilmanprecision.com\/wp-content\/plugins\/contact-form-7\/images\/ajax-loader.gif","sending":"Sending ..."};
/* ]]> */
</script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/contact-form-7/includes/js/scripts.js?ver=3.7.2'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/wp_trigger/js/lib/jquery.json-2.3.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/wp_trigger/js/main.js?ver=4.1.4'></script>
<script type='text/javascript' src='http://www.gilmanprecision.com/wp-content/plugins/wp_trigger/js/pages/front/trigger_process.js?ver=4.1.4'></script>
</body>
</html>
