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

Flight::route("POST /", function() {
	$paths = $_POST["paths"];
	$names = $_POST["names"];
	$types = $_POST["types"];
	$files = array();
	
	for ($i = 0; $i < sizeof($paths); $i++) {
		$path = $paths[$i];
		$name = $names[$i];
		$type = $types[$i];
		$files[$path] = array("name" => $name, "type" => $type);
	}
	
	$result = array();
	$settings = Flight::get("organizer.settings");
	foreach ($files as $path => $options) {
		if (!file_exists($path)) {
			$result[$path] = array("status" => FALSE, "error" => "Original file does not exist");
			continue;
		}
		
		$pathinfo = pathinfo($options["name"]);
		
		if ($options["type"] === "movie") { $options["type"] = "movies"; }
		if (isset($settings["to"][$options["type"]])) {
			$link_dir = $settings["to"][$options["type"]];
		} else {
			$result[$path] = array("status" => FALSE, "error" => "Unrecognized media type", "type" => $options["type"]);
			continue;
		}
		
		if ($options["type"] === "movie") {
			$link_dir .= "/" . $pathinfo["filename"];
		} else if ($options["type"] === "tv") {
			if (preg_match("/^\s*(.*)\s*-\s*S(\d+)E\d+/", $options["name"], $matches)) {
				$show = trim($matches[1]);
				$season = intval($matches[2]);
				$link_dir .= "/" . $show . "/Season " . $season;
			} else {
				$result[$path] = array("status" => FALSE, "error" => "TV episode name is formatted incorrectly");
				continue;
			}
		}
		
		mkdir($link_dir, 0755, TRUE);
		
		$link_path = $link_dir . "/" . $options["name"];
		
		if (file_exists($link_path)) {
			$result[$path] = array("status" => FALSE, "error" => "Link target already exists");
			continue;
		}
		
		if (link($path, $link_path)) {
			$result[$path] = array("status" => TRUE, "path" => $link_path);
		} else {
			$result[$path] = array("status" => FALSE, "error" => "Could not create link", "path" => $link_path);
		}
	}
	
	header("Content-type: application/json");
	echo json_encode($result);
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
