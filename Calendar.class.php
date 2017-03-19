<?php

/**
 * Created by PhpStorm.
 * User: Angry
 * Date: 2017/3/18
 * Time: 21:59
 */
class Calendar
{
    private $_year;
    private $_month;
    private $_days;
    private $_startWeek;
    private $_prevMonth;
    private $_prevLastDays;

    function __construct()
    {
        $this->_year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $this->_month = isset($_GET['month']) ? $_GET['month'] : date('n');
        $this->_days = date('t', mktime(0, 0, 0, $this->_month, 1, $this->_year));
        $this->_startWeek = date('w', mktime(0, 0, 0, $this->_month, 1, $this->_year));
        $this->_prevMonth = $this->_month - 1 == 0 ? 12 : $this->_month - 1;
        $this->_prevLastDays = date('t', mktime(0, 0, 0, $this->_prevMonth, 1, $this->_year));
    }

    function __toString()
    {
        $out  = '<table align="center" border="1">';
        $out .= $this->changeDate();
        $out .= $this->getWeekList();
        $out .= $this->getDayList();
        $out .= '</table>';
        return $out;
    }

    private function _prevChangeYear()
    {
        $year = $this->_year - 1;
        return 'year='.$year.'&month='.$this->_month;
    }

    private function _prevChangeMonth()
    {
        if ($this->_month - 1 == 0) {
            $year = $this->_year - 1;
            $month = 12;
        } else {
            $year = $this->_year;
            $month = $this->_month - 1;
        }
        return 'year='.$year.'&month='.$month;
    }

    private function _nextChangeYear()
    {
        $year = $this->_year + 1;
        return 'year='.$year.'&month='.$this->_month;
    }

    private function _nextChangeMonth()
    {
        if ($this->_month + 1 == 13) {
            $year = $this->_year + 1;
            $month = 1;
        } else {
            $year = $this->_year;
            $month = $this->_month + 1;
        }
        return 'year='.$year.'&month='.$month;
    }

    private function changeDate()
    {
        $out  = '<tr>';
        $out .= '<td><a href="?'.$this->_prevChangeYear().'">&Lt;</a></td>';
        $out .= '<td><a href="?'.$this->_prevChangeMonth().'">&lt;</a></td>';
        $out .= '<td colspan="3">';
        $out .= '<select name="year" onchange="window.location = '."'?year='+this.options[selectedIndex].value+'&month='+".$this->_month."".'">';
        for ($i = 1970; $i <= 2038; $i++) {
            if ($i == $this->_year) {
                $select = 'selected="selected"';
            } else {
                $select = '';
            }
            $out .= '<option value="'.$i.'" '.$select.'>'.$i.'</option>';
        }
        $out .= '</select>';

        $out .= '<select name="month" onchange="window.location ='."'?year=".$this->_year."'+'&month='+this.options[selectedIndex].value".'">';
        for ($j = 1; $j <= 12; $j++) {
            if ($j == $this->_month) {
                $select = 'selected="selected"';
            } else {
                $select = '';
            }
            $out .= '<option value="'.$j.'" '.$select.'>'.$j.'</option>';
        }
        $out .= '</select>';
        $out .= '</td>';
        $out .= '<td><a href="?'.$this->_nextChangeMonth().'">&gt;</a></td>';
        $out .= '<td><a href="?'.$this->_nextChangeYear().'">&Gt;</a></td>';
        return $out;
    }

    private function getDayList()
    {
        $out  = '<tr>';
        $i = 0;
        for ($c = $this->_prevLastDays - $this->_startWeek + 1; $c <= $this->_prevLastDays; $c++) {
            $out .= "<td style='color: #ccc;'>{$c}</td>";
            $i++;
        }
        for ($j = 1; $j <= $this->_days; $j++) {
            if ($i % 7 == 0) {
                $out .= '</tr><tr>';
            }
            if ($this->_year === date('Y') && $this->_month === date('n') && $j == date('j')) {
                $select = 'style="background:#f60;color:#fff;"';
            } else {
                $select = '';
            }
            $out .= "<td $select>{$j}</td>";
            $i++;
        }

        $nextDays = 1;
        while ($i % 7 != 0) {
            $out .= '<td style="color: #ccc">'.$nextDays.'</td>';
            $nextDays++;
            $i++;
        }
        $out .= '</tr>';
        return $out;
    }

    private function getWeekList()
    {
        $week = array('日', '一', '二', '三', '四', '五', '六');
        $out = '<tr>';
        foreach ($week as $value) {
            $out .= "<td style='background: #999;'>{$value}</td>";
        }
        $out .= '</tr>';
        return $out;
    }


}