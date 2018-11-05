<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Server Error</title>
    <link rel="stylesheet" href="<?=site_url('assets/css/frost-ui.min.css')?>">
</head>
<body>
    <div class="container p-5">
        <h1 class="display-4">Server Error</h1>
        <p class="italic">The server has encountered an error.</p>
        <div class="msg msg-danger py-3">
            <?=$message?>
        </div>
    </div>
</body>
</html>