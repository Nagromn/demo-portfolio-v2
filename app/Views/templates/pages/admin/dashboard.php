<h1>Page d'administration</h1>

<h2>__Ajout ou modification__</h2>
<a href="/admin-project-form">Ajouter un projet</a>
<br>
<a href="/admin-registration">Ajouter un utilisateur</a>
<br>
<a href="/admin-update-user">Modifier données personnelles</a>
<br><br>
<hr>
<h2>__Projets__</h2>

<?php /** @var array $projectData */
// Afficher tous les projets
foreach ($projectData as $project): ?>
    <!--Nom du projet-->
    <h3><?= $project['projectName']; ?></h3>

    <!--Afficher les images du projet-->
    <?php foreach ($project['images'] as $image): ?>
        <img src="<?= $image['location'] . $image['fileName'] ?>" alt="<?= $image['fileName'] ?>">
        <a href="<?= $image['location'] . $image['fileName']?>">Clique</a>
        <br>
        <?php
            var_dump($image['location'] . $image['fileName']);
        ?>
        <br>
    <?php endforeach; ?>

    <!--Afficher le contenu du projet-->
    <p><?= $project['content']; ?></p>

    <!--Lien d'update du projet-->
    <a href="/admin-update-project?id=<?= $project['id'] ?>">Modifier</a>
    <hr>
<?php endforeach; ?>