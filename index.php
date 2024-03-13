
<?php
session_start();

if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 'home';
}
if(isset($_SESSION['user'])){
    $user = $_SESSION['user'];
}else{
    $user = 'gast';
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    <link rel="canonical" href="https://getbootstrap.com/docs/4.0/examples/blog/">
    <link rel="icon" href="/docs/4.0/assets/img/favicons/favicon.ico">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    
<?php
include 'includes/nav.inc.php';     




include 'includes/' . $page . '.inc.php';
?>

</body>
</ht    
