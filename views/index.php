<ul id="file-list" class="file-list">
	<?php echo($entries); ?>
</ul>

<div class="form-actions form-actions-fixed-bottom" style="text-align:right">
	<div class="container">
		<div class="pull-left">
			<p><?php echo($entries_total); ?> files awaiting organization</p>
		</div>
		<div class="pull-right">
			<a id="organize-btn" class="btn btn-primary disabled" href="#">Organize</a>
		</div>
	</div>
</div>

<script src="assets/javascripts/sprintf-0.7-beta1.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	var Organizer = {
		setup: function() {
			// Highlight file basenames
			$(".file-path").each(function() {
				var pathElement = $(this);
				var groups = pathElement.text().match(/^(.+\/)([^\/]+)$/);
				if (groups !== null) {
					var path = groups[1], basename = groups[2];
					
					// Try to be helpful about reformatting TV episode file names
					var fileName = Organizer.guessEpisodeDetails(path, basename);
					
					pathElement.text(basename);
					pathElement.prev(".file-basename").text(fileName);
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
					li.removeClass("movie tv");
				} else {
					li.removeClass("movie tv").addClass(btnType);
					Organizer.selectElement(li.find(".file-basename")[0]);
				}

				$("#organize-btn").trigger("com.blolol.sf.update-organize-btn");
			});

			// Click row to edit file name
			$("ul.file-list > li").click(function() {
				var basename = $(this).find(".file-basename")[0];
				basename.focus();
			});
		},
		
		guessEpisodeDetails: function(path, basename) {
			var episode;
			if (episode = basename.match(/^(.+?)(?:\s*-\s*)?S?(\d+)?[Ex](\d+).*\.(\w+)$/i)) {
				/**
				 * episode[1] => Show name (e.g. "top_gear.")
				 * episode[2] => Season number (e.g. "01") (may be undefined)
				 * episode[3] => Episode number (e.g. "06")
				 * episode[4] => File extension (e.g. ".mkv")
				 **/
				var showName = episode[1].replace(/[\._]+/g, " ").replace(/\b[a-z](?!\b|he)/g, function(letter) {
					return letter.toUpperCase();
				}).trim();
				var seasonNumber = parseInt(episode[2], 10); // Explicit radix to make ensure decimal parsing
				var episodeNumber = parseInt(episode[3], 10);
				var fileExtension = episode[4].trim();
				
				if (!seasonNumber) {
					var season;
					if (season = path.match(/S(\d+)/i)) {
						seasonNumber = parseInt(season[1], 10);
					} else {
						return basename;
					}
				}
				
				return sprintf("%s - S%02dE%02d.%s", showName, seasonNumber, episodeNumber, fileExtension);
			}
			
			return basename;
		},
		
		getMediaTypeByClass: function(btn) {
			if (btn.hasClass("movie")) {
				return "movie";
			} else if (btn.hasClass("tv")) {
				return "tv";
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
		},
		
		serializeSelections: function() {
			var data = { "paths[]": [], "names[]": [], "types[]": [] };
			$("ul.file-list > li.movie, ul.file-list > li.tv").each(function(index) {
				var li = $(this);
				data["paths[]"][index] = li.find(".file-path").attr("title");
				data["names[]"][index] = li.find(".file-basename").text();
				data["types[]"][index] = Organizer.getMediaTypeByClass(li);
			});
			return data;
		}
	};
	
	$(function() {
		Organizer.setup();
		
		// Organize button functionality
		$("#organize-btn").bind("com.blolol.sf.update-organize-btn", function() {
			var count = $("ul.file-list > li.movie, ul.file-list > li.tv").length;
			var label = count === 0 ? "Organize" : "Organize " + count + " file";
			if (count > 1) { label += "s"; }
			$(this).text(label);
			if (count === 0) { $(this).addClass("disabled"); } else { $(this).removeClass("disabled"); }
		}).click(function(event) {
			event.preventDefault();
			if ($(this).hasClass("disabled")) { return; }
			var data = Organizer.serializeSelections();
			$.post("/", data, function(data, status) {
				console.log(data);
				$("#file-list").load("/entries", function() {
					Organizer.setup();
				});
			});
		});
	});
</script>
