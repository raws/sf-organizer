<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stratofortress &mdash; Organizer</title>
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.4.0/bootstrap.min.css">
    <link rel="stylesheet" href="assets/stylesheet.css" type="text/css" media="screen" charset="utf-8">
</head>
<body>
    <div class="topbar">
        <div class="topbar-inner">
            <div class="container">
                <a class="brand" href="/">Organizer</a>
                <ul class="nav">
                    <li><a href="/">Overview</a></li>
                    <li><a href="/settings">Settings</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="container">
        <?php if (Flight::get("organizer.settings") == null): ?>
            <div class="alert-message error">
                <p><strong>You need to configure Organizer!</strong> Visit <a href="/settings">settings</a> to do so.</p>
            </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <?=$error;?>
        <?php endif; ?>
        
        <?php if (isset($success)): ?>
            <?=$success;?>
        <?php endif; ?>
        
        <?=$content;?>
    </div>
</body>
</html>
