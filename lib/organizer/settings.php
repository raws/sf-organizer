<?php
Flight::set("organizer.settings.path", "config/settings.json");

Flight::map("read_settings", function() {
	if (($settings = file_get_contents(Flight::get("organizer.settings.path"))) !== false) {
		return json_decode($settings, true);
	} else {
		return false;
	}
});

Flight::map("load_settings", function() {
	$settings = Flight::read_settings();
	Flight::set("organizer.settings", $settings);
	return $settings;
});

Flight::map("setting", function($path) {
	$settings = Flight::get("organizer.settings");
	foreach (explode(".", $path) as $key) {
		$settings = $settings[$key];
	}
	return $settings;
});

Flight::map("write_settings", function($settings) {
	$settings = json_encode($settings);
	return file_put_contents(Flight::get("organizer.settings.path"), $settings);
});

Flight::map("dump_settings", function() {
	return Flight::write_settings(Flight::get("organizer.settings"));
});
?>
