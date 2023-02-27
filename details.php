<?php
$key = 'abcdefghijklmnop';
require 'header.php';

// Check if ID parameter is set
if (isset($_GET['id'])) {
  // Decode the base64 encoded encrypted ID
  $encodedId = $_GET['id'];
  $encryptedId = base64_decode($encodedId);
  // Decrypt the encrypted ID using AES-256-CBC
  $decryptedId = openssl_decrypt($encryptedId, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, '1234567890123456');

  // Use the decrypted ID in the query
  $query = "SELECT * FROM aliens WHERE id = $decryptedId AND approved = 1";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);

  if ($row) {
    // Display sighting details
    echo '<div class="col">';
    echo '<h1>Sighting details</h1>';

    // Display location, date and time, and description
    echo "<p class='fw-bold'>Location:</p>";
    echo "<p>{$row['location']}</p>";
    echo "<p class='fw-bold'>Date & Time:</p>";
    echo "<p>". formatDate($row['date']) ." - {$row['time']}</p>";
    echo "<p class='fw-bold'>Description:</p>";
    echo "<p>{$row['message']}</p>";

    // Display image
    $imagePath = 'assets/images/' . $row['alienImg'];
    if (!empty($row['alienImg']) && file_exists($imagePath)) {
        echo "<img src='$imagePath' alt='Alien image' class='img-fluid'>";
    } else {
        echo "<img src='assets/images/default-image.jpg' alt='Default image' class='img-fluid'>";
    }    

    // Add social media sharing buttons
    echo '<div class="m-1 text-center">';
    echo '<span class="fw-bold">Share on:</span> <a href="https://www.facebook.com/sharer.php?u='. urlencode($_SERVER['REQUEST_URI']) .'" target="_blank"><i class="fab fa-facebook fa-lg me-3"></i></a>';
    echo '<a href="https://twitter.com/intent/tweet?url='. urlencode($_SERVER['REQUEST_URI']) .'" target="_blank"><i class="fab fa-twitter fa-lg"></i></a>';
    echo '</div>';
    echo '</div>';

  } else {
    // Sighting not found
    echo '<div class="col">';
    echo '<h1>Sighting not found</h1>';
    echo '<p>The sighting you requested could not be found.</p>';
    echo '</div>';
  }

} else {
  // ID parameter not set
  echo '<div class="col">';
  echo '<h1>Invalid request</h1>';
  echo '<p>The request is missing a valid ID parameter.</p>';
  echo '</div>';
}

require 'footer.php';
