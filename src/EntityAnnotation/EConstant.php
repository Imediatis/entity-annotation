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

/**
 * Description of EConstant
 *
 * @author Sylvin
 */
class EConstant
{

  const EN_DateTimeFormat = 'Y-m-d H:i:s';
  const FR_DateTimeFormat = 'd/m/Y H:i:s';
  const EN_DateFormat = 'Y-m-d';
  const FR_DateFormat = 'd/m/Y';
  const TimeFormat = 'H:i:s';
  const d_Fr = "FR";
  const d_En = "EN";
  const REG_NOM_PRENOM = "/^([a-zA-Z1-9éèàêâùïüë]([\w -éèàêâùïüë'.])*[a-zA-Z0-9éèàêâùïüë]){2,50}$/i";
  const REG_GENDER = "/^[FM]{1}$/";
  const REG_EMAIL = "/^[_A-Za-z0-9-\+]+(\.[_A-Za-z0-9-]+)*@[A-Za-z0-9-]+(\.[A-Za-z0-9]+)*(\.[A-Za-z]{2,})$/";
  const REG_WEBSITE = "/^www\.[a-z0-9][a-z0-9\-]*[a-z]+\.[a-z]{2,}$/i";
  const REG_WEBSITE_e = "/www\.[a-z0-9][a-z0-9\-]*[a-z]+\.[a-z]{2,}/i";
  const REG_DIGIT_ONLY = "/^\d+$/";
  const REG_NUMERIC = "/^-?(0|([1-9](\d*)?))([.,]\d*+)?$/";
  const REG_DIGIT_SPACE = "/^\d[\d ]*\d$/";
  const REG_LETTER_DIGIT = "/^[a-zA-Z0-9éèàêâùïüëî][éèàêâùïüë\w-'.]*[a-zA-Z0-9éèàêâùïüë]$/";
  const REG_LETTER_DIGIT_SPACE = "/^[a-zA-Z0-9éèàêâùïüë][éèàêâùïüë\w -'.]*[a-zA-Z0-9éèàêâùïüë]$/";
  const REG_DATE = "/^((\d{2,4})-(\d{1,2})-(\d{1,2}))|((\d{1,2})\/(\d{1,2})\/(\d{2,4}))$/";
  const REG_DATE_EN = "/^(\d{2,4})-(\d{1,2})-(\d{1,2})$/";
  const REG_DATE_FR = "/^(\d{1,2})\/(\d{1,2})\/(\d{2,4})$/";
  const REG_VALID_DATE = "/^(((\d{2,4}-\d{1,2}-\d{1,2})(( )((\d{1,2}:\d{1,2})((:)(\d{1,2})(\.(\d+))?)?))?)|((\d{1,2}\/\d{1,2}\/\d{2,4})(( )((\d{1,2}:\d{1,2})((:)(\d{1,2})(\.(\d+))?)?))?))$/";
  const REG_VALID_DATE_EN = "/^(\d{2,4}-\d{1,2}-\d{1,2})(( )((\d{1,2}:\d{1,2})((:)(\d{1,2})(\.(\d+))?)?))?$/";
  const REG_VALID_DATE_FR = "/^(\d{1,2}\/\d{1,2}\/\d{2,4})(( )((\d{1,2}:\d{1,2})((:)(\d{1,2})(\.(\d+))?)?))?$/";
  const REG_VALIDE_MONTH = "/^(((\d{2,4})-(\d{1,2}))|((\d{1,2})\/(\d{2,4})))$/";
  const REG_VALIDE_MONTH_EN = "/^(\d{2,4})-(\d{1,2})$/";
  const REG_VALIDE_MONTH_FR = "/^(\d{1,2})\/(\d{2,4})$/";
  const REG_TIME = "/^((\d{1,2}):(\d{1,2}))(:(\d{1,2})(\.(\d+))?)?$/";
  const REG_UNVERSAL_DATE = "/^(\d{4}-\d{2}-\d{2}( \d{2}:\d{2}(:\d{2})?)?)$/";
  const REG_PRICE = "/^((\+|-)?(0|([1-9]\d*)))([.,]\d+)?$/";
  const REG_PHONE = "/^(237)?(6(((8[0-9])|(7[0-9])|(5[0-4])|(9[0-9])|(5[5-9]))[0-9]{6,6}))|(66[0-9]{7,7})$/";
  const REG_NULL = "/^(\s*(null)?\s*)$/i";
  const REG_BOOL = "/^(\s*(true|false|0|1)\s*)$/i";
  const REG_MSG = "/^([a-zA-Z0-9éèàêâùïüë ]([\w -éèàêâùïüë'.])*[a-zA-Z0-9éèàêâùïüë])+$/i";
  const REG_VALID_ID = "/^[1-9]\d*$/";
  const REG_ACCID = "/^[1-9]\d{13}$/";
  const REG_TRADENAME = "/^[\w ]{3,11}$/";
  const REG_LOGIN = "/^([a-zA-Z1-9][\w_]*){6,10}$/";
  const REG_DUPLICATE_DB = "/SQLSTATE\[[0-9]+\]:/";
  const REG_MASK_BASIC_AUTH = "/^Basic [\w]+$/";

  public static $REG_SWITCH_CHARACTER = array(
    '/(ê|ë|Ξ|Σ)+/i',
    '/(î|ï)+/i',
    '/(â|ä|ã|Å|å)+/i',
    '/(ô|õ|ö|ò|Ø|ø|Θ)+/i',
    '/ñ+/i',
    '/(ç|Ç)+/i',
    '/ß+/i',
    '/(û|ü)+/i',
    '/{/',
    '/}/',
    '/~/',
    '/"|\^|’/',
    '/\s+/',
    '/[^a-zA-Z0-9éèàù \-:;<=>?!+_\[\]\(\)|\'#@\/%*,.]/'
  );
  public static $REPLACE_SWITCH_CHARACTER = array(
    'e', 'i', 'a', 'o', 'n', 'c', 'b', 'u', '(', ')', '-', '\'', ' ', ''
  );
}