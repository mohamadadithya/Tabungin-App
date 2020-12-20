<?= $this->extend('template/main'); ?>
<?= $this->section('content'); ?>
<div class="col-md-6">
    <div class="card">
        <div class="card-body">
            <h2>My Money</h2>
            <h4 class="money_amount"></h4>
        </div>
    </div>
    <div class="card save-log"></div>
</div>
<div class="col-md-6">
    <form action="/main/take_money_processing" method="post" class="formTake form-horizontal form-material">
        <?= csrf_field(); ?>
        <div class="form-group">
            <label class="col-md-12 mb-0" for="money">Money Amount</label>
            <div class="col-md-12">
                <input type="number" name="money" id="money" class="form-control pl-0 form-control-line">
                <div class="invalid-feedback errorMoney"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="container">
                <button type="submit" class="btn btn-info btn-take btn-block">Take</button>
            </div>
        </div>
    </form>
</div>
<script>
    function savemoneyData() {
        $.ajax({
            url: "/main/takeSaveMoneyData",
            dataType: "json",
            success: function(response) {
                $('.money_amount').html(`Rp. ${response.data}`);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function save_log() {
        $.ajax({
            url: "/main/takeSaveMoneyLog",
            dataType: "json",
            success: function(response) {
                $('.save-log').html(response.data);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    $(document).ready(function() {
        savemoneyData();
        save_log();
        $('.formTake').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: $(this).attr('action'),
                data: $(this).serialize(),
                dataType: "json",
                beforeSend: function() {
                    $('.btn-take').attr('disable', 'disabled');
                    $('.btn-take').html('Processing...');
                },
                complete: function() {
                    $('.btn-take').removeAttr('disable');
                    $('.btn-take').html('Take');
                },
                success: function(response) {
                    if (response.error) {
                        if (response.error.money) {
                            $('#money').addClass('is-invalid');
                            $('.errorMoney').html(response.error.money);
                        } else {
                            $('#money').removeClass('is-invalid');
                            $('.errorMoney').html('');
                        }
                    } else {
                        if (response.message) {
                            if (response.message.null) {
                                $.toast({
                                    heading: 'Error',
                                    text: response.message.null,
                                    showHideTransition: 'slide',
                                    icon: 'error',
                                    position: 'top-right'
                                });
                                $('#money').val('');
                            }
                            if (response.message.gThan) {
                                $.toast({
                                    heading: 'Error',
                                    text: response.message.gThan,
                                    showHideTransition: 'slide',
                                    icon: 'error',
                                    position: 'top-right'
                                });
                                $('#money').val('');
                            }
                        } else {
                            $.toast({
                                heading: 'Success',
                                text: response.success,
                                showHideTransition: 'slide',
                                icon: 'success',
                                position: 'top-right'
                            });
                            $('#money').val('');
                            savemoneyData();
                            save_log();
                        }
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });
</script>
<?= $this->endSection(); ?>