<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Imediatis\EntityAnnotation\Security;

use DateTime;
use Imediatis\EntityAnnotation\AnnotationReader;
use Imediatis\EntityAnnotation\Attributes\DataType;
use Imediatis\EntityAnnotation\DatePart;
use Imediatis\EntityAnnotation\EConstant;
use Imediatis\EntityAnnotation\ModelState;
use Imediatis\EntityAnnotation\MonthPart;
use ReflectionClass;
use Slim\Http\Request;

/**
 * Description of ExtendedRequest
 *
 * @author Sylvin
 */
class InputValidator
{

    /**
     *
     * @var Request
     */
    public static $request;
    public static $usingSlim = false;
    public static $inputMethode;

    public static function getParam($key, $filter = FILTER_DEFAULT, $options = null)
    {
        self::$inputMethode = $_SERVER['REQUEST_METHOD'] === 'POST' ? INPUT_POST : INPUT_GET;
        if (self::$usingSlim) {
            $value = self::$request->getParam($key);
            return (!is_array($value) && !is_bool($value)) ? trim($value) : $value;
        } else {
            $value = filter_input(self::$inputMethode, $key, $filter, $options);
            return !is_null($value) ? ((!is_array($value) && !is_bool($value)) ? trim($value):$value) : $value;
        }
    }

    public static function InitSlimRequest(Request $request = null)
    {
        self::$request = $request;
        self::$usingSlim = !is_null($request);
    }

    // <editor-fold defaultstate="collapsed" desc="Data type validator">

    /**
     * Détermine si la valeur passée en paramètre est un booleen
     * @param mixed $value valeur à tester
     * @return boolean
     */
    public static function isBool($value)
    {
        return is_bool($value) ? true : (preg_match(EConstant::REG_BOOL, $value) ? true : false);
    }

    /**
     * Vérifie qu'une valeure est une valeure numérique
     *
     * @param mixed $value valeur à valider
     * @return boolean
     */
    public static function isInt($value)
    {
        return preg_match(EConstant::REG_DIGIT_ONLY, $value);
    }

    /**
     * Détermine se la valeur passé en paramètre est un réel
     * @param stirng $value valeur à controller
     * @return boolean
     */
    public static function isReal($value)
    {
        return preg_match(EConstant::REG_NUMERIC, $value);
    }

    /**
     * Valide le genre d'une personne
     *
     * @param string $gender genre à valider
     * @return boolean
     */
    public static function isGender($gender)
    {
        return preg_match(EConstant::REG_GENDER, $gender);
    }

    /**
     * Valide une adresse email
     *
     * @param string $email adresse à valider
     * @return boolean
     */
    public static function isEmail($email)
    {
        return preg_match(EConstant::REG_EMAIL, $email);
    }

    /**
     * Détermine si une adresse mail/url site web/domain a un nom de domaine valide
     *
     * @param string $param 
     * @return boolean
     */
    public static function isValidDomainForEmail($param)
    {
        if (self::isEmail($param)) {
            return checkdnsrr(explode('@', $param)[1], 'MX') && count(dns_get_record(explode('@', $param)[1], DNS_MX)) > 0;
        }
        $matches = [];
        if (preg_match_all("/^(www\.)?([a-z0-9][a-z0-9\-]*[a-z]+\.[a-z]{2,})$/i", $param, $matches)) {
            return checkdnsrr($matches[2][0], 'MX') && count(dns_get_record($matches[2][0], DNS_MX)) > 0;
        }
        return false;
    }

    /**
     * Permet de valider si la valeur passée est une adresse de site web  valide (www.toto.com)
     *
     * @param string $website
     * @return boolean
     */
    public static function isWebsite($website)
    {
        return preg_match(EConstant::REG_WEBSITE, $website);
    }

    /**
     * Valide si une variable qui lui est passé correspond à notre charte accepté pour les noms
     *
     * @param string $name valeur à valider
     * @return boolean
     */
    public static function isName($name)
    {
        return preg_match(EConstant::REG_NOM_PRENOM, $name);
    }

    /**
     * Valide si un numéro de téléphone est un numéro de téléphone GSM Camerounais
     *
     * @param string $phonNumber numéro à valider
     * @return boolean
     */
    public static function isPhone($phonNumber)
    {
        return preg_match(EConstant::REG_PHONE, $phonNumber);
    }

    /**
     * Détermine si la valeur passé en paramètre est une date valide.
     * Format accepté [yy]yy-m[m]-d[d] [[h]h:m[m][:s[s][.t[ttt]]]] ou m[m]/d[d]/yy[yy] [[h]h:m[m][:s[s][.t[ttt]]]]
     *
     * @param mixed $date Date à valider
     * @return boolean
     */
    public static function isValidDate($date)
    {
        $datepart = new DatePart($date);
        return $datepart->isValideDate();
    }

    /**
     * Permet de valider que la chaine passé en paramètre est une représentation de mois. [yy]yy-m[m] ou [m]m/yy[yy]
     * @param string $month moi à valider
     * @return boolean
     */
    public static function isValideMonth($month)
    {
        $mparts = new MonthPart($month);
        return $mparts->isValideMonth();
    }

    /**
     * Détermine si le temps passé en paramètre est un temps valide
     *
     * @param string $time
     * @return boolean
     */
    public static function isValidTime($time)
    {
        $parts = [];
        if (preg_match(EConstant::REG_TIME, $time, $parts)) {
            $h = intval($parts[2]);
            $m = intval($parts[3]);
            $s = isset($parts[5]) ? intval($parts[5]) : 0;
            return $h <= 24 && $m <= 59 && $s <= 60;
        }
        return false;
    }

    /**
     * Valide le format de la valeur comme étant un montant d'opération
     *
     * @param mixed $price valeur à valider
     * @return boolean
     */
    public static function isPrice($price)
    {
        return preg_match(EConstant::REG_PRICE, $price);
    }

    /**
     *  Détermine si la valeur passé en paramètre est une valeur null ou pas.
     * @param mixed $param
     * @return boolean
     */
    public static function isNull($param)
    {
        if (is_array($param)) {
            return count(array_filter($param, function ($v) {
                return !is_array($v) ? (trim($v) != "" ? trim($v) : null) : array_filter($v, function ($sv) {
                    return trim($sv) != null ? trim($sv) : null;
                });
            })) == 0;
        }
        return trim($param) == '' || preg_match(EConstant::REG_NULL, $param);
    }

    /**
     * Détermine si le parmètre d'authetification transmis est valide
     * @param string $aut
     * @return boolean
     */
    public static function isValideBasicAuth($aut)
    {
        return preg_match(EConstant::REG_MASK_BASIC_AUTH, $aut);
    }

    // </editor-fold>

    /**
     * 
     * @param mixed $param
     * @return DataTime
     */
    public static function getDate($param)
    {
        $org = self::getParam($param, FILTER_SANITIZE_STRING);
        $dpart = new DatePart($org);
        return $dpart->isValideDate() ? $dpart->toDateTime() : null;
    }

    /**
     * 
     * @param mixed $param Identifiant du champ à récupérer de la requete du client
     * @return DateTime
     */
    public static function getMonth($param)
    {
        $m = self::getParam($param, FILTER_SANITIZE_STRING);
        $mpart = new MonthPart($m);
        return $mpart->isValideMonth() ? $mpart->toDateTime() : null;
    }

    /**
     * Permet de récupérer de la requete transmise une valeur en tant que integer
     * @param mixed $key indexe de la variable à récupérer
     * @return integer
     */
    public static function getInt($key)
    {
        $val = self::getParam($key, FILTER_SANITIZE_NUMBER_INT);
        $val = preg_match('/^0(\.|,0)?$/', $val) ? 0 : $val;
        return self::isInt($val) ? intval($val) : null;
    }

    /**
     * Récupère la données transmise par formulaire sous la forme d'une chaîne de caractère
     *
     * @param string $varname nom de la variable à récupérer
     * @param boolean $strict Variable déterminant s'il faut récupérer la variable de manière strict (si elle est vide alors NULL est retourné)
     * @return mixed
     */
    public static function getString($varname)
    {
        $val = self::getParam($varname, FILTER_SANITIZE_STRING);
        return self::isNull($val) ? null : (is_bool($val) ? null : $val);
    }

    /**
     * Récupère un contenut html de la requette d'un client
     * @param string $varname nom du paramètre contenant la valeur
     * @return string
     */
    public static function getHtml($varname)
    {
        $val = self::getParam($varname);
        return self::isNull($val) ? null : (is_bool($val) ? null : $val);
    }

    /**
     * Récupère la données transmise par formulaire sous forme d'email
     *
     * @param string $varname Nom de la variable à récupérer
     * @param boolean $strict Variable déterminant s'il faut récupérer la variable de manière strict (si elle est vide alors NULL est retourné)
     * @return mixed
     */
    public static function getEmail($varname)
    {
        $val = self::getParam($varname, FILTER_SANITIZE_EMAIL);
        $rval = self::getParam($varname);
        return self::isEmail($val) ? $val : null;
    }

    /**
     * Recupère la données transmise par formulaire sous forme d'url
     *
     * @param string $varname nom de la variable
     * @param boolean $strict Variable déterminant s'il faut récupérer la variable de manière strict (si elle est vide alors NULL est retourné)
     * @return mixed
     */
    public static function getUrl($varname)
    {
        $val = self::getParam($varname, FILTER_SANITIZE_URL);
        return self::isNull($val) ? null : (is_bool($val) ? null : $val);
    }

    /**
     * Recupère la données transmise par formulaire sous forme de nombre réel
     *
     * @param string $varname nom de la variable
     * @param boolean $strict Variable déterminant s'il faut récupérer la variable de manière strict (si elle est vide alors NULL est retourné)
     * @return mixed
     */
    public static function getFloat($varname)
    {
        $out = self::getParam($varname, FILTER_SANITIZE_NUMBER_FLOAT);
        $out = preg_match('/^0(\.|,0)?$/', $out) ? 0 : $out;
        return self::isPrice($out) ? $out : null;
    }

    /**
     * Peremet de récupérer une valeur sous forme de tableau
     * @param string $varname indexe de la variable contenant la valeur à récupérer
     * @return array()
     */
    public static function getArray($varname)
    {
        $val = self::getParam($varname, FILTER_DEFAULT, ['flag' => FILTER_FORCE_ARRAY]);
        return self::isNull($val) ? null : (is_bool($val) ? null : $val);
    }

    /**
     * Permt de récupérer une information comme la réprésentation du temps
     * @param string $param indexe de la variable portant la données à extraire
     * @return string. Retourne une chaine au forma [h]h:m[m][:s[s][.t]]
     */
    public static function getTime($param)
    {
        $val = self::getParam($param, FILTER_SANITIZE_STRING);
        return self::isValidTime($val) ? $val : (is_bool($val) ? null : $val);
    }

    /**
     * 
     * @param string $key indexe de l'élément dont la valeur doit être récupéré
     * @return boolean;
     */
    public static function getBool($key)
    {
        $val = self::getParam($key, FILTER_SANITIZE_STRING);
        if (self::isBool($val)) {
            if (is_bool($val)) {
                return $val;
            } else {
                return strtolower($val) == 'true' || $val == 1;
            }
        }
        return null;
    }

    /**
     * 
     * @param mixed $className Nom de la classe dont on veut récupérer les éléments.
     *  dans un contexte de namespace il faut donner le nom complet y compris le namespace.
     * @return Object Objet dont on veut extraire
     */
    public static function BuildModelFromRequest($className, Request $request = null)
    {
        $refClass = new ReflectionClass($className);
        $nclasse = $refClass->getName();
        $output = new $nclasse();
        ModelState::initModelState();
        InputValidator::InitSlimRequest($request);

        $properties = $refClass->getProperties();
        //$prop       = new \ReflectionProperty('sl', 'lsk');
        foreach ($properties as $prop) {
            $prop->setAccessible(true);
            $pannotation = AnnotationReader::getPropertyAnnotation($prop, !is_null($request));
            if (is_null($pannotation)) {
                $value = self::getParam($prop->getName(), FILTER_SANITIZE_STRING);
                $value = self::isNull($value) ? null : $value;
                $defValue = $prop->getValue($output);
                $output->{$prop->getName()} = is_null($value) ? $defValue : $value;
                continue;
            }

            $methode = $pannotation->dataType->getFromRequest;
            $value = $methode($prop->getName());
            $defValue = $prop->getValue($output);

            $hasRequire = false;
            if (!is_null($pannotation->required)) {
                $hasRequire = true;
                if (is_null($value) && is_null($defValue)) {
                    ModelState::setValidity(false);
                    ModelState::setMessage($prop->getName(), $pannotation->required->getError());
                }
            }
            if (!is_null($pannotation->length)) {
                if (!is_null($value)) {
                    if (strlen($value) < $pannotation->length->min || strlen($value) > $pannotation->length->max) {
                        ModelState::setValidity(false);
                        ModelState::setMessage($prop->getName(), $pannotation->length->getError());
                    }
                } elseif (!is_null($defValue)) {
                    if (strlen($defValue) < $pannotation->length->min || strlen($defValue) > $pannotation->length->max) {
                        ModelState::setValidity(false);
                        ModelState::setMessage($prop->getName(), $pannotation->length->getError());
                    }
                } /* else {
                    ModelState::setValidity(false);
                    ModelState::setMessage($prop->getName(), $pannotation->length->getError());
                }*/
            }
            if (in_array($pannotation->dataType->type, DataType::collection())) {
                if ((!$pannotation->dataType->nullable) && is_null($value)) {
                    ModelState::setValidity(false);
                    ModelState::setMessage($prop->getName(), $pannotation->dataType->getErrMsg());
                }
            }
            $output->{$prop->getName()} = is_null($value) ? $defValue : $value;
        }

        return $output;
    }
}