<h2>Connexion</h2>

<!--Formulaire de connexion-->
<form method="POST" action="/admin-login">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required>
    <br>
    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="submit" value="Se connecter">
</form>

<br>

<!--Lien pour se déconnecter-->
<a href="/admin-logout">Se déconnecter</a>