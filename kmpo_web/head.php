<?
require_once __DIR__ . '/classes/db.php';
require_once __DIR__ . '/config.php';
$DB = new WebDatabase($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
?>
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="images/logo_bw.ico" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Замены КМПО РАНХиГС</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Библиотека для экспорта в Excel -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>

    <!-- Библиотека для экспорта в PDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.24/jspdf.plugin.autotable.min.js"></script>
</head>