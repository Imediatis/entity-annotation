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

use Imediatis\EntityAnnotation\Annotation;
use Imediatis\EntityAnnotation\Reader;

/**
 * Description of AnnotationReader
 *
 * @author Sylvin
 */
class AnnotationReader implements Reader
{

    public static function filterAnnotation(string $annotation) : array
    {
        $tannotation = array_filter(
            preg_split('/([\r\n\t\f] *\* *)/', $annotation, null, PREG_SPLIT_NO_EMPTY),
            function ($val) {
                return preg_match('/^@IME/', $val);
            }
        );
        $output = [];
        foreach ($tannotation as $value) {
            $output[] = preg_replace("/@IME\\\/", "", $value);
        }
        return $output;
    }

    /**
     * Permet de récupérer l'annotation d'une class
     * @param ReflectionClass $class
     * @return Annotation
     */
    public static function getClassAnnotation(ReflectionClass $class)
    {
        $tannotation = self::filterAnnotation($class->getDocComment());
        if (count($tannotation) > 0) {
            return new Annotation($tannotation, $class->getName());
        } else {
            return;
        }
    }

    /**
     * Permet de construire l'annotation d'un attribu d'une classe pour 
     * la validation de celle-ci ou pour la construction du champ de formulaire
     * @param ReflectionProperty $property
     * @param boolean $usingSlim Détermine si le framework Slim est utilisé.Valeur par défaut FALSE
     * @return Annotation
     */
    public static function getPropertyAnnotation(ReflectionProperty $property, $usingSlim = false)
    {
        $tannotation = self::filterAnnotation($property->getDocComment());
        if (count($tannotation) > 0) {
            return new Annotation($tannotation, $property->getName(), $usingSlim);
        } else {
            return;
        }
    }

}
