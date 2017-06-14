<?php

namespace Ekomi;

use Bigcommerce\Api\Client as Bigcommerce;

/**
 * Calls the BigCommerce APIs
 * 
 * This is the class which contains the queries to eKomi Systems.
 * 
 * @since 1.0.0
 */
class BCHanlder {

    private $storeConfig;
    private $prcConfig;

    function __construct($storeConfig, $prcConfig) {
        $this->storeConfig = $storeConfig;
        $this->prcConfig = $prcConfig;

        Bigcommerce::useJson();
        
        configureBCApi($storeConfig['storeHash'], $storeConfig['accessToken']);
        Bigcommerce::verifyPeer(false);
    }

    public function getProduct($productId) {
        $product = Bigcommerce::getProduct($productId);
        $data = $this->getObjectField($product);
        return $data;
    }

    private function getObjectFields($array) {
        $fields = array();
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $fields[] = $this->getObjectField($value);
            }
        }
        return $fields;
    }

    private function getObjectField($object) {
        $array = (array) $object;
        return $array[' * fields'];
    }

    public function getVariantIDs($bcProduct) {
        $productId = '';
        if ($bcProduct) {
            foreach ($bcProduct->variants as $key => $variant) {
                $productId .= ',' . "'$variant->id'";
            }
        }
        return $productId;
    }

}
