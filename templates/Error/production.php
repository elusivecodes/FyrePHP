<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$title?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/elusivecodes/frostui@latest/dist/frost-ui.min.css" />
</head>

<body class="d-flex vh-100 justify-content-center align-items-center text-bg-danger bg-gradient">
    <div class="container w-100">
        <div class="text-center">
            <h1 class="display-1 mb-5">
                <span style="font-size: 200%;"><?=$exception->getCode()?></span>
            </h1>
            <p class="display-6"><?=$title?></p>
        </div>
    </div>
</body>

</html>