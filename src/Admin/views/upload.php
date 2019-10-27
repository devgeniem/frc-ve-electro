<div class="wrap">

    <h1>
        <?php _e( 'Upload JSON response' ); ?>
    </h1>

    <div id="poststuff">
        <div class="postbox">
            <div class="inside">

                <form 
                    action="<?= esc_url(admin_url('admin-post.php')); ?>" 
                    method="POST" 
                    enctype="multipart/form-data"
                >
                    <?php wp_nonce_field('enerim_json_upload'); ?>
                    <input type="hidden" name="action" value="enerim_json_upload">
                    <input type="file" name="file">
                    <div style="margin-top: 1em;">
                        <button class="button-primary">Upload</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>