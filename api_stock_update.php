<?php
$path = realpath(__DIR__) ."";
require_once $path . "/load-env.php";
require_once $path . "/TP24Api.php";
require_once $path . "/PTApi.php";
require_once $path . "/Notify.php";

$Api_tp24 = new TP24Api();
$Api_pt = new PTApi();
$Notify = new Notify();

// GET List of TP24 Products
$list = $Api_tp24->getProductList()->data;

// Filter List to include only products with manufacturerNumber
$filtered_list = array_filter($list, function($product) {
    return isset($product->manufacturerNumber);
});



// foreach Product in filtered List Get Prodcut Details from PT
$count = 0;

foreach($filtered_list as $product) {
    $ptCode = $product->manufacturerNumber;
    $stock = $Api_pt->getProductStock($ptCode);
    // Update Stock in all Products in Filtered List - if stock did change from last time
    if($stock && $stock != $product->stock) {
        // Foreach Product in Filtered List -> Patch Stock at TP24
        $Api_tp24->setStock($product->id, $stock);
        $count++;
    }
}

// Notify Admin about the number of Products that had their stock updated
$Notify->sendNotification("Lagerbestand Aktualisiert: " . $count . " Produkte geändert");

?>