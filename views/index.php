<div class="page-header">
	<h1>Organizer</h1>
</div>

<p>There are <?=sizeof($entries);?> files awaiting organization:</p>

<ul>
	<?php foreach($entries as $path): ?>
		<li><?php echo($path); ?></li>
	<?php endforeach; ?>
</ul>
