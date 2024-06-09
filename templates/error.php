<?php
    $code = $exception->getCode();

    if (config('App.debug')) {
        $title = $exception->getMessage();
    } else if ($code >= 400 && $code < 500) {
        $title = 'Page Not Found';
    } else {
        $title = 'Something Went Wrong';
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/elusivecodes/frostui@latest/dist/frost-ui.min.css" />
</head>

<body class="d-flex vh-100 justify-content-center align-items-center text-bg-warning bg-gradient">
    <div class="container w-100">
        <div class="text-center">
            <h1 class="display-1 fw-bold mb-5">
                <span style="font-size: 250%;"><?=$code?></span>
            </h1>
            <p class="display-6"><?=$title?></p>
        </div>
<?php if (config('App.debug')) { ?>
        <div class="card shadow mt-5">
            <div class="card-body">
                <pre class="text-danger"><?=$exception?></pre>
            </div>
        </div>
<?php } ?>
    </div>
</body>

</html>