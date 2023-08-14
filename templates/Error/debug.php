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
        <h1 class="display-4 text-center mb-5"><?=$title?></h1>
        <div class="card shadow">
            <div class="card-body">
                <pre class="text-danger"><?=$exception?></pre>
            </div>
        </div>
    </div>
</body>

</html>