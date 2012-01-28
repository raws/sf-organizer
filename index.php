<?php
require "../lib/flight/Flight.php";

Flight::route("/", function() {
    Flight::render("index", null, "content");
    Flight::render("layout");
});

Flight::start();
?>
