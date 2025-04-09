<?
require_once __DIR__ . '/classes/db.php';
require_once __DIR__ . '/config.php';
$DB = new WebDatabase($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
?>
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/favicon_transp.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Замены КМПО РАНХиГС</title>
    <link rel="stylesheet" href="css/style.css">
    <!-- Font-Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Excel/PDF -->
    <script src="js/lib/xlsx.min.lib.js"></script>
    <script src="js/lib/jspdf.min.lib.js"></script>
    <script src="js/lib/jspdf.plugin.autotable.min.js"></script>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
    (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
    m[i].l=1*new Date();
    for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
    k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
    (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

    ym(100881196, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true
    });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/100881196" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->

</head>