<?php
/*
 * This file is part of the Digitalis Software.
 * 
 * (c) IMEDIATIS <info@imediatis.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imediatis\EntityAnnotation;

use Imediatis\EntityAnnotation\Attributes\DataType;
use Imediatis\EntityAnnotation\Attributes\DataTypeAttribute;
use Imediatis\EntityAnnotation\Attributes\LabelAttribute;
use Imediatis\EntityAnnotation\Attributes\LengthAttribute;
use Imediatis\EntityAnnotation\Attributes\RequiredAttribute;

/**
 * Description of Annotation
 *
 * @author Sylvin
 */
class Annotation
{
    const REQUIRED = "/^Required(\{?.*\}?)/";
    const LENGTH = "/^Length(\{.*\})/";
    const DATATYPE = "/^DataType(\{.*\})/";
    const LABEL = "/^Label(\{.*\})/";
    const ID = "/^Id$/";

    /**
     *
     * @var RequiredAttribute
     */
    public $required;

    /**
     *
     * @var LengthAttribute
     */
    public $length;

    /**
     *
     * @var DataTypeAttribute
     */
    public $dataType;

    /**
     *
     * @var LabelAttribute
     */
    public $label;

    /**
     *
     * @var boolean
     */
    public $id;

    /**
     * Initialise les annotations d'une classe/attribut de classe
     * @param array $data Tableau conotenant toute les annotations à extraire;
     * @param string $propName Nom de la propriété/Classe pour laquelle il faut générer l'annotation
     * @param boolean $usingSlim Détermine si le framework Slim est utilisé. Valeur par defaut FALSE
     */
    public function __construct(array $data, string $propName, $usingSlim = false)
    {
        $this->required = null;
        $this->length = null;
        $this->dataType = new DataTypeAttribute($propName, "string");
        $this->label = new LabelAttribute($propName);
        $this->id = false;

        foreach ($data as $value) {
            switch (true) {
                case preg_match(self::REQUIRED, $value):
                    $trequired = $this->spliteAttribut(self::REQUIRED, $value);
                    $msg = null;
                    if (count($trequired) >= 1) {
                        $strequire = json_decode($trequired[0], true);
                        $msg = isset($strequire['errMsg']) ? $strequire['errMsg'] : $msg;
                    }
                    $this->required = new RequiredAttribute($propName, $msg);
                    break;
                case preg_match(self::LENGTH, $value):
                    $tlength = $this->spliteAttribut(self::LENGTH, $value);
                    if (count($tlength) >= 1) {
                        $stlength = json_decode($tlength[0], true);
                        if (isset($stlength['max'])) {
                            $min = isset($stlength['min']) ? $stlength['min'] : 0;
                            $max = $stlength['max'];
                            $errMsg = isset($stlength['errMsg']) ? $stlength['errMsg'] : null;
                            $this->length = new LengthAttribute($propName, $max, $min, $errMsg);
                        }
                    }
                    break;
                case preg_match(self::DATATYPE, $value):
                    $tdatatype = $this->spliteAttribut(self::DATATYPE, $value);
                    if (count($tdatatype) >= 1) {
                        $stdatatype = json_decode($tdatatype[0], true);
                        $type = isset($stdatatype['type']) ? $stdatatype['type'] : DataType::STRING;
                        $errMsg = isset($stdatatype['errMsg']) ? $stdatatype['errMsg'] : null;
                        $inputType = isset($stdatatype['inputType']) ? $stdatatype['inputType'] : null;
                        $nullable = isset($stdatatype['nullable']) ? $stdatatype['nullable'] : false;
                        $this->dataType = new DataTypeAttribute($propName, $type, $nullable, $errMsg, $inputType, $usingSlim);
                    }
                    break;
                case preg_match(self::ID, $value):
                    $this->id = true;
                    break;
                case preg_match(self::LABEL, $value):
                    $tlabel = $this->spliteAttribut(self::LABEL, $value);
                    if (count($tlabel) >= 1) {
                        $stdatatype = json_decode($tlabel[0], true);
                        $text = isset($stdatatype['text']) ? $stdatatype['text'] : $propName;
                        $fnc = isset($stdatatype['fnc']) ? $stdatatype['fnc'] : null;
                        $this->label = new LabelAttribute($text, $fnc);
                    }
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Permet de découper la chaine descriptive d'un attribut
     * @param string $pattern
     * @param string $sattribute
     * @return mixed Tableau contenant le type d'attribut et les valeurs de l'attribut
     */
    private function spliteAttribut(string $pattern, string $sattribute)
    {
        return preg_split($pattern, $sattribute, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
    }

}
