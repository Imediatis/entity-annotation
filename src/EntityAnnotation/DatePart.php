<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Imediatis\EntityAnnotation;

use Imediatis\EntityAnnotation\EConstant;

/**
 * Description of DatePart
 *
 * @author Sylvin
 */
class DatePart {

    public $date;
    public $fullTime;
    public $day;
    public $month;
    public $year;
    public $time;
    public $hour;
    public $minute;
    public $tierce;
    public $second;
    private $format;

    /**
     *
     * @var boolean
     */
    public $isValideDate;

    function __construct($date) {
        $parts              = [];
        $tfulltime          = [];
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
            $tdate      = [];

            preg_match($this->format == EConstant::REG_VALID_DATE_EN ? EConstant::REG_DATE_EN : EConstant::REG_DATE_FR, $parts[1], $tdate);
            $iy          = $this->format == EConstant::REG_VALID_DATE_EN ? 1 : 3;
            $im          = $this->format == EConstant::REG_VALID_DATE_EN ? 2 : 1;
            $id          = $this->format == EConstant::REG_VALID_DATE_EN ? 3 : 2;
            $this->year  = intval($tdate[$iy]);
            $this->month = intval($tdate[$im]);
            $this->day   = intval($tdate[$id]);

            if (isset($parts[4])) {
                $this->fullTime = $parts[4];
                preg_match(EConstant::REG_TIME, $parts[4], $tfulltime);
                $this->hour     = intval($tfulltime[2]);
                $this->minute   = intval($tfulltime[3]);
                $this->second   = isset($tfulltime[5]) ? intval($tfulltime[5]) : 0;
                $this->tierce   = isset($tfulltime[7]) ? intval($tfulltime[7]) : 0;
            } else {
                $this->hour     = 0;
                $this->minute   = 0;
                $this->second   = 0;
                $this->tierce   = 0;
                $this->fullTime = null;
            }
            $this->isValideDate = $this->month <= 12 && $this->day <= 31 && $this->hour <= 24 && $this->minute <= 59 && $this->second <= 60;
            $this->time         = isset($parts[5]) ? $parts[5] : null;
        } else {
            $this->date = null;
        }
    }

    public function isValideDate() {
        return $this->isValideDate;
    }

    function getFormat() {
        return $this->format;
    }

    /**
     * 
     * @return DateTime
     */
    public function toDateTime() {
        if ($this->isValideDate) {
            return new DateTime($this->year . '-' . $this->month . '-' . $this->day . ' ' . $this->hour . ':' . $this->minute . ':' . $this->second);
        }
        return null;
    }

}
