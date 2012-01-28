<h1>Settings</h1>

<p>Make sure Organizer knows where to find and move your files!</p>

<form action="/settings" method="post">
    <fieldset>
        <div class="clearfix">
            <label for="paths.torrents">Torrents</label>
            <div class="input">
                <input id="paths.torrents" name="paths[torrents]" class="span8" type="text" value="<?=$settings->paths->torrents;?>">
            </div>
        </div>
        
        <div class="clearfix">
            <label for="paths.movies">Movies</label>
            <div class="input">
                <input id="paths.movies" name="paths[movies]" class="span8" type="text" value="<?=$settings->paths->movies;?>">
            </div>
        </div>
        
        <div class="clearfix">
            <label for="paths.tv">TV Shows</label>
            <div class="input">
                <input id="paths.tv" name="paths[tv]" class="span8" type="text" value="<?=$settings->paths->tv;?>">
            </div>
        </div>
        
        <div class="actions">
            <input class="btn primary" type="submit" value="Save changes">
        </div>
    </fieldset>
</form>
