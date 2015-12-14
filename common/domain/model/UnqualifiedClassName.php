<?php

namespace connected\common\domain\model;

class UnqualifiedClassName
{
    /**
     * @param $object
     * @return string
     */
    public static function fromObject($object) {
        $classNameWithNamespace = get_class($object);

        if (strpos($classNameWithNamespace, '\\') !== false) {
            return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\') + 1);
        } else {
            return substr($classNameWithNamespace, strrpos($classNameWithNamespace, '\\'));
        }
    }
}