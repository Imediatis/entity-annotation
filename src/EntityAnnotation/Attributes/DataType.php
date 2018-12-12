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
 * Description of DataType
 *
 * @author Sylvin
 */
final class DataType
{

    const DATE = 'date';
    const DATETIME = 'datetime';
    const TIME = 'time';
    const MONTH = 'month';
    const INTEGER = 'integer';
    const INT = 'int';
    const NUMBER = 'number';
    const FLOAT = 'float';
    const EMAIL = 'email';
    const PASSWORD = 'password';
    const STRING = 'string';
    const HTML = 'html';
    const BOOLEAN = "boolean";
    const BOOL = "bool";
    const TARRAY = "array";

    public static function collection()
    {
        return [
            self::DATE,
            self::DATETIME,
            self::TIME,
            self::MONTH,
            self::INTEGER,
            self::INT,
            self::NUMBER,
            self::FLOAT,
            self::EMAIL,
            self::PASSWORD,
            self::STRING,
            self::HTML,
            self::BOOL,
            self::BOOLEAN,
            self::TARRAY
        ];
    }

}
