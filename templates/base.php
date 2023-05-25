<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Portfolio v2</title>
</head>
<body>
    <header>
        <?php include '../templates/partials/_header.php'; ?>
    </header>
    <main>
        <?php /** @var string $content */ ?>
        <?= $content ?>
    </main>
    <footer>
        <?php include '../templates/partials/_footer.php'; ?>
    </footer>
</body>
</html>