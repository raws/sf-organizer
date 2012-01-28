<?php
require "lib/flight/Flight.php";

if (($f = file_get_contents("config/settings.json")) !== false) {
    $config = json_decode($f);
    Flight::set("organizer.settings", $config);
}

Flight::route("/", function() {
    Flight::render("index", null, "content");
    Flight::render("layout");
});

Flight::route("/settings", function() {
    if (($settings = Flight::get("organizer.settings")) == null) {
        $settings = array();
    }
    
    Flight::render("settings", array("settings" => $settings), "content");
    Flight::render("layout");
});

Flight::route("POST /settings", function() {
    if (($settings = Flight::get("organizer.settings")) == null) {
        $settings = array();
    }
    
    $settings->paths->torrents = $_POST["paths"]["torrents"];
    $settings->paths->movies = $_POST["paths"]["movies"];
    $settings->paths->tv = $_POST["paths"]["tv"];
    Flight::set("organizer.settings", $settings);
    
    $result = file_put_contents("config/settings.json", json_encode($settings));
    if ($result !== false) {
        Flight::render("_success", array("message" => "Changes saved."), "success");
    } else {
        Flight::render("_error", array("message" => "Changes could not be saved."), "error");
    }
    
    Flight::render("settings", array("settings" => $settings), "content");
    Flight::render("layout");
});

Flight::start();
?>
