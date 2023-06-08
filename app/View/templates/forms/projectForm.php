<form method="POST" action="/admin-project-form" enctype="multipart/form-data">
    <input type="file" name="files[]" multiple>

    <br>

    <label for="projectName">Nom du projet :</label>
    <input type="text" name="projectName" id="projectName">

    <br>

    <label for="category"> Catégorie :
        <select name="category[]" multiple>
            <?php foreach ($categories as $category) : ?>
                <?php $isSelected = in_array($category['id'], $categories) ? 'selected' : ''; ?>
                <option value="<?= $category['id'] ?>" <?= $isSelected ?>>
                    <?= $category['name'] ?>
                </option>
            <?php endforeach ?>
        </select>
    </label>

    <br>

    <label for="content">Description :</label>
    <textarea name="content" id="content" cols="30" rows="10"></textarea>

    <br>

    <input type="submit" value="Envoyer">
</form>
