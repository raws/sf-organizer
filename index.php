<?php

include("lib/fitzgerald.php");

class Organizer extends Fitzgerald {
	
	public function get_index() {
		return $this->render("index");
	}
	
}

$app = new Organizer(array(
    "mountPoint" => "/~ross/organizer",
    "layout" => "layout"
));

$app->get("/", "get_index");

$app->run();

?>
