<?php
$year = ( isset($_GET['year']) && is_numeric($_GET['year']) ? $_GET['year'] : 2011 );
?>
<html>
<head>
  <title>CASC Image Submissions</title>
  <style type="text/css">
    img, body, html { border: 0 none; }
    body, html { margin: 0; padding: 0}
    #wrapper { margin: 5px auto; width: 1000; border: 0px solid black }
    .desc { font-size: x-small; }
    .thumbnail {
        background-color: #eee;
        border: 1px solid #ccc;
        width: 180px;
        height: 220px;
        float: left;
        margin-bottom: 10px;
        margin-right: 10px;
        padding: 3px 3px 8px;
        text-align: center;
    }
    #outside-page {
        background: url("images/bg_page.jpg") repeat-x scroll left top transparent;
    }
  </style>
  <link rel="stylesheet" href="css/lightbox.css" type="text/css" media="screen" />
  <script type="text/javascript" src="js/lightbox/prototype.js"></script>
  <script type="text/javascript" src="js/lightbox/scriptaculous.js?load=effects,builder"></script>
  <script type="text/javascript" src="js/lightbox/lightbox.js"></script>
</head>
<body>
<div id="outside-page">
<div align="center" id="header"> <a href="http://www.casc.org/"><img src="images/logo.png"/></a></div>

<div align="center" id="wrapper">
<h2><?= $year ?> Image Submissions</h2>
<p><a href="/">Back</a></p>

<?php
$config = parse_ini_file("../config/casc.ini", true);
$dbHost = $config['database']['host'];
$dbName = $config['database']['name'];
$dbUser = $config['database']['user'];
$dbPasswd = $config['database']['password'];

$dbh = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPasswd);
$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
$year = trim($dbh->quote($year), "'");

$query = "
select 
    m.organization,
    i.researcher_name,
    i.member_id,
    i.description,
    i.name,
    i.email,
    unix_timestamp(i.date_uploaded) uploaded
from {$year}_images i
join {$year}_members m
    on i.member_id = m.member_id
";

try {
  $sth = $dbh->prepare($query);
  $sth->execute();
} catch (PDOException $e) {
  $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
  exit("<pre>$msg</pre>");
}

while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $thumb = 'images/'.$year.'/180x/'.$row['member_id'].'-'.$row['uploaded'].'.png';
    $full = 'images/'.$year.'/600x/'.$row['member_id'].'-'.$row['uploaded'].'.png';
    echo '<div class="thumbnail"><a rel="lightbox[casc]" title="'.htmlentities($row['description']).'" href="'.$full.'"><img src="'.$thumb.'"/></a>';
    echo '<p class="desc">'.$row['researcher_name'].'<br/>'.$row['organization'].'</p>';
    echo '</div>';
}
?>

</div>

<div align="center" style="clear: both">
<img src="/images/casc_footer.jpg">
<div>
</div>
</body>
</html>
