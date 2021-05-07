<?php
/**
 * Plugin Name: calisia-discounts
 */

$calisiaDiscounts = new CalisiaDiscounts();
// Simple, grouped and external products
add_filter('woocommerce_product_get_price', array( $calisiaDiscounts, 'custom_price' ), 99, 2 );
add_filter('woocommerce_product_get_regular_price', array( $calisiaDiscounts, 'custom_price' ), 99, 2 );
// Variations 
add_filter('woocommerce_product_variation_get_regular_price', array( $calisiaDiscounts, 'custom_price' ), 99, 2 );
add_filter('woocommerce_product_variation_get_price', array( $calisiaDiscounts, 'custom_price' ), 99, 2 );

// Variable (price range)
add_filter('woocommerce_variation_prices_price', array( $calisiaDiscounts, 'custom_variable_price' ), 99, 3 );
add_filter('woocommerce_variation_prices_regular_price', array( $calisiaDiscounts, 'custom_variable_price' ), 99, 3 );

// Handling price caching (see explanations at the end)
add_filter( 'woocommerce_get_variation_prices_hash', array( $calisiaDiscounts, 'add_price_multiplier_to_variation_prices_hash' ), 99, 3 );


class CalisiaDiscounts{
    public function get_price_multiplier() {
        return 0.9; // x2 for testing
    }

    public function custom_price( $price ) { //public function custom_price( $price, $product ) {
        if(!$this->user_has_discount()){
            return $price;
        }

        return (float) $price * $this->get_price_multiplier();
    }

    public function custom_variable_price( $price, $variation, $product ) {
        if(!$this->user_has_discount()){
            return $price;
        }

        return (float) $price * $this->get_price_multiplier();
    }

    public function add_price_multiplier_to_variation_prices_hash( $price_hash, $product, $for_display ) {
        if($this->user_has_discount())
            $price_hash[] = $this->get_price_multiplier();
        return $price_hash;
    }

    public function user_has_discount(){
        
        $user = wp_get_current_user();
        if ( in_array( 'klient_ekskluzywny', (array) $user->roles ) )
            return true;
        return false;
    }
}