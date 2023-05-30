<h1>Page d'accueil</h1>

<h2>Liste des utilisateurs</h2>
<?php if (!empty($users)) {
    foreach ($users as $user): ?>
    <ul>
        <li><?= $user['username']; ?></li>
    </ul>
    <?php endforeach;
} ?>
<?php if (empty($users)): ?>
    <p>Aucun utilisateur enregistré</p>
<?php endif; ?>

<h2>Formulaire d'inscription</h2>
<form method="post" action="">
    <label for="username">Nom d'utilisateur:</label>
    <input type="text" id="username" name="username" required>
    <br><br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    <br><br>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>
    <br><br>

    <input type="submit" value="Envoyer">
</form>