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
use Imediatis\EntityAnnotation\Security\FormSecurity;
use ReflectionClass;

/**
 * Permet d'évaluer le modèle
 *
 * @author Sylvin
 */
class ModelState {

    private static $errMsg    = [];
    private static $_isValide = true;

    public static function initModelState() {
        self::$errMsg    = [];
        self::$_isValide = true;
    }

    public static function setValidity(bool $value) {
        self::$_isValide = $value;
    }

    public static function setMessage($key, $msg) {
        self::$errMsg[$key] = $msg;
    }

    /**
     * Détermine si le modèle que l'on à construit à partir des données d'un formulaire est valide
     * @param object $model Objet dont on veut déterminer la validité.
     * @return bool
     */
    public static function isValid($model = null): bool {
        if (!is_null($model)) {
            self::initModelState();
            $rmodel     = new ReflectionClass($model);
            $properties = $rmodel->getProperties();
            //$prop       = new \ReflectionProperty('sl', 'lsk');
            foreach ($properties as $prop) {
                $pannotation = AnnotationReader::getPropertyAnnotation($prop);
                if (is_null($pannotation)) {
                    continue;
                }
                $value = $prop->getValue($model);

                if (!is_null($pannotation->required)) {
                    if (is_null($value)) {
                        self::setValidity(false);
                        self::setMessage($prop->getName(), $pannotation->required->getError());
                    }
                }
                if (!is_null($pannotation->length)) {
                    if (!is_null($value)) {
                        if (strlen($value) < $pannotation->length->min || strlen($value) > $pannotation->length->max) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->length->getError());
                        }
                    } else {
                        if (!is_null($pannotation->required)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->length->getError());
                        }
                    }
                }
                switch ($pannotation->dataType->type) {
                    case DataType::DATETIME:
                    case DataType::DATE:
                        $value = !is_null($value) ? $value->format(Data::EN_DateTimeFormat) : null;
                        if (!FormSecurity::isValidDate($value)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->dataType->getErrMsg());
                        }
                        break;
                    case DataType::MONTH:
                        $value = !is_null($value) ? $value->format('Y-m-d') : null;
                        if (!FormSecurity::isValidDate($value)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->dataType->getErrMsg());
                        }
                        break;
                    case DataType::TIME:
                        if (!FormSecurity::isValidTime($value)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->dataType->getErrMsg());
                        }
                        break;
                    case DataType::INTEGER:
                    case DataType::INT:
                    case DataType::NUMBER:
                        if (!FormSecurity::isInt($value)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->dataType->getErrMsg());
                        }
                        break;
                    case DataType::FLOAT:
                        if (!FormSecurity::isPrice($value)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->dataType->getErrMsg());
                        }
                        break;
                    case DataType::EMAIL:
                        if (!FormSecurity::isEmail($value)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->dataType->getErrMsg());
                        }
                        break;
                    case DataType::BOOL:
                    case DataType::BOOLEAN:
                        if (!is_bool($value)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), $pannotation->dataType->getErrMsg());
                        }
                        break;
                    case DataType::TARRAY:
                        if (!is_array($value)) {
                            self::setValidity(false);
                            self::setMessage($prop->getName(), sprintf("La valeur attendu pour le champ %s est un %s, mais un string a été donné à la place", $prop->getName(), DataType::TARRAY));
                        }
                        break;
                    default:
                        break;
                }
            }
        }
        return self::$_isValide;
    }

    /**
     * Retourne toutes les erreurs associées à la validation d'un modèle quelconque
     * @return array Tableau associatif des erreurs associées au modèle
     */
    public static function getErrors(): array {
        return self::$errMsg;
    }

    /**
     * Permet d'extraire le message d'erreur lié à un champ spécific
     * @param type $propertyName Champ dont on veut extraire le message d'erreur associé
     * @return string
     */
    public static function getError($propertyName): string {
        if (isset(self::$errMsg[$propertyName])) {
            return self::$errMsg[$propertyName];
        } else {
            return '';
        }
    }

}
