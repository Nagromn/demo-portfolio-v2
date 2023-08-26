<h2>Modification des informations personnelles</h2>

<!--Formulaire de mis à jour des données de l'utilisateur-->
<form method="POST" action="/admin-update-user">
    <input type="hidden" name="id" value="<?php /** @var array $userData */
    echo $userData['id']?>">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" value="<?php echo $userData['email']?>" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="submit" value="Envoyer">
</form>