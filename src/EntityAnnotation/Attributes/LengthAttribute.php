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
 * Attribut permettant gérer la validation du model en se servant de la longueur
 *
 * @author Sylvin
 */
class LengthAttribute extends Attribute
{

    /**
     * Longeur minimale pour une chaîne de caractère. 
     * vaut 0 par défaut. Ce qui implique le non contrôle de la longueur lors
     * de la validation du modèle.
     * @var integer
     */
    public $min = 0;

    /**
     * Valeur maximale pour une chaîne de caractères
     * @var integer
     */
    public $max;

    /**
     * Initialise l'annotation Length pour une classe/attribut
     * @param string $context Classe/attribut de classe pour lequel l'annotation est définit
     * @param integer $max Longueur maximale de la chaîne de caractère
     * @param integer $min Longueur minimale de la chaîne de caractère
     * @param string $errMsg Message d'erreur en cas de non conformité
     */
    public function __construct(string $context, $max, $min = 0, $errMsg = null)
    {
        parent::__construct($context, $errMsg);
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * Retourne le message d'erreur pour la longueur min de la chaine
     * @return string
     */
    public function getMinError() : string
    {
        return !is_null($this->errMsg) ? $this->errMsg : sprintf('le champ %s require au moins %d caratères', $this->getContext(), $this->min);
    }

    /**
     * Retourne le message d'erreur pour la longueur max de la chaine
     * @return string
     */
    public function getMaxError() : string
    {
        return !is_null($this->errMsg) ? $this->errMsg : sprintf('le champ %s require au moins %d caratères', $this->getContext(), $this->max);
    }

    public function getError()
    {
        return !is_null($this->errMsg) ? $this->errMsg : sprintf('le champ %s require au moins %d caratères et au plus %d caractères', $this->getContext(), $this->min, $this->max);
    }
}
