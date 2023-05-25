<h1>Page d'accueil</h1>

<?php /** @var array $users */ ?>
<?php foreach ($users as $user): ?>
    <p>Nom: <?php echo $user['username']; ?></p>
    <p>Email: <?php echo $user['email']; ?></p>
<?php endforeach; ?>

