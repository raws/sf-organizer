<div class="page-header">
	<h1>Organizer</h1>
</div>

<p>There are <?=sizeof($entries);?> files awaiting organization.</p>

<ul class="file-list">
	<?php foreach($entries as $path): ?>
		<li>
			<div class="file-name">
				<strong class="file-basename" contenteditable></strong>
				<small class="file-path" title="<?php echo($path); ?>"><?php echo($path); ?></small>
			</div>
			<div class="file-actions btn-group" data-toggle="buttons-radio">
				<a class="btn btn-small movie">Movie</a>
				<a class="btn btn-small tv-show">TV Show</a>
			</div>
		</li>
	<?php endforeach; ?>
</ul>

<script type="text/javascript" charset="utf-8">
	var Organizer = {
		"getMediaTypeByClass": function(btn) {
			if (btn.hasClass("movie")) {
				return "movie";
			} else if (btn.hasClass("tv-show")) {
				return "tv-show";
			}
			return null;
		}
	};
	
	$(function() {
		// Highlight file basenames
		$(".file-path").each(function() {
			var pathElement = $(this);
			var groups = pathElement.text().match(/^(.+\/)([^\/]+)$/);
			if (groups !== null) {
				var path = groups[1], basename = groups[2];
				pathElement.text(basename);
				pathElement.prev(".file-basename").text(basename);
			}
		});
		
		// Enable radio button groups
		$("ul.file-list .file-actions").button();
		$("ul.file-list .file-actions").on("click", ".btn", function(event) {
			var btn = $(this), btnType = Organizer.getMediaTypeByClass(btn);
			var li = btn.parents("li"), liType = Organizer.getMediaTypeByClass(li);
			
			if (liType === btnType) {
				/* Prevent Bootstrap button click event handler from firing and
				 * re-adding .active class to our button
				 */
				event.stopPropagation();
				
				btn.removeClass("active");
				li.removeClass("movie tv-show");
			} else {
				li.removeClass("movie tv-show").addClass(btnType);
			}
		});
		
		// Click row to edit file name
		$("ul.file-list > li").click(function() {
			$(this).find(".file-basename").focus();
		});
	});
</script>
