<div class="wrap">
    <h1><?php _e( 'Upload JSON response' ); ?></h1>

    <form action="<?= esc_url(admin_url('admin-post.php')); ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="enerim_json_upload">
        <?php wp_referer_field(); ?>
        <input type="file" name="file">
        <button>Upload</button>
    </form>
</div>