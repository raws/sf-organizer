<?php
require "lib/flight/Flight.php";
require "lib/organizer/settings.php";

Flight::load_settings();

Flight::route("/", function() {
    Flight::render("index", null, "content");
    Flight::render("layout");
});

Flight::route("/settings", function() {
    if (($settings = Flight::get("organizer.settings")) == null) {
        $settings = array("paths" => array());
    }
    
    Flight::render("settings", array("settings" => $settings), "content");
    Flight::render("layout");
});

Flight::route("POST /settings", function() {
    if (($settings = Flight::get("organizer.settings")) == null) {
        $settings = array("paths" => array());
    }
    
    $settings["paths"]["torrents"] = $_POST["paths"]["torrents"];
    $settings["paths"]["movies"] = $_POST["paths"]["movies"];
    $settings["paths"]["tv"] = $_POST["paths"]["tv"];
    
    $result = Flight::write_settings($settings);
    if ($result !== false) {
        $settings = Flight::load_settings();
        Flight::render("_success", array("message" => "Changes saved."), "success");
    } else {
        Flight::render("_error", array("message" => "Changes could not be saved."), "error");
    }
    
    Flight::render("settings", array("settings" => $settings), "content");
    Flight::render("layout");
});

Flight::start();
?>
