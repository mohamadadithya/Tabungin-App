<div class="card-body scroll">
    <h2>Log</h2>
    <?php if ($data_log) : ?>
        <?php foreach ($data_log as $dl) : ?>
            <small class="save-log"><?= $dl['log']; ?><br></small>
            <hr>
        <?php endforeach; ?>
    <?php else : ?>
        <small>You never saved money before.</small>
    <?php endif; ?>
</div>