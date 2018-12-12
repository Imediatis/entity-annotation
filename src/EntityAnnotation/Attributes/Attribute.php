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
 * Description of Attribute
 *
 * @author Sylvin
 */
class Attribute
{

    /**
     *
     * @var string
     */
    public $errMsg;

    /**
     * Contexte à partir duquel l'attribute est construite
     * @var string
     */
    private $context;

    /**
     * Retourne le contexte à partir duquel l'attribut a été construit
     * @return string
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * Permet de définir le contexte à partir duquel l'attribut a été construit
     * @param string $context
     */
    public function setContext($context)
    {
        $this->context = $context;
    }

    public function __construct(string $context, string $errMsg = null)
    {
        $this->errMsg = $errMsg;
        $this->context = $context;
    }

}
