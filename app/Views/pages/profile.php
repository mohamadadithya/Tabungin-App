<?= $this->extend('template/main'); ?>
<?= $this->section('content'); ?>
<!-- Column -->
<div class="col-lg-4 col-xlg-3 col-md-5">
    <div class="card">
        <div class="card-body profile-card">
            <center class="m-t-30"> <img src="/assets/profile/<?= $userData['photo']; ?>" class="rounded-circle" width="150" />
                <h4 class="card-title m-t-10"><?= $userData['username']; ?></h4>
                <h6 class="card-subtitle">Web Developer</h6>
            </center>
        </div>
    </div>
</div>
<!-- Column -->
<!-- Column -->
<div class="col-lg-8 col-xlg-9 col-md-7">
    <div class="card">
        <div class="card-body">
            <div class="container">
                <h2 class="mb-3">Update Profile</h2>
                <?php if (session()->getFlashdata('message')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong><?= (session()->getFlashdata('message')); ?></strong>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif; ?>
            </div>
            <form action="/main/update_profile" method="POST" class="form-horizontal form-material formUpdate">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <label class="col-md-12 mb-0">Username</label>
                    <div class="col-md-12">
                        <input type="text" id="username" name="username" placeholder="<?= $userData['username']; ?>" class="form-control pl-0 form-control-line <?= ($validation->hasError('username')) ? 'is-invalid' : ''; ?>">
                        <div class="invalid-feedback"><?= $validation->getError('username'); ?></div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 d-flex">
                        <button type="submit" class="btn btn-success mx-auto mx-md-0 text-white btn-update">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>