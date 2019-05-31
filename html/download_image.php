<?php
// Download a raw image

if ( ! isset($_GET['year']) || ! isset($_GET['name']) ) {
    exit("Invalid image specification");
}

$year = trim($_GET['year']);
$name = trim($_GET['name']);

// Ensure the filename falls under the expected directory and thwart injection attacks

$filename = realpath("images/$year/raw/$name");
if ( 0 !== strpos($filename, sprintf("%s/%s", getcwd(), 'images')) || ! is_file($filename) ) {
    exit("Invalid image specification");
}

$imageInfo = getimagesize($filename);
$mimeType = (array_key_exists('mime', $imageInfo) && ! empty($imageInfo['mime']) ? $imageInfo['mime'] : 'application/octet-stream');

header('Content-type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . $name . '"');
readfile($filename);
