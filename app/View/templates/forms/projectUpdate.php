<h2>Modification du project <?= /** @var array $projectData */ $projectData['projectName'] ?></h2>

<!--Formulaire de mis à jour des données de l'utilisateur-->
<form method="POST" action="/admin-update-project">
    <input type="hidden" name="id" value="<?php /** @var array $projectData */
    echo $projectData['id']?>">
    <br>
    <input type="file" name="files[]" multiple>
    <br>
    <label for="projectName">Nom du projet :</label>
    <input type="text" name="projectName" id="projectName" value="<?php echo $projectData['projectName']?>" required>
    <br>
    <label for="content">Description :</label>
    <textarea name="content" id="content" cols="30" rows="10" required><?php echo $projectData['content']?></textarea>
    <br>
    <input type="submit" value="Envoyer">
</form>