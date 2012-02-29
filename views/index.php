<h1>Organizer</h1>

<h2><?=sizeof($entries);?> entries</h2>
<ul>
    <?php foreach($entries as $path): ?>
        <li><?php echo($path); ?></li>
    <?php endforeach; ?>
</ul>
