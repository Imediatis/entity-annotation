<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Imediatis\EntityAnnotation;

use Imediatis\EntityAnnotation\EConstant;

/**
 * Description of MonthPart
 *
 * @author Sylvin
 */
class MonthPart
{
    public $year;
    public $month;
    public $fullmonth;
    private $_isValideMonth;

    function __construct($stringFull)
    {
        $this->fullmonth = $stringFull;
        $this->buildMonthPart();
    }

    public function buildMonthPart()
    {
        $parts = [];
        $this->_isValideMonth = false;
        if (preg_match_all(EConstant::REG_VALIDE_MONTH_EN, $this->fullmonth, $parts)) {
            $this->year = $parts[1];
            $this->month = $parts[2];
        } else if (preg_match_all(EConstant::REG_VALIDE_MONTH_FR, $this->fullmonth, $parts)) {
            $this->year = $parts[2];
            $this->month = $parts[1];
        }
        $this->_isValideMonth = !is_null($this->year) && !is_null($this->month) && $this->month <= 12;
    }

    /**
     * 
     * @return DateTime
     */
    public function toDateTime()
    {
        if ($this->_isValideMonth) {
            return new DateTime($this->year . '-' . $this->month . '-01');
        }
        return null;
    }

    function isValideMonth()
    {
        return $this->_isValideMonth;
    }


}
