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

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

/**
 *
 * @author Sylvin
 */
interface Reader
{

    /**
     * Gets a class annotation.
     *
     * @param ReflectionClass $class          The ReflectionClass of the class from which
     *                                         the class annotations should be read.
     *
     * @return Annotation|null The Annotation or NULL, if the requested annotation does not exist.
     */
    static function getClassAnnotation(\ReflectionClass $class);

    /**
     * Gets the annotations applied to a method.
     *
     * @param ReflectionMethod $method The ReflectionMethod of the method from which
     *                                  the annotations should be read.
     *
     * @return array An array of Annotations.
     */
//   static function getMethodAnnotations(\ReflectionMethod $method);

    /**
     * Gets a method annotation.
     *
     * @param ReflectionMethod $method         The ReflectionMethod to read the annotations from.
     * @param string            $annotationName The name of the annotation.
     *
     * @return object|null The Annotation or NULL, if the requested annotation does not exist.
     */
//    static function getMethodAnnotation(\ReflectionMethod $method, $annotationName);

    /**
     * Gets the annotations applied to a property.
     *
     * @param ReflectionProperty $property The ReflectionProperty of the property
     *                                      from which the annotations should be read.
     *
     * @return array An array of Annotations.
     */
//    static function getPropertyAnnotations(ReflectionProperty $property);

    /**
     * Gets a property annotation.
     *
     * @param ReflectionProperty $property       The ReflectionProperty to read the annotations from.
     * @param string              $annotationName The name of the annotation.
     *
     * @return Annotation|null The Annotation or NULL, if the requested annotation does not exist.
     */
    static function getPropertyAnnotation(ReflectionProperty $property);
}
