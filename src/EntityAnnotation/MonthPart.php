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
 * Description of MonthPart
 *
 * @author Sylvin
 */
class MonthPart
{
    private $year;
    private $month;
    private $fullmonth;
    private $_isValideMonth;

    /**
     * Initialise une instance de MonthPart
     *
     * @param string $stringFull
     */
    function __construct($stringFull)
    {
        $this->fullmonth = $stringFull;
        $this->buildMonthPart();
    }
    
    private function buildMonthPart()
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
     * Convertit la valeur fournit en \DateTime
     * @return \DateTime
     */
    public function toDateTime()
    {
        if ($this->_isValideMonth) {
            return new DateTime($this->year . '-' . $this->month . '-01');
        }
        return null;
    }

    /**
     * Indique si la valeur passé est une représentation valide de moi
     *
     * @return boolean
     */
    function isValideMonth()
    {
        return $this->_isValideMonth;
    }



    /**
     * Get the value of year
     * 
     * @return string
     */ 
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Get the value of month
     * @return string
     */ 
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Get the value of fullMonth
     *
     * @return string
     */ 
    public function getFullmonth()
    {
        return $this->fullmonth;
    }
}
