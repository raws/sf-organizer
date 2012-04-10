<?php foreach($paths as $path => $details): ?>
	<li>
		<div class="file-name">
			<strong class="file-basename" contenteditable></strong>
			<small class="file-path" title="<?php echo($path); ?>"><?php echo($path); ?></small>
			<?php if ($details["size"] !== null && !empty($details["size"])): ?>
				<small title="<?php echo($details["size"]); ?> bytes">(<span class="file-size"><?php echo($details["size"]); ?></span>)</small>
			<?php endif; ?>
		</div>
		<div class="file-actions btn-group" data-toggle="buttons-radio">
			<a class="btn btn-small ignore">Ignore</a>
			<a class="btn btn-small delete">Delete</a>
			<a class="btn btn-small movie">Movie</a>
			<a class="btn btn-small tv">TV Show</a>
		</div>
	</li>
<?php endforeach; ?>
