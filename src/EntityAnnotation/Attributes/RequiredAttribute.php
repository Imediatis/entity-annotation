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
 * Définit l'attribu spécifiant qu'un champ est requis
 *
 * @author Sylvin
 */
class RequiredAttribute extends Attribute
{

    /**
     * Initialise l'attribut Require
     * @param string $context Classe/Attribut pour lequel l'attribut Required est définit.
     * @param string $errMsg Message d'erreur pour le champ requi
     */
    public function __construct(string $context, string $errMsg = null)
    {
        parent::__construct($context, $errMsg);
    }

    public function getError()
    {
        return is_null($this->errMsg) ? sprintf("Le champ %s est obligatoire", $this->getContext()) : $this->errMsg;
    }
}
