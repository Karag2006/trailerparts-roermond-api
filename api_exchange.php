<?php
require_once "./load-env.php";
require_once "./TP24Api.php";
require_once "./PTApi.php";

$Api_tp24 = new TP24Api();
$Api_pt = new PTApi();

// GET List of TP24 Products
$list = $Api_tp24->getProductList()->data;

// Filter List to include only products with manufacturerNumber
$filtered_list = array_filter($list, function($product) {
    return isset($product->manufacturerNumber);
});

echo count($filtered_list);

// foreach Product in filtered List Get Prodcut Details from PT
// Update Stock in all Products in Filtered List
// Foreach Product in Filtered List -> Patch Stock at TP24



?>