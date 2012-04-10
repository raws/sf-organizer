<?php foreach($paths as $path): ?>
	<li>
		<div class="file-name">
			<strong class="file-basename" contenteditable></strong>
			<small class="file-path" title="<?php echo($path); ?>"><?php echo($path); ?></small>
		</div>
		<div class="file-actions btn-group" data-toggle="buttons-radio">
			<a class="btn btn-small delete">Delete</a>
			<a class="btn btn-small ignore">Ignore</a>
			<a class="btn btn-small movie">Movie</a>
			<a class="btn btn-small tv">TV Show</a>
		</div>
	</li>
<?php endforeach; ?>
