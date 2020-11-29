<?php if(!empty($_SESSION['notify']->errors)) { ?>
   <div id="comment_add_error" class="alert alert-danger">
        <span class="close" data-dismiss="alert">×</span>
        <h4><?=(isset($_SESSION['notify']->title)) ? $_SESSION['notify']->title : 'Error!'?></h4>
        <p><?=$_SESSION['notify']->errors?></p>
    </div>
<?php } unset($_SESSION['notify']); ?>

<div class="add-your-review mb-3 mb-md-5">
    <h5 class="reviews-block-title font-weight-bold mb-3" id="addreview"><?=$this->text('Add your own review')?></h5>
    <form action="<?=SITE_URL?>comments/add" method="POST" class="review-form" enctype="multipart/form-data">
        <input type="hidden" name="content" value="<?= $content?>">
        <input type="hidden" name="alias" value="<?= $alias?>">
        <input type="hidden" name="image_name" value="<?= $image_name?>">

        <div class="form-group row">
            <div class="col-md-6 rating">
                <span class="mb-0 align-middle"><?=$this->text('Rating')?></span>
                <div class="d-inline-block align-middle">
                    <label <?=$this->data->re_post('rating') == 5 ? 'class="checked"':''?>><input type="radio" name="rating" value="5" <?=$this->data->re_post('rating') == 5 ? 'selected':''?>></label>
                    <label <?=$this->data->re_post('rating') == 5 ? 'class="checked"':''?>><input type="radio" name="rating" value="4" <?=$this->data->re_post('rating') == 5 ? 'selected':''?>></label>
                    <label <?=$this->data->re_post('rating') == 5 ? 'class="checked"':''?>><input type="radio" name="rating" value="3" <?=$this->data->re_post('rating') == 5 ? 'selected':''?>></label>
                    <label <?=$this->data->re_post('rating') == 5 ? 'class="checked"':''?>><input type="radio" name="rating" value="2" <?=$this->data->re_post('rating') == 5 ? 'selected':''?>></label>
                    <label <?=$this->data->re_post('rating') == 5 ? 'class="checked"':''?>><input type="radio" name="rating" value="1" <?=$this->data->re_post('rating') == 5 ? 'selected':''?>></label>
                </div>
            </div>
            <div class="col-md-6 text-md-right mt-2 mt-md-0">
                <label class="image-review-style mb-0" for="image-review"><?=$this->text('Choose a image')?> <i class="fa fa-download" aria-hidden="true"></i></label>
                <input type="file" name="images[]" accept="image/jpg,image/jpeg,image/png" multiple id="image-review">
                <div class="review-gallery"></div>
            </div>
        </div>
        <div class="form-group">
            <textarea class="form-control rounded-0" name="comment" id="review-text" rows="6" placeholder="<?=$this->text('Review')?>" required><?=$this->data->re_post('comment')?></textarea>
        </div>
        <?php if($this->userIs()) { ?>
            <div class="form-group row">
                <div class="col-12 col-md-4 mt-md-0 mt-3">
                    <input class="review-btn btn w-100 rounded-0" type="submit" value="<?=$this->text('Add review')?>">
                </div>
            </div>
        <?php } else { ?>
            <div class="form-group">
                <?php $this->load->library('recaptcha');
                    $this->recaptcha->form('recaptchaVerifyCallback', 'recaptchaExpiredCallback'); ?>
            </div>
            <div class="form-group row">
                <div class="col-6 col-md-4">
                    <input class="form-control rounded-0" type="text" name="name" placeholder="<?=$this->text('Name')?>*" value="<?=$this->data->re_post('name')?>" required>
                </div>
                <div class="col-6 col-md-4">
                    <input class="form-control rounded-0" type="email" name="email" placeholder="Email*" value="<?=$this->data->re_post('email')?>" required>
                </div>
                <div class="col-12 col-md-4 mt-md-0 mt-3">
                    <input class="review-btn btn w-100 rounded-0" type="submit" value="<?=$this->text('Add review')?>" title='<?=$this->text('Заповніть "Я не робот"')?>' disabled>
                </div>
            </div>
        <?php } ?>
    </form>
</div>

<script type="text/javascript">
    window.onload = function() {
        $('.rating label').click(function() {
            $('.rating label').removeClass('checked')
            $(this).addClass('checked');
        });

        $("#image-review").change(function() {
            $(this).prev().css({
                color: '#b59759',
                'background-color': '#fff'
            }).html('<?=$this->text('Change image')?> <i class="fa fa-download" aria-hidden="true"></i>');
            imagesPreview(this, 'div.review-gallery');
        });

        var imagesPreview = function(input, placeToInsertImagePreview) {
            $(placeToInsertImagePreview).empty();
            if (input.files) {
                var filesAmount = input.files.length;
                for (i = 0; i < filesAmount; i++) {
                    var reader = new FileReader();
                    reader.onload = function(event) {
                        $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview);
                    }
                    reader.readAsDataURL(input.files[i]);
                }
            }
        };
    }
    var recaptchaVerifyCallback = function(response) {
        $('.add-your-review .review-btn').attr('disabled', false);
        $('.add-your-review .review-btn').attr('title', false);
    };
    var recaptchaExpiredCallback = function(response) {
        $('.add-your-review .review-btn').attr('disabled', true);
        $('.add-your-review .review-btn').attr('title', '<?=$this->text('Заповніть "Я не робот"')?>');
    };
</script>