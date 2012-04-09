<?php
namespace organizer;

class Organizer {
	private $settings;
	
	public function __construct($settings) {
		$this->settings = $settings;
	}
	
	public function organize($paths) {
		$result = array();
		
		/*
		 * Iterate over each path and attempt to move it to its new location,
		 * based on the settings this Organizer was initialized with.
		 */
		foreach ($paths as $path => $options) {
			// Make sure the original file exists and is able to be (re)moved
			if (!is_writable($path)) {
				$result[$path] = array(
					"status" => "error",
					"error" => "Original file does not exist or is not writable"
				);
				continue;
			}
			
			// Validate and act on media type
			if ($options["type"] === "movie") { $options["type"] = "movies"; }
			if (isset($this->settings["to"][$options["type"]])) {
				$dest_dir = $this->settings["to"][$options["type"]];
			} else if ($options["type"] === "delete") {
				if (unlink($path)) {
					$result[$path] = array(
						"status" => "success",
						"deleted" => TRUE
					);
					continue;
				} else {
					$result[$path] = array(
						"status" => "error",
						"error" => "Original file could not be deleted",
						"deleted" => FALSE
					);
					continue;
				}
			} else {
				$result[$path] = array(
					"status" => "error",
					"error" => "Media type is unrecognized",
					"type" => $options["type"]
				);
				continue;
			}
			
			// Construct destination directory
			$pathinfo = pathinfo($options["name"]);
			if ($options["type"] === "movies") {
				$dest_dir .= "/" . $pathinfo["filename"];
			} else if ($options["type"] === "tv") {
				if (preg_match("/^\s*(.*)\s*-\s*S(\d+)E\d+/", $options["name"], $matches)) {
					$show = trim($matches[1]);
					$season = intval($matches[2]);
					$dest_dir .= "/" . $show . "/Season " . $season;
				} else {
					$result[$path] = array(
						"status" => "error",
						"error" => "TV episode name is formatted incorrectly"
					);
					continue;
				}
			}
			
			mkdir($dest_dir, 0755, TRUE); // May return FALSE if directory already exists
			
			if (!is_writable($dest_dir)) {
				$result[$path] = array(
					"status" => "error",
					"error" => "Destination folder cannot be written to",
					"path" => $dest_dir
				);
				continue;
			}
			
			// Construct destination path
			$dest_path = $dest_dir . "/" . $options["name"];
			
			if (file_exists($dest_path)) {
				$result[$path] = array(
					"status" => "error",
					"error" => "Destination file already exists",
					"path" => $dest_path
				);
				continue;
			}
			
			// Attempt to move original file to destination
			if (rename($path, $dest_path)) {
				$result[$path] = array(
					"status" => "success",
					"path" => $dest_path
				);
			} else {
				$result[$path] = array(
					"status" => "error",
					"error" => "Could not move original file to destination",
					"path" => $dest_path
				);
			}
		}
		
		return $result;
	}
}
?>