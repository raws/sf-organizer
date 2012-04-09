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
			$result[$path] = array("status" => "error", "error" => "Original file does not exist");
			continue;
		}
		
		if ($options["type"] === "movie") { $options["type"] = "movies"; }
		if (isset($settings["to"][$options["type"]])) {
			$dest_dir = $settings["to"][$options["type"]];
		} else {
			$result[$path] = array("status" => "error", "error" => "Unrecognized media type", "type" => $options["type"]);
			continue;
		}
		
		$pathinfo = pathinfo($options["name"]);
		if ($options["type"] === "movies") {
			$dest_dir .= "/" . $pathinfo["filename"];
		} else if ($options["type"] === "tv") {
			if (preg_match("/^\s*(.*)\s*-\s*S(\d+)E\d+/", $options["name"], $matches)) {
				$show = trim($matches[1]);
				$season = intval($matches[2]);
				$dest_dir .= "/" . $show . "/Season " . $season;
			} else {
				$result[$path] = array("status" => "error", "error" => "TV episode name is formatted incorrectly");
				continue;
			}
		}
		
		mkdir($dest_dir, 0755, TRUE);
		
		$dest_path = $dest_dir . "/" . $options["name"];
		
		if (file_exists($dest_path)) {
			$result[$path] = array("status" => "error", "error" => "New file already exists", "path" => $dest_path);
			continue;
		}
		
		if (rename($path, $dest_path)) {
			$result[$path] = array("status" => "success", "path" => $dest_path);
		} else {
			$result[$path] = array("status" => "error", "error" => "Could not move file", "path" => $dest_path);
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
