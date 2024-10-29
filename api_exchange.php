<?php
require_once "./load-env.php";
require_once "./TP24Api.php";

$Api_tp24 = new TP24Api();

// GET List of TP24 Products
$list = $Api_tp24->getProductList()->data;

echo "First Product Id: " . $list[0]->id . "\n";

// Filter List to include only products with manufacturerNumber
// foreach Product in filtered List Get Prodcut Details from PT
// Update Stock in all Products in Filtered List
// Foreach Product in Filtered List -> Patch Stock at TP24



?>