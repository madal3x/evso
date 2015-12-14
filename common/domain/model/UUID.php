<?php

namespace connected\common\domain\model;

class UUID
{
    private $value;

    public function __construct($value = null) {
        if ($value) {
            if ( ! $value || strlen($value) < 32 || $this->entropy($value) < 3.0) {
                throw new \Exception("You need to supply an id with 32 chars and an entropy >= 3.0");
            }

            $this->value = $value;
        } else {
            $this->value = bin2hex(openssl_random_pseudo_bytes(16));
        }
    }

    /**
     * @return string
     */
    public function value()
    {
        return $this->value;
    }

    private function entropy($string) {
        $h=0;
        $size = strlen($string);
        foreach (count_chars($string, 1) as $v) {
            $p = $v/$size;
            $h -= $p*log($p)/log(2);
        }
        return $h;
    }
}