<?php
require "lib/flight/Flight.php";

require "lib/organizer/settings.php";
Flight::load_settings();

require "lib/organizer/explorer.php";
Flight::register("explorer", "organizer\Explorer", array(
	Flight::setting("from.folders"),
	Flight::setting("from.pattern")
));

Flight::route("/", function() {
	Flight::render("index", array("entries" => Flight::explorer()->get_entries()), "content");
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
	
	$settings["from"]["folders"] = explode("\n", trim($_POST["from"]["folders"]));
	$settings["from"]["pattern"] = $_POST["from"]["pattern"];
	$settings["to"]["movies"] = rtrim(trim($_POST["to"]["movies"]), "/");
	$settings["to"]["tv"] = rtrim(trim($_POST["to"]["tv"]), "/");
	
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
