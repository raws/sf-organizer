<?php

include("lib/fitzgerald.php");

class Organizer extends Fitzgerald {
	
	public function get_index() {
		return $this->render("index");
	}
	
}

$app = new Organizer(array("mountPoint" => "/~ross/organizer"));
$app->get("/", "get_index");
$app->run();

?>
