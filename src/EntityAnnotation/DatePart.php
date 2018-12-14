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

use Imediatis\EntityAnnotation\EConstant;

/**
 * Description of DatePart
 *
 * @author Sylvin
 */
class DatePart
{

    /**
     * date
     *
     * @var string
     */
    private $date;
    /**
     * represent the full time of the given value
     *
     * @var string
     */
    private $fullTime;
    /**
     * reprent the day of the given date
     *
     * @var int
     */
    private $day;
    /**
     * represent the month of the given date
     *
     * @var int
     */
    private $month;
    /**
     * represent the year of the given date
     *
     * @var int
     */
    private $year;
    /**
     * represent the time of the given date
     *
     * @var int
     */
    private $time;
    /**
     * represent hour of the given date
     *
     * @var int
     */
    private $hour;
    /**
     * represent minutes of the given date
     *
     * @var int
     */
    private $minute;
    /**
     * represent tierce of the given date
     *
     * @var string
     */
    private $tierce;
    /**
     * represent seconds of the given date
     *
     * @var int
     */
    private $second;
    /**
     * reprensent the internal regularExpression used to validate the given value
     *
     * @var string
     */
    private $format;

    /**
     *
     * @var boolean
     */
    public $isValideDate;

    /**
     * Initialise une instance
     *
     * @param string $date
     */
    function __construct($date)
    {
        $parts = [];
        $tfulltime = [];
        $this->isValideDate = false;
        if (preg_match(EConstant::REG_VALID_DATE_EN, trim($date), $parts)) {
            $this->format = EConstant::REG_VALID_DATE_EN;
        } else if (preg_match(EConstant::REG_VALID_DATE_FR, trim($date), $parts)) {
            $this->format = EConstant::REG_VALID_DATE_FR;
        } else {
            $this->format = null;
        }
        if (!is_null($this->format)) {
            $this->date = $parts[1];
            $tdate = [];

            preg_match($this->format == EConstant::REG_VALID_DATE_EN ? EConstant::REG_DATE_EN : EConstant::REG_DATE_FR, $parts[1], $tdate);
            $iy = $this->format == EConstant::REG_VALID_DATE_EN ? 1 : 3;
            $im = $this->format == EConstant::REG_VALID_DATE_EN ? 2 : 1;
            $id = $this->format == EConstant::REG_VALID_DATE_EN ? 3 : 2;
            $this->year = intval($tdate[$iy]);
            $this->month = intval($tdate[$im]);
            $this->day = intval($tdate[$id]);

            if (isset($parts[4])) {
                $this->fullTime = $parts[4];
                preg_match(EConstant::REG_TIME, $parts[4], $tfulltime);
                $this->hour = intval($tfulltime[2]);
                $this->minute = intval($tfulltime[3]);
                $this->second = isset($tfulltime[5]) ? intval($tfulltime[5]) : 0;
                $this->tierce = isset($tfulltime[7]) ? intval($tfulltime[7]) : 0;
            } else {
                $this->hour = 0;
                $this->minute = 0;
                $this->second = 0;
                $this->tierce = 0;
                $this->fullTime = null;
            }
            $this->isValideDate = $this->month <= 12 && $this->day <= 31 && $this->hour <= 24 && $this->minute <= 59 && $this->second <= 60;
            $this->time = isset($parts[5]) ? $parts[5] : null;
        } else {
            $this->date = null;
        }
    }

    /**
     * Détermine si la valeur passé est une date valide
     *
     * @return boolean
     */
    public function isValideDate()
    {
        return $this->isValideDate;
    }

    /**
     * Retourne l'expression régulière qui a permis de valider votre date
     *
     * @return string
     */
    function getFormat()
    {
        return $this->format;
    }

    /**
     * Convertir 
     * @return \DateTime
     */
    public function toDateTime()
    {
        if ($this->isValideDate) {
            return new DateTime($this->year . '-' . $this->month . '-' . $this->day . ' ' . $this->hour . ':' . $this->minute . ':' . $this->second);
        }
        return null;
    }


    /**
     * Get date
     *
     * @return  string
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Get represent the full time of the given value
     *
     * @return  string
     */ 
    public function getFullTime()
    {
        return $this->fullTime;
    }

    /**
     * Get reprent the day of the given date
     *
     * @return  int
     */ 
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Get represent seconds of the given date
     *
     * @return  int
     */ 
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * Get represent tierce of the given date
     *
     * @return  string
     */ 
    public function getTierce()
    {
        return $this->tierce;
    }

    /**
     * Get represent minutes of the given date
     *
     * @return  int
     */ 
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * Get represent hour of the given date
     *
     * @return  int
     */ 
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * Get represent the time of the given date
     *
     * @return  int
     */ 
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Get represent the year of the given date
     *
     * @return  int
     */ 
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Get represent the month of the given date
     *
     * @return  int
     */ 
    public function getMonth()
    {
        return $this->month;
    }
}
