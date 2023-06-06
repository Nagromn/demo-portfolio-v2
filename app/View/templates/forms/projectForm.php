<div>
    <form method="POST" action="/admin-project-form" enctype="multipart/form-data">
        <input type="file" name="files[]" multiple>

        <label for="projectName"></label>
        <input type="text" name="projectName" id="projectName">

        <label for="category">
            <select name="category[]" multiple>
                <?php foreach ($categories as $category) : ?>
                    <?php $isSelected = in_array($category['id'], $categories) ? 'selected' : ''; ?>
                    <option value="<?= $category['id'] ?>" <?= $isSelected ?>>
                        <?= $category['name'] ?>
                    </option>
                <?php endforeach ?>
            </select>
        </label>

        <label for="content"></label>
        <textarea name="content" id="content" cols="30" rows="10"></textarea>

        <input type="submit" value="Envoyer">
    </form>
</div>