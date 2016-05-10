<?php
/*
@version 3.0.0
*/

if(!defined('ABSPATH')) {
    exit;
}

global $woocommerce_loop;

if(!isset($woocommerce_loop['view'])) {
	$woocommerce_loop['view']='grid';
}

if($woocommerce_loop['view']=='grid') {
?>
<div class="items-wrap">
<?php } else { ?>
<div class="items-list clearfix">
<?php } ?>