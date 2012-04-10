<div id="loading" style="display:none;">
	<div class="spinner">
		<div class="bar1"></div>
		<div class="bar2"></div>
		<div class="bar3"></div>
		<div class="bar4"></div>
		<div class="bar5"></div>
		<div class="bar6"></div>
		<div class="bar7"></div>
		<div class="bar8"></div>
		<div class="bar9"></div>
		<div class="bar10"></div>
		<div class="bar11"></div>
		<div class="bar12"></div>
	</div>
</div>

<ul id="file-list" class="file-list"></ul>

<div class="form-actions form-actions-fixed-bottom" style="text-align:right">
	<div class="container">
		<div class="pull-left">
			<p style="display:none;"><span id="file-count"></span> files awaiting organization</p>
		</div>
		<div class="pull-right">
			<a id="organize-btn" class="btn btn-primary disabled" href="#">Organize</a>
		</div>
	</div>
</div>

<script src="assets/javascripts/sprintf-0.7-beta1.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
	var Organizer = {
		loadEntries: function() {
			var fileList = $("#file-list");
			fileList.slideUp(function() {
				fileList.empty();
				$("#organize-btn").trigger("com.blolol.sf.update-organize-btn");
				
				$("#file-count").parent().fadeOut();

				var timer = setTimeout(function() {
					$("#loading").fadeIn();
				}, 1000);

				$.ajax("/entries", {
					success: function(data) {
						clearTimeout(timer);
						$("#loading").fadeOut(function() {
							fileList.html(data);
							Organizer.setup();
							fileList.slideDown();
							$("#file-count").text(fileList.children("li").length).parent().fadeIn();
						});
					}
				});
			});
		},
		
		setup: function() {
			// Highlight file basenames
			$(".file-path").each(function() {
				var pathElement = $(this);
				var groups = pathElement.text().match(/^(.+\/)([^\/]+)$/);
				if (groups !== null) {
					var path = groups[1], basename = groups[2];
					
					// Try to be helpful about reformatting movie and TV episode file names
					var fileName = Organizer.guessEpisodeDetails(path, basename) || Organizer.guessMovieDetails(path, basename) || basename;
					
					pathElement.text(basename);
					pathElement.prev(".file-basename").text(fileName);
				}
			});
			
			// Make file sizes human readable
			$(".file-size").text(function(index, text) {
				return Organizer.humanReadableFileSize(text);
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
					li.removeClass("delete ignore movie tv");
				} else {
					li.removeClass("delete ignore movie tv").addClass(btnType);
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
		
		guessMovieDetails: function(path, basename) {
			var movie;
			if (movie = basename.match(/^(.+?)\(?(\d{4})\)?.*\.(\w+)$/)) {
				/**
				 * movie[1] => Title (e.g. "The.Italian.Job.")
				 * movie[2] => Year (e.g. "1968")
				 * movie[3] => File extension (e.g. ".mkv")
				 **/
				var title = Organizer.normalizeTitle(movie[1]);
				var year = parseInt(movie[2], 10); // Explicit radix to ensure decimal parsing
				var fileExtension = movie[3].trim();
				return sprintf("%s (%d).%s", title, year, fileExtension);
			}
		},
		
		guessEpisodeDetails: function(path, basename) {
			if (!(/[ex]\d{1,2}(?!\d)/i.test(basename))) { return; }
			var episode;
			if (episode = basename.match(/^(.+?)(?:\s*-\s*)?S?(\d+)?\.?[ex](\d+).*\.(\w+)$/i)) {
				/**
				 * episode[1] => Show name (e.g. "top_gear.")
				 * episode[2] => Season number (e.g. "01") (may be undefined)
				 * episode[3] => Episode number (e.g. "06")
				 * episode[4] => File extension (e.g. ".mkv")
				 **/
				var showName = Organizer.normalizeTitle(episode[1]);
				var seasonNumber = parseInt(episode[2], 10); // Explicit radix to ensure decimal parsing
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
		},
		
		normalizeTitle: function(title) {
			return title.replace(/[\._]+/g, " ").replace(/\b[a-z]/g, function(letter) {
				return letter.toUpperCase();
			}).replace(/\b(?:a|by|in|of|to|the|and)\b/ig, function(pronoun) {
				return pronoun.toLowerCase();
			}).replace(/^\w/, function(letter) {
				return letter.toUpperCase();
			}).trim();
		},
		
		getMediaTypeByClass: function(btn) {
			if (btn.hasClass("delete")) {
				return "delete";
			} else if (btn.hasClass("ignore")) {
				return "ignore";
			} else if (btn.hasClass("movie")) {
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
			$("li.delete, li.ignore, li.movie, li.tv").each(function(index) {
				var li = $(this);
				data["paths[]"][index] = li.find(".file-path").attr("title");
				data["names[]"][index] = li.find(".file-basename").text();
				data["types[]"][index] = Organizer.getMediaTypeByClass(li);
			});
			return data;
		},
		
		humanReadableFileSize: function(size) {
			size = parseInt(size, 10);
			var suffixes = ["B", "KB", "MB", "GB", "TB", "PB"], i = 0;
			for (i = 0; size >= 1024 && i < (suffixes.length - 1); i++) { size /= 1024; }
			return (Math.round(size * 10) / 10) + " " + suffixes[i];
		}
	};
	
	$(function() {
		// Organize button functionality
		$("#organize-btn").bind("com.blolol.sf.update-organize-btn", function() {
			var count = $("li.delete, li.ignore, li.movie, li.tv").length;
			var label = count === 0 ? "Organize" : "Organize " + count + " file";
			if (count > 1) { label += "s"; }
			$(this).text(label);
			if (count === 0) { $(this).addClass("disabled"); } else { $(this).removeClass("disabled"); }
		}).click(function(event) {
			event.preventDefault();
			if ($(this).hasClass("disabled")) { return; }
			var data = Organizer.serializeSelections();
			$.post("/", data, function(data, status) {
				Organizer.loadEntries();
			});
		});
		
		Organizer.loadEntries();
	});
</script>
