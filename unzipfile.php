<?php
$unzip = new ZipArchive;
$out = $unzip->open('resources.zip');
if ($out === TRUE) {
  $unzip->extractTo(getcwd());
  $unzip->close();
  echo 'File unzipped';
} else {
  echo 'Something went wrong?';
}
?>