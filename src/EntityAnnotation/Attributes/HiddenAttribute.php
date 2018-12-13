<?php
/*
 * This file is part of the Digitalis Software.
 * 
 * (c) IMEDIATIS <info@imediatis.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imediatis\EntityAnnotation\Attributes;

/**
 * Description of HiddenAttribute
 *
 * @author Sylvin
 */
class HiddenAttribute extends Attribute
{
    //put your code here
    public function __construct(string $context, string $errMsg = null)
    {
        parent::__construct($context, $errMsg);
    }

}
