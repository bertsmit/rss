<?php
require '../src/claviska/SimpleImage.php';

// Ignore notices
error_reporting(E_ALL & ~E_NOTICE);

try {
  // Create a new SimpleImage object
  $image = new \claviska\SimpleImage();

  // Manipulate it
  $image
    ->fromFile('parrot.jpg') 
    ->autoOrient();

} catch(Exception $err) {
  // Handle errors
  echo $err->getMessage();
}
