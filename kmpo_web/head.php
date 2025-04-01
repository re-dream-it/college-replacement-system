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
    <!-- Font-Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Excel -->
    <script src="https://cdn.sheetjs.com/xlsx-0.19.3/package/dist/xlsx.full.min.js"></script>
</head>