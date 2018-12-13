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
 * Type de donnée qui sera contenut dans un champ arpès récupération du formulaire
 *
 * @author Sylvin
 */
class DataTypeAttribute extends Attribute
{

    /**
     *
     * @var string
     */
    public $type;

    /**
     * Type du champ du formulaire Html à générer
     * @var string
     */
    public $inputType;

    /**
     * Nom de la méthode qui servira à récupérer la données une fois le formulaire transmis
     * @var callable
     */
    public $getFromRequest;

    public $nullable;

    /**
     * Permet d'extraire et manipuler l'annotation sur le type d'une classe/attribut de classe
     * @param string $context Classe/Attribut pour lequel l'annotation est traité
     * @param string $type Type de donnée à manipuler
     * @param boolean $nullable Détermine si le type passé en paramètre accepte une valeur nulle. Valeur par défaut FALSE
     * @param string $errMsg Message d'erreur en cas de non conformité du format de donnée par rapport au type. Valeur par défaut NULL
     * @param string $inputType Type de champ à utiliser sur le formulaire. Valeur par défaut NULL
     * @param boolean $usingSlim Indique si le frameworkSlim est utilisé. Valeur par défaut FALSE
     * 
     */
    public function __construct(string $context, string $type, $nullable = false, string $errMsg = null, string $inputType = null, $usingSlim = false)
    {
        parent::__construct($context, $errMsg);
        $this->type = strtolower($type);
        $this->nullable = $nullable;
        $ltype = strtolower($type);
        switch ($ltype) {
            case DataType::DATE:
            case DataType::DATETIME:
                $this->type = DataType::DATE;
                $this->inputType = !is_null($inputType) ? $inputType : InputType::DATE;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getDate";
                break;
            case DataType::TIME:
                $this->type = DataType::STRING;
                $this->inputType = !is_null($inputType) ? $inputType : InputType::TIME;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getTime";
                break;
            case DataType::MONTH:
                $this->type = DataType::DATE;
                $this->inputType = !is_null($inputType) ? $inputType : InputType::MONTH;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getMonth";
                break;
            case DataType::INTEGER:
            case DataType::INT:
            case DataType::NUMBER:
                $this->inputType = !is_null($inputType) ? $inputType : InputType::NUMBER;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getInt";
                break;
            case DataType::FLOAT:
                $this->inputType = !is_null($inputType) ? $inputType : InputType::TEXT;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getFloat";
                break;
            case DataType::EMAIL:
                $this->inputType = !is_null($inputType) ? $inputType : InputType::EMAIL;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getEmail";
                break;
            case DataType::PASSWORD:
                $this->inputType = !is_null($inputType) ? $inputType : InputType::PASSWORD;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getString";
                break;
            case DataType::TARRAY:
                $this->inputType = !is_null($inputType) ? $inputType : InputType::TEXT;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getArray";
                break;
            case DataType::HTML:
                $this->inputType = !is_null($inputType) ? $inputType : InputType::TEXTAREA;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getHtml";
                break;
            case DataType::BOOLEAN:
            case DataType::BOOL:
                $this->inputType = !is_null($inputType) ? $inputType : InputType::TEXT;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getBool";
                break;
            case DataType::STRING:
            default:
                $this->inputType = !is_null($inputType) ? $inputType : InputType::TEXT;
                $this->getFromRequest = "\Imediatis\EntityAnnotation\Security\InputValidator::getString";
                break;
        }
    }

    public function getErrMsg()
    {
        return is_null($this->errMsg) ? sprintf("La donnée fournie pour %s n'est pas conforme au format attendu", $this->getContext()) : $this->errMsg;
    }

}
