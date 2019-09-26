<?php

use VE\Electro\Product\ProductRepository;
use VE\Electro\Electro;

$product = ProductRepository::get($post->ID);

?>

<div>
    <strong>Name</strong>
    <p><?= $product->getGroup(); ?></p>
</div>

<div>
    <strong>Description</strong>
    <p><?= $product->getDescription(); ?></p>
</div>

<div>
    <strong>Bonusta</strong>
    <p><?= $product->isBonusProduct() ? 'Kyllä' : 'Ei'; ?></p>
</div>

<div>
    <strong>Hintajakso</strong>
    <p><?= $product->getContractType(); ?></p>
</div>

<?php
$components = $product->components()
    ->items()
    ->current()
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
        </div>
    <?php endforeach; ?>
</div>

<?php

$primary = $product->components()->main()->current()->first();
$price = $primary->price();

?>
<div>
    <p>
        <?= $primary->getDescription(); ?>
        <?= $price->output(); ?>
    </p>
</div>

<?php if ($product->shouldDisplayPeriod()): ?>
<div>
    <p>Hintajakso <?= $primary->get('valid_from'); ?> - <?= $primary->get('valid_to'); ?></p>
</div>
<?php endif; ?>
