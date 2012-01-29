<h1>Settings</h1>

<form action="/settings" method="post">
    <fieldset>
        <legend>Sources</legend>
        
        <div class="clearfix">
            <label for="from[folders]">Folders</label>
            <div class="input">
                <textarea id="from_folders" name="from[folders]" class="span8" rows="3"><?=implode("\n", $settings["from"]["folders"]);?></textarea>
                <span class="help-block">Folders, one per line, in which to recursively search for files.</span>
            </div>
        </div>
        
        <div class="clearfix">
            <label for="from[pattern]">Pattern</label>
            <div class="input">
                <input id="from_pattern" name="from[pattern]" class="span8" type="text" value="<?=$settings["from"]["pattern"];?>">
                <span class="help-block">File names will be matched against this regular expression.</span>
            </div>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Destinations</legend>
        
        <div class="clearfix">
            <label for="to[movies]">Movies</label>
            <div class="input">
                <input id="to_movies" name="to[movies]" class="span8" type="text" value="<?=$settings["to"]["movies"];?>">
            </div>
        </div>
        
        <div class="clearfix">
            <label for="to[tv]">TV Shows</label>
            <div class="input">
                <input id="to_tv" name="to[tv]" class="span8" type="text" value="<?=$settings["to"]["tv"];?>">
            </div>
        </div>
    </fieldset>
    
    <div class="actions">
        <input class="btn primary" type="submit" value="Save changes">
    </div>
</form>
