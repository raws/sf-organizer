<div class="page-header">
	<h1>Settings</h1>
</div>

<form class="form-horizontal" action="/settings" method="post">
	<fieldset>
		<legend>Sources</legend>
		
		<div class="control-group">
			<label class="control-label" for="from_folders">Folders</label>
			<div class="controls">
				<textarea id="from_folders" name="from[folders]" class="span8" rows="3" autofocus><?php echo(implode("\n", $settings["from"]["folders"])); ?></textarea>
				<span class="help-inline">Folders, one per line, in which to recursively search for files.</span>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="from_pattern">Pattern</label>
			<div class="controls">
				<input id="from_pattern" name="from[pattern]" class="span8" type="text" value="<?php echo($settings["from"]["pattern"]); ?>">
				<span class="help-inline">File names will be matched against this regular expression.</span>
			</div>
		</div>
	</fieldset>
	
	<fieldset>
		<legend>Destinations</legend>
		
		<div class="control-group">
			<label class="control-label" for="to_movies">Movies</label>
			<div class="controls">
				<input id="to_movies" name="to[movies]" class="span8" type="text" value="<?php echo($settings["to"]["movies"]); ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="to_tv">TV Shows</label>
			<div class="controls">
				<input id="to_tv" name="to[tv]" class="span8" type="text" value="<?php echo($settings["to"]["tv"]); ?>">
			</div>
		</div>
	</fieldset>
	
	<div class="form-actions">
		<input class="btn btn-primary" type="submit" value="Save changes">
	</div>
</form>
