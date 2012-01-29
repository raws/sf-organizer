<h1>Settings</h1>

<form action="/settings" method="post">
    <fieldset>
        <legend>Sources</legend>
        
        <div class="clearfix">
            <label for="paths[sources]">Sources</label>
            <div class="input">
                <textarea id="paths[sources]" name="paths[sources]" class="span8" rows="5"><?=implode("\n", $settings["paths"]["sources"]);?></textarea>
                <span class="help-block">One path per line. Standard glob patterns are expanded.</span>
            </div>
        </div>
    </fieldset>
    
    <fieldset>
        <legend>Destinations</legend>
        
        <div class="clearfix">
            <label for="paths[movies]">Movies</label>
            <div class="input">
                <input id="paths[movies]" name="paths[movies]" class="span8" type="text" value="<?=$settings["paths"]["movies"];?>">
            </div>
        </div>
        
        <div class="clearfix">
            <label for="paths[tv]">TV Shows</label>
            <div class="input">
                <input id="paths[tv]" name="paths[tv]" class="span8" type="text" value="<?=$settings["paths"]["tv"];?>">
            </div>
        </div>
    </fieldset>
    
    <div class="actions">
        <input class="btn primary" type="submit" value="Save changes">
    </div>
</form>
