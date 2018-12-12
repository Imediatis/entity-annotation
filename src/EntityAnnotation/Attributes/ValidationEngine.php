<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Imediatis\EntityAnnotation\Attributes;

/**
 * Description of ValidationEngine
 *
 * @author Sylvin
 */
class ValidationEngine
{

    const REQUIRED_MASK = "required";
    const MINSIZE_MASK = "minSize[%d]";
    const MAXSIZE_MASK = "maxSize[%d]";
    const OUTPUT_MASK = ' class="%s %s" ';

    /**
     *
     * @var array
     */
    protected $cssClass;

    /**
     *
     * @var bool
     */
    protected $required;

    /**
     *
     * @var int
     */
    protected $minSize;

    /**
     *
     * @var int
     */
    protected $maxSize;

    public function __construct($required = false, $minSize = null, $maxSize = null, $cssClass = array('form-control'))
    {
        $this->required = $required;
        $this->minSize = $minSize;
        $this->maxSize = $maxSize;
        $this->cssClass = $cssClass;
    }

    public function getRequired()
    {
        return $this->required;
    }

    public function getMinSize()
    {
        return $this->minSize;
    }

    public function getMaxSize()
    {
        return $this->maxSize;
    }

    public function getCssClass()
    {
        return $this->cssClass;
    }

    public function setRequired(bool $required = false)
    {
        $this->required = $required;
    }

    public function setMinSize(int $minSize = null)
    {
        $this->minSize = $minSize;
    }

    public function setMaxSize(int $maxSize = null)
    {
        $this->maxSize = $maxSize;
    }

    public function setCssClass(array $cssClass)
    {
        $this->cssClass = $cssClass;
    }

    /**
     * Ajoute la liste de class css séparé par l'espace à la collection de class css à appliquer au contrôle
     * @param string $cssClass  Liste de class css à ajouter au contrôle séparer par l'espace
     */
    public function addCssClass(string $cssClass = null)
    {
        if (!is_null($cssClass)) {
            $tccss = explode(" ", $cssClass);
            foreach ($tccss as $value) {
                if (!in_array($value, $this->cssClass)) {
                    $this->cssClass[] = $value;
                }
            }
        }
    }

    public function __toString()
    {
        $tab = array();
        if ($this->required) {
            $tab[] = self::REQUIRED_MASK;
        }
        if (!is_null($this->minSize)) {
            $tab[] = sprintf(self::MINSIZE_MASK, $this->minSize);
        }
        if (!is_null($this->maxSize)) {
            $tab[] = sprintf(self::MAXSIZE_MASK, $this->maxSize);
        }
        $css = join(" ", $this->cssClass);
        $validate = "";
        if (count($tab) > 0) {
            $validate = sprintf("validate[%s]", join(", ", $tab));
        }
        return sprintf(self::OUTPUT_MASK, $css, $validate);
    }

}
