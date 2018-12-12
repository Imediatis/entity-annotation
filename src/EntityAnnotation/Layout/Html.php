<?php

/*
 * This file is part of the Digitalis Software.
 * 
 * (c) IMEDIATIS <info@imediatis.net>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Imediatis\EntityAnnotation\Layout;

use DateTime;
use Imediatis\EntityAnnotation\AnnotationReader;
use Imediatis\EntityAnnotation\Attributes\InputType;
use Imediatis\EntityAnnotation\Attributes\ValidationEngine;
use Imediatis\EntityAnnotation\ModelState;
use Exception;
use ReflectionClass;

/**
 * Description of HtmlHelpers
 *
 * @author Sylvin
 */
class Html
{
    const DEFAULT_ROWS_TEXTAREA = 7;

    /**
     * Permet de transformer le tableau associatif des attributs html du champ de formulaire.
     * @param type $htmlAttribute Tableau des associatif des attribut du composant. array("class"=>"form-control", "readonly"=>"readonly" ..)
     * @param array $exclude tableau d'attribut dont il faut ignorer lors de la génération des attributs
     * @return string Concatenantion des attributs du composant HTML du formulaire, Respectant la norme.
     */
    public static function HtmlAttributeToString(array $htmlAttribute = null, array $exclude = null)
    {
        $htmlAttribute = is_null($htmlAttribute) ? array() : $htmlAttribute;
        $stringhtmlAttribute = null;
        if (!is_null($htmlAttribute) && is_array($htmlAttribute)) {
            foreach ($htmlAttribute as $key => $value) {
                if (strtolower($key) == 'name' || strtolower($key) == 'id') {
                    continue;
                }
                if (!is_null($exclude) && is_array($exclude)) {
                    if (in_array($key, $exclude)) {
                        continue;
                    }
                }
                $stringhtmlAttribute .= strtolower($key) == 'multiple' ? sprintf(' %s', $key) : sprintf(' %s="%s"', $key, $value);
            }
        }
        return $stringhtmlAttribute;
    }

    /**
     * Crée un champ de formulaire selon l'annotation qui a été faite sur l'attribut dans la classe.
     * @param type $model Objet/Nom de la classe à laquelle appartient l'attribut
     * @param string $propertyName Attribut de la classe pour lequel le champ est créé
     * @param array $htmlAttribute Attribut complémentaire du champ à créer du champ à créer
     * @return string Champ du formulaire HTML généré, ou alors le message d'erreur en cas de paramètres non concordant pour la génération
     * @throws Exception Une exception est généré lorsque la classe ou l'attribut ciblé sont inexistant.
     */
    public static function InputFor($model, string $propertyName, array $htmlAttribute = null) : string
    {
        $htmlAttribute = is_null($htmlAttribute) ? array() : $htmlAttribute;
        $output = "";
        $stringhtmlAttribute = self::HtmlAttributeToString($htmlAttribute);

        try {
            $refclass = new ReflectionClass($model);
            try {
                $prop = $refclass->getProperty($propertyName);
                $pannotation = AnnotationReader::getPropertyAnnotation($prop);

                //
                //Intégration de la valeur du champ à générer
                //
                $txtareaValue = "";
                if (is_object($model)) {
                    $value = $prop->getValue($model);
                    if (!array_key_exists('value', $htmlAttribute)) {
                        $stringhtmlAttribute .= sprintf(' value="%s" ', $value);
                        $txtareaValue = $value;
                    }
                }

                if (!is_null($pannotation)) {

                    //
                    //INTERPRETATION DE L'ANNOTATION REQUIRE
                    //
                    if (!is_null($pannotation->required)) {
                        $stringhtmlAttribute .= sprintf(' required data-msg-required="%s"', $pannotation->required->getError());
                    }

                    //
                    //INTERPRETATION DE L'ANNOTATION LENGTH
                    //
                    if (!is_null($pannotation->length)) {
                        $stringhtmlAttribute .= sprintf(' maxlength="%d" data-msg-maxlength="%s"', $pannotation->length->max, $pannotation->length->getMaxError());

                        if ($pannotation->length->min > 0) {
                            $stringhtmlAttribute .= sprintf(' minlength="%d"  data-msg-minlength="%s" ', $pannotation->length->min, $pannotation->length->getMinError());
                        }
                    }

                    //
                    //INTERPRETATION DE L'ANNOTATION DATATYPE POUR DETERMINER SI C'EST UN SIMPLE INPUT OUT TEXTAREA QU'IL FAUT GENERER
                    //
                    if ($pannotation->dataType->inputType == InputType::TEXTAREA) {
                        $stringhtmlAttribute .= self::HtmlAttributeToString($htmlAttribute, 'value');
                        $stringhtmlAttribute .= !preg_match('/rows=/', $stringhtmlAttribute) ? ' rows="' . self::DEFAULT_ROWS_TEXTAREA . '"' : '';
                        $texte = isset($htmlAttribute['value']) ? $htmlAttribute['value'] : null;
                        $output = sprintf('<%s name="%s" id="%s" %s>%s</%s>', $pannotation->dataType->inputType, $propertyName, $propertyName, $stringhtmlAttribute, $texte, $pannotation->dataType->inputType);
                    } else {
                        $output = sprintf('<input type="%s" name="%s" id="%s" %s />', $pannotation->dataType->inputType, $propertyName, $propertyName, $stringhtmlAttribute);
                    }
                } else {
                    $output = sprintf('<input type="text" name="%s" id="%s" %s/>', $propertyName, $propertyName, $stringhtmlAttribute);
                }
            } catch (Exception $exc) {
                $output = sprintf("The specified property %s does not exist ", $propertyName);
                throw new Exception($output, $exc->getCode(), $exc);
            }
        } catch (Exception $exc) {
            $output = sprintf("The specified model %s does not exist ", $model);
            throw new Exception($output, $exc->getCode(), $exc);
        }
        return $output;
    }

    /**
     * Crée un champ de formulaire selon l'annotation qui a été faite sur l'attribut dans la classe.
     * @param type $model Objet/Nom de la classe à laquelle appartient l'attribut
     * @param string $propertyName Attribut de la classe pour lequel le champ est créé
     * @param array $htmlAttribute Attribut complémentaire du champ à créer du champ à créer
     * @return string Champ du formulaire HTML généré, ou alors le message d'erreur en cas de paramètres non concordant pour la génération
     * @throws Exception Une exception est généré lorsque la classe ou l'attribut ciblé sont inexistant.
     */
    public static function InputFor_ve($model, string $propertyName, array $htmlAttribute = null) : string
    {
        $htmlAttribute = is_null($htmlAttribute) ? array() : $htmlAttribute;
        $output = "";
        $stringhtmlAttribute = self::HtmlAttributeToString($htmlAttribute, array('class'));
        $valEng = new ValidationEngine();
        $valEng->addCssClass($htmlAttribute['class']);
        try {
            $refclass = new ReflectionClass($model);
            try {
                $prop = $refclass->getProperty($propertyName);
                $pannotation = AnnotationReader::getPropertyAnnotation($prop);

                //
                //Intégration de la valeur du champ à générer
                //
                $txtareaValue = "";
                if (is_object($model)) {
                    $value = $prop->getValue($model);
                    if (!array_key_exists('value', $htmlAttribute)) {
                        $svalue = is_object($value) && ($value instanceof DateTime) ? $value->format('Y-m-d') : $value;
                        $stringhtmlAttribute .= sprintf(' value="%s" ', $svalue);
                        $txtareaValue = $svalue;
                    }
                }

                if (!is_null($pannotation)) {
                    //
                    //INTERPRETATION DE L'ANNOTATION REQUIRE
                    //
                    if (!is_null($pannotation->required)) {
                        $stringhtmlAttribute .= sprintf(' required data-msg-required="%s"', $pannotation->required->getError());
                        $valEng->setRequired(true);
                    }

                    //
                    //INTERPRETATION DE L'ANNOTATION LENGTH
                    //
                    if (!is_null($pannotation->length)) {
                        $valEng->setMaxSize($pannotation->length->max);
                        $stringhtmlAttribute .= sprintf(' maxlength="%d" data-msg-maxlength="%s"', $pannotation->length->max, $pannotation->length->getMaxError());
                        if ($pannotation->length->min > 0) {
                            $stringhtmlAttribute .= sprintf(' minlength="%d"  data-msg-minlength="%s" ', $pannotation->length->min, $pannotation->length->getMinError());
                            $valEng->setMinSize($pannotation->length->min);
                        }
                    }

                    //
                    //INTERPRETATION DE L'ANNOTATION LABEL POUR L'AJOUTER COMME PLACE HOLDER
                    //
                    if (!is_null($pannotation->label)) {
                        if (!array_key_exists('placeholder', $htmlAttribute)) {
                            $stringhtmlAttribute .= sprintf(' placeholder="%s" ', $pannotation->label);
                            $htmlAttribute['placeholder'] = $pannotation->label;
                        }
                    }

                    //
                    //INTERPRETATION DE L'ANNOTATION DATATYPE POUR DETERMINER SI C'EST UN SIMPLE INPUT OUT TEXTAREA QU'IL FAUT GENERER
                    //
                    if ($pannotation->dataType->inputType == InputType::TEXTAREA) {
                        $stringhtmlAttribute = self::HtmlAttributeToString($htmlAttribute, array('value', 'class'));
                        $stringhtmlAttribute .= !preg_match('/rows=/', $stringhtmlAttribute) ? ' rows="' . self::DEFAULT_ROWS_TEXTAREA . '"' : '';
                        $stringhtmlAttribute .= $valEng;
                        $texte = isset($htmlAttribute['value']) ? $htmlAttribute['value'] : $txtareaValue;
                        $output = sprintf('<%s name="%s" id="%s" %s>%s</%s>', $pannotation->dataType->inputType, $propertyName, $propertyName, $stringhtmlAttribute, $texte, $pannotation->dataType->inputType);
                    } else {
                        $stringhtmlAttribute .= $valEng;
                        $output = sprintf('<input type="%s" name="%s" id="%s" %s />', $pannotation->dataType->inputType, $propertyName, $propertyName, $stringhtmlAttribute);
                    }
                } else {
                    $output = sprintf('<input type="text" name="%s" id="%s" %s/>', $propertyName, $propertyName, $stringhtmlAttribute);
                }
            } catch (Exception $exc) {
                $output = sprintf("The specified property %s does not exist ", $propertyName);
                //throw new Exception($output, $exc->getCode(), $exc);
            }
        } catch (Exception $exc) {
            $output = sprintf("The specified model %s does not exist ", $model);
            //throw new Exception($output, $exc->getCode(), $exc);
        }
        return $output;
    }

    public static function validationMessageFor(string $propertyName, array $htmlAttribute = null) : string
    {
        $htmlAttribute = is_null($htmlAttribute) ? array() : $htmlAttribute;
        $stringhtmlAttribute = self::HtmlAttributeToString($htmlAttribute);

        return sprintf('<span class="field-validation-valid" data-valmsg-for="%s" data-valmsg-replace="true" %s>%s</span>', $propertyName, $stringhtmlAttribute, ModelState::getError($propertyName));
    }

    /**
     * Permet de générer un Label (HTML) pour un attribut d'une classe
     * @param type $class Classe contenant l'attribut dont il faut générer le label pour le formulaire
     * @param string $propertyName Attribut de la classe pour lequel on veut générer le label
     * @param array $htmlAttribute Tablea associatif des attribut splémentaire du label
     * @return string Label HTML
     * @throws Exception Une exception est généré lorsque la classe ou l'attribut ciblé sont inexistant.
     */
    public static function LabelFor($class, string $propertyName, array $htmlAttribute = null) : string
    {
        $htmlAttribute = is_null($htmlAttribute) ? array() : $htmlAttribute;
        $output = "";
        $stringhtmlAttribute = self::HtmlAttributeToString($htmlAttribute);

        try {
            $refclass = new ReflectionClass($class);
            try {
                $pannotation = AnnotationReader::getPropertyAnnotation($refclass->getProperty($propertyName));
                if (!is_null($pannotation)) {
                    $require = "";
                    if (!is_null($pannotation->required)) {
                        $require = "&nbsp;*";
                    }
                    $output = sprintf('<label for="%s" %s>%s %s</label>', $propertyName, $stringhtmlAttribute, $pannotation->label, $require);
                } else {
                    $output = sprintf('<label for="%s" %s>%s</label>', $propertyName, $stringhtmlAttribute, $propertyName);
                }
            } catch (Exception $exc) {
                $output = sprintf("The specified property %s does not exist ", $propertyName);
                //throw new Exception($output, $exc->getCode(), $exc);
            }
        } catch (Exception $exc) {
            $output = sprintf("The specified model %s does not exist ", $class);
            //throw new Exception($output, $exc->getCode(), $exc);
        }
        return $output;
    }

    public static function dataToOptions($data, $selected)
    {
        $odata = array();
        foreach ($data as $key => $value) {
            $fvalue = null;
            $vdata = null;
            if (is_array($value)) {
                $fvalue = $value[0];
                $vdata = sprintf(' data-value="%s"', $value[1]);
            } else {
                $fvalue = $value;
            }
            $odata[] = sprintf('<option value="%s" %s %s>%s</option>', $key, $vdata, (in_array($key, $selected) ? "selected" : ""), $fvalue);
        }
        return join("<br/>", $odata);
    }

    /**
     * Permet de construire une liste déroulante HTML
     * @param type $model Modèle à partir duquel les informations proviennent
     * @param string $propertyName attribut du modèle dont la liste déroulante représente
     * @param array $dataForOptions
     * @param array $htmlAttribute
     * @param boolean $useTerms Indique si l'on doit faire usage de 
     * @return string code Html  du sélect
     */
    public static function SelectedListFor($model, string $propertyName, array $dataForOptions, array $htmlAttribute = null)
    {
        $htmlAttribute = is_null($htmlAttribute) ? array() : $htmlAttribute;
        $output = "";
        $stringhtmlAttribute = self::HtmlAttributeToString($htmlAttribute, array('class'));
        $valEng = new ValidationEngine();
        $valEng->addCssClass($htmlAttribute['class']);
        try {
            $refclass = new ReflectionClass($model);
            try {
                $prop = $refclass->getProperty($propertyName);
                $pannotation = AnnotationReader::getPropertyAnnotation($prop);

                //
                //Intégration de la valeur du champ à générer
                //
                $selected = array();
                if (is_object($model)) {
                    $value = $prop->getValue($model);
//                    if (!array_key_exists('value', $htmlAttribute)) {
//                        $stringhtmlAttribute .= sprintf(' value="%s" ', $value);
//                    }
                    if (is_array($value)) {
                        $selected = $value;
                    } else {
                        $selected[] = trim($value);
                    }
                }
                if (!preg_match('/chosen-select/', $htmlAttribute['class'])) {
                    $options = sprintf('<option %s value="">%s</option>', (count($selected) == 0 ? "selected" : ""), $propertyName);
                }

                if (isset($htmlAttribute['multiple'])) {
                    $mname = '[]';
                } else {
                    $mname = null;
                }


                if (!is_null($pannotation)) {
                    //
                    //INTERPRETATION DE L'ANNOTATION REQUIRE
                    //
                    if (!is_null($pannotation->required)) {
                        $stringhtmlAttribute .= sprintf(' required data-msg-required="%s"', $pannotation->required->getError());
                        $valEng->setRequired(true);
                    }
                    //
                    //INTERPRETATION DE L'ANNOTATION LABEL POUR L'AJOUTER COMME PLACE HOLDER
                    //
                    if (!is_null($pannotation->label) && !preg_match('/chosen-select/', $htmlAttribute['class'])) {
                        $options = sprintf('<option %s value="">%s</option>', (count($selected) == 0 ? "selected" : ""), $pannotation->label);
                    }
                    $options .= self::dataToOptions($dataForOptions, $selected);
                    $label = !is_null($pannotation->label) ? $pannotation->label : null;
                    //
                    //INTERPRETATION DE L'ANNOTATION DATATYPE POUR DETERMINER SI C'EST UN SIMPLE INPUT OUT TEXTAREA QU'IL FAUT GENERER
                    //
                    $output = sprintf('<select data-placeholder="%s" name="%s" id="%s" %s>%s</select>', $label, $propertyName . $mname, $propertyName, $stringhtmlAttribute . $valEng, $options);
                } else {
                    $options .= self::dataToOptions($dataForOptions, $selected);
                    $output = sprintf('<select name="%s" id="%s" %s>%s</select>', $propertyName, $propertyName . $mname, $stringhtmlAttribute . $valEng, $options);
                }
            } catch (Exception $exc) {
                $output = sprintf("The specified property %s does not exist ", $propertyName);
                //throw new Exception($output, $exc->getCode(), $exc);
            }
        } catch (Exception $exc) {
            $output = sprintf("The specified model %s does not exist ", $model);
            //throw new Exception($output, $exc->getCode(), $exc);
        }
        return $output;
    }

}
