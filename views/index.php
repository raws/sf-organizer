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

<div class="form-actions form-actions-fixed-bottom" style="text-align:right">
	<div class="container">
		<div class="pull-left">
			<p><?=sizeof($entries);?> files awaiting organization</p>
		</div>
		<div class="pull-right">
			<a id="organize-btn" class="btn btn-primary disabled" href="#">Organize</a>
		</div>
	</div>
</div>

<script type="text/javascript" charset="utf-8">
	var Organizer = {
		getMediaTypeByClass: function(btn) {
			if (btn.hasClass("movie")) {
				return "movie";
			} else if (btn.hasClass("tv-show")) {
				return "tv-show";
			}
			return null;
		},
		
		selectElement: function(element) {
			if (window.getSelection) {
				var sel = window.getSelection();
				sel.removeAllRanges();
				var range = document.createRange();
				range.selectNodeContents(element);
				sel.addRange(range);
			} else if (document.selection) {
				var textRange = document.body.createTextRange();
				textRange.moveToElementText(element);
				textRange.select();
			}
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
				Organizer.selectElement(li.find(".file-basename")[0]);
			}
			
			$("#organize-btn").trigger("com.blolol.sf.update-organize-btn");
		});
		
		// Click row to edit file name
		$("ul.file-list > li").click(function() {
			var basename = $(this).find(".file-basename")[0];
			basename.focus();
		});
		
		// Organize button functionality
		$("#organize-btn").bind("com.blolol.sf.update-organize-btn", function() {
			var count = $("ul.file-list > li.movie, ul.file-list > li.tv-show").length;
			var label = count === 0 ? "Organize" : "Organize " + count + " file";
			if (count > 1) { label += "s"; }
			$(this).text(label);
			if (count === 0) { $(this).addClass("disabled"); } else { $(this).removeClass("disabled"); }
		}).click(function(event) {
			event.preventDefault();
			if ($(this).hasClass("disabled")) { return; }
			alert("Not yet implemented!");
		});
	});
</script>
