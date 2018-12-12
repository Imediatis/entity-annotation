<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Imediatis\EntityAnnotation\Attributes;

/**
 * Description of LabelAttribute
 *
 * @author Sylvin
 */
class LabelAttribute
{

    public $text;
    public $function;

    public function __construct($text, $function = null)
    {
        $this->text = $text;
        $this->function = $function;
    }

    public function __toString()
    {
        if (!is_null($this->function)) {
            $tfnc = explode("::", $this->function);
            if (count($tfnc) == 2) {
                if (method_exists($tfnc[0], $tfnc[1])) {
                    $fnc = $this->function;
                    return $fnc($this->text);
                } else {
                    return $this->text;
                }
            } else {
                if (function_exists($this->function)) {
                    $fnc = $this->function;
                    return $fnc($this->text);
                } else {
                    return $this->text;
                }
            }
        } else {
            return $this->text;
        }
    }


}
