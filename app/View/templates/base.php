<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Portfolio v2</title>
</head>
<body>
    <?php include 'partials/_header.php'; ?>
    <main>
        <!--Message d'erreur ou de succès-->
        <?php if (isset($error)): ?>
            <div><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <div><?php echo $success; ?></div>
        <?php endif; ?>

        <?php /** @var string $content */ ?>
        <?= $content ?>
    </main>
    <?php include 'partials/_footer.php'; ?>
</body>
</html>