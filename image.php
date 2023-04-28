<?php
$filename = $_GET['filename'];
if (file_exists($filename)) {
    header('Content-Type: image/jpeg');
    readfile($filename);
} else {
    echo "Image not found";
}
?>