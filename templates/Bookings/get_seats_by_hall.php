<?php foreach ($seats as $id => $type): ?>
    <option value="<?= $id ?>"><?= h($type) ?></option>
<?php endforeach; ?>