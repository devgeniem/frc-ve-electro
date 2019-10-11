<?php

use VE\Electro\Product\ProductRepository;
use VE\Electro\Electro;

$product = ProductRepository::get($post->ID);

?>

<div>
    <strong>Name</strong>
    <p><?= $product->getName(); ?></p>
</div>

<div>
    <strong>Type</strong>
    <p><?= $product->getType(); ?></p>
</div>

<div>
    <strong>Grop</strong>
    <p><?= $product->getGroup(); ?></p>
</div>

<div>
    <strong>Description</strong>
    <p><?= $product->getDescription(); ?></p>
</div>

<div>
    <strong>Is Bonus product</strong>
    <p><?= $product->isBonusProduct() ? 'Kyllä' : 'Ei'; ?></p>
</div>

<div>
    <strong>Hintajakso</strong>
    <p><?= $product->getContractDuration(); ?></p>
</div>

<div>
    <strong>SOPA-link</strong>
    <p>
        <a href="<?= esc_url($product->getOrderLink()); ?>" target="_blank">
            <?= $product->getOrderLink(); ?>
        </a>
    </p>
</div>

<?php
$components = $product->components();
?>
<div style="display:flex;">
    <?php foreach($components as $component): ?>
        <?php
           $price = $component->price();
        ?>
        <div style="padding-right: 10px;">
            <div><strong><?= $component->getDescription(); ?></strong></div>
            <span>
                <?= $price->output(); ?>
            </span>
            <div>
                <?= $component->get('valid_from'); ?> - <?= $component->get('valid_to'); ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
