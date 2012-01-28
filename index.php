<?php

include("lib/fitzgerald.php");

class Organizer extends Fitzgerald {
	
	public function get_index() {
	    $unorganized = $this->get_unorganized();
		return $this->render("index", compact("unorganized"));
	}
	
	private function get_unorganized() {
	    return glob($this->options->torrents . "/*/*.mkv");
	}
	
}

$app = new Organizer(array(
    "mountPoint" => "/~ross/organizer",
    "torrents" => "/volume1/torrents",
    "layout" => "layout"
));

$app->get("/", "get_index");

$app->run();

?>
