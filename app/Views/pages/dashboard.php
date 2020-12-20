<?= $this->extend('template/main'); ?>
<?= $this->section('content'); ?>
<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <h3>Money <i class="mdi mdi-cash-multiple"></i></h3>
            <?php if ($my_money) : ?>
                <small>Rp. <?= $my_money['money_amount']; ?></small>
            <?php else : ?>
                <small>Rp. 0</small>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <h3>Log <i class="mdi mdi-chart-timeline"></i></h3>
            <?php if ($my_savedLog) : ?>
                <small><?= $my_savedLog['log']; ?></small>
            <?php else : ?>
                <small>You never save money before.</small>
            <?php endif; ?>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="card">
        <div class="card-body">
            <h3>Date <i class="mdi mdi-calendar-clock"></i></h3>
            <small><?= date('F d Y'); ?></small>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>