<?php
/*
 * Note: The researcher_institution field was added in 2014
*/
$year = ( isset($_GET['year']) && is_numeric($_GET['year']) ? $_GET['year'] : 2011 );
?>
<html>
<head>
  <title>CASC Image Submissions</title>
  <style type="text/css">
    img, body, html { border: 0 none; }
    body, html { margin: 0; padding: 0}
    #wrapper { margin: 5px auto; width: 1100; border: 0px solid black }
    .desc { font-size: x-small; }
    .thumbnail {
        background-color: #eee;
        border: 1px solid #ccc;
        width: 250px;
        height: 400px;
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

$fields = array(
	"m.name as member_name",
	"m.organization as member_org",
	"i.researcher_name",
	"i.member_id",
	"i.description",
	"unix_timestamp(i.date_uploaded) uploaded");

if ( $year >= 2014 ) {
	$additionalFields = array(
		"i.researcher_institution",
		"i.viz_name",
		"i.viz_institution",
		"i.compute_name",
		"i.compute_institution");
	$fields = array_merge($fields, $additionalFields);
}

$query = "select " .
implode(", ", $fields) . "
from {$year}_images i
join {$year}_members m
    on i.member_id = m.member_id
order by i.date_uploaded asc
";

try {
  $sth = $dbh->prepare($query);
  $sth->execute();
} catch (PDOException $e) {
  $msg = "Query error in {$e->getFile()}:{$e->getLine()} {$e->getMessage()}";
  exit("<pre>$msg</pre>");
}

$imageNumber = 1;
while($row = $sth->fetch(PDO::FETCH_ASSOC)) {
    $thumb = 'images/'.$year.'/180x/'.$row['member_id'].'-'.$row['uploaded'].'.png';
    $full = 'images/'.$year.'/600x/'.$row['member_id'].'-'.$row['uploaded'].'.png';
    echo '<div class="thumbnail"><a rel="lightbox[casc]" title="'.htmlentities($row['description']).'" href="'.$full.'"><img src="'.$thumb.'"/></a>';
    echo '<p class="desc">Image #' . $imageNumber++ . '<br/>Researcher: '.$row['researcher_name'] . ( $year >= 2014 ? '<br/>'.$row['researcher_institution'] : "" );
    if ( $year >= 2014 ) {
      echo '<br/>Visualization: '.$row['viz_name'].'<br/>'.$row['viz_institution'];
      echo '<br/>Computation: '.$row['compute_name'].'<br/>'.$row['compute_institution']."<br/>";
    }
    echo '</p>';
    echo '<p class="desc"><b>' . $row['member_name'] . '</b>';
    if ( ! empty($row['member_org']) ) {
        echo ' (' . $row['member_org'] . ')';
    }
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
