<?php
/*
 * This file is part of
 *     ____              _ __
 *    / __ )__  ______ _(_) /_____  _____
 *   / __  / / / / __ `/ / __/ __ \/ ___/
 *  / /_/ / /_/ / /_/ / / /_/ /_/ / /
 * /_____/\__,_/\__, /_/\__/\____/_/
 *             /____/
 * A Yii powered issue tracker
 * http://bitbucket.org/jacmoe/bugitor/
 *
 * Copyright (C) 2009 - 2012 Bugitor Team
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation files
 * (the "Software"), to deal in the Software without restriction,
 * including without limitation the rights to use, copy, modify, merge,
 * publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
 * DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
 * OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
?>
<?php
/**
* Time helper is ported over from CakePHP.  Most of the credit goes to them for this class
*/
class Time {
    const LONG='D, M jS Y, g:i a';
    const SHORT='F d, o';
    /**
     * Converts given time (in server's time zone) to user's local time, given his/her offset from GMT.
     *
     * @param string $serverTime UNIX timestamp
     * @param int $userOffset User's offset from GMT (in hours)
     * @return string UNIX timestamp
     */
    static function convert($serverTime, $userOffset) {
        $serverOffset = self::serverOffset();
        $gmtTime = $serverTime - $serverOffset;
        $userTime = $gmtTime + $userOffset * (60*60);
        return $userTime;
    }
    /**
     * Returns server's offset from GMT in seconds.
     *
     * @return int Offset
     */
    static function serverOffset() {
        return date('Z', time());
    }
    /**
     * Returns a UNIX timestamp, given either a UNIX timestamp or a valid strtotime() date string.
     *
     * @param string $dateString Datetime string
     * @param int $userOffset User's offset from GMT (in hours)
     * @return string Parsed timestamp
     */
    static function makeUnix($dateString, $userOffset = null) {
        //if ($userOffset === null) $userOffset = default user offset? how to obtain?
        $date = is_numeric($dateString) ? intval($dateString) : strtotime($dateString);
        return ($userOffset !== null) ? self::convert($date, $userOffset) : $date;
    }
    /**
    * Returns a nicely formatted date string for given Datetime string.
    *
    * @param string $dateString Datetime string or UNIX time
    * @param int $format Format of returned date
    * @return string Formatted date string
    */
    public static function nice($dateString = null, $format = 'F d, o') {

        $date = ($dateString == null) ? time() : self::makeUnix($dateString);
        return date($format, $date);
    }

    /**
    * Returns a formatted descriptive date string for given datetime string.
    *
    * If the given date is today, the returned string could be "Today, 6:54 pm".
    * If the given date was yesterday, the returned string could be "Yesterday, 6:54 pm".
    * If $dateString's year is the current year, the returned string does not
    * include mention of the year.
    *
    * @param string $dateString Datetime string or Unix timestamp
    * @return string Described, relative date string
    */
    public static function niceShort($dateString = null) {
        $date = ($dateString == null) ? time() : self::makeUnix($dateString);

        $y = (self::isThisYear($date)) ? '' : ' Y';

        if (self::isToday($date)) {
            $ret = sprintf('Today, %s', date("g:i a", $date));
        } elseif (self::wasYesterday($date)) {
            $ret = sprintf('Yesterday, %s', date("g:i a", $date));
        } else {
            $ret = date("M jS{$y}, H:i", $date);
        }

        return $ret;
    }

    /**
    * Returns true if given date is today.
    *
    * @param string $date Unix timestamp or datetime string
    * @return boolean True if date is today
    */
    public static function isToday($date) {
        return date('Y-m-d', self::makeUnix($date)) == date('Y-m-d', time());
    }

    /**
    * Returns true if given date was yesterday
    *
    * @param string $date Unix timestamp or datetime string
    * @return boolean True if date was yesterday
    */
    public static function wasYesterday($date) {
        return date('Y-m-d', self::makeUnix($date)) == date('Y-m-d', strtotime('yesterday'));
    }

    /**
    * Returns true if given date is in this year
    *
    * @param string $date Unix timestamp or datetime string
    * @return boolean True if date is in this year
    */
    public static function isThisYear($date) {
        return date('Y', self::makeUnix($date)) == date('Y', time());
    }

    /**
    * Returns true if given date is in this week
    *
    * @param string $date Unix timestamp or datetime string
    * @return boolean True if date is in this week
    */
    public static function isThisWeek($date) {
        return date('W Y', self::makeUnix($date)) == date('W Y', time());
    }

    /**
    * Returns true if given date is in this month
    *
    * @param string $date Unix timestamp or datetime string
    * @return boolean True if date is in this month
    */
    public static function isThisMonth($date) {
        return date('m Y',self::makeUnix($date)) == date('m Y', time());
    }

        public static function ago( /*timestamp*/ $date ) {
            $the_date = self::makeUnix($date);

            $ago = time() - $the_date;

            $ranges = array(
                31536000 => array( 'year', 'years' ),
                2419200 => array( 'month', 'months' ),
                604800 => array( 'week', 'weeks' ),
                86400 => array( 'day', 'days' ),
                3600 => array( 'hour', 'hours' ),
                60 => array( 'minute', 'minutes' ),
            );

            $parts = array();
            foreach( $ranges as $seconds => $sufix ) {
                $t = floor($ago / $seconds);

                if ( $t > 0 ) {
                    return '<acronym title="' . Yii::app()->dateFormatter->formatDateTime($date) . '">' . $t . ' ' . $sufix[ $t>1 ? 1 : 0 ] . '</acronym>';
                }
            }
                return '<acronym title="' . Yii::app()->dateFormatter->formatDateTime($date) . '">Just now</acronym>';
        }

        public static function shortTimeAgo($date) {
            $date_parsed = date_parse($date);
            $date_timestamp = mktime(0,0,0, $date_parsed['month'], $date_parsed['day'], $date_parsed['year']);
            $now_timestamp = time();
            $timestamp_diff = $now_timestamp - $date_timestamp;
            $nice_date = $date;
            if ($timestamp_diff < (60*60*24*7*2)) {
                $nice_date = floor($timestamp_diff/60/60/24) . ((floor($timestamp_diff/60/60/24)>1) ? " Days" : " Day");
            }
            elseif ($timestamp_diff < (60*60*24*7*4)) {
                $nice_date = floor($timestamp_diff/60/60/24/7) . ((floor($timestamp_diff/60/60/24/7)>1) ? " Weeks" : " Week");
            } else {
                $total_months = $months = floor($timestamp_diff/60/60/24/30);
                if($months >= 12) {
                    $months = ($total_months % 12);
                    $years  = ($total_months - $months)/12;
                    $nice_date = $years  . (($years > 1) ? " Years" : " Year");
                }
                if($months > 0)
                    $nice_date = $months  . (($months > 1) ? " Months" : " Month");
            }
            if ($timestamp_diff < (60*60*24*2)) {
                $nice_date = $date;
            }
            return '<acronym title="' . Yii::app()->dateFormatter->formatDateTime($date) . '">' . $nice_date . '</acronym>';
            //return $nice_date;
        }

        /**
    * Returns either a relative date or a formatted date depending
    * on the difference between the current time and given datetime.
    * $datetime should be in a <i>strtotime</i>-parsable format, like MySQL's datetime datatype.
    *
    * Options:
    *  'format' => a fall back format if the relative time is longer than the duration specified by end
    *  'end' =>  The end of relative time telling
    *
    * Relative dates look something like this:
    *   3 weeks, 4 days ago
    *   15 seconds ago
    * Formatted dates look like this:
    *   on 02/18/2004
    *
    * The returned string includes 'ago' or 'on' and assumes you'll properly add a word
    * like 'Posted ' before the function output.
    *
    * @param string $dateString Datetime string
    * @param array $options Default format if timestamp is used in $dateString
    * @return string Relative time string.
    */
    public static function timeAgoInWords($dateTime, $title_extra = '', $options = array()) {
        $now = time();

        $inSeconds = self::makeUnix($dateTime);// - Yii::app()->config->get('serverOffset');
        $backwards = ($inSeconds > $now);

        $format = 'j/n/y';
        $end = '+24 month';

        if (is_array($options)) {
            if (isset($options['format'])) {
                $format = $options['format'];
                unset($options['format']);
            }
            if (isset($options['end'])) {
                $end = $options['end'];
                unset($options['end']);
            }
        } else {
            $format = $options;
        }

        if ($backwards) {
            $futureTime = $inSeconds;
            $pastTime = $now;
        } else {
            $futureTime = $now;
            $pastTime = $inSeconds;
        }
        $diff = $futureTime - $pastTime;

        // If more than a week, then take into account the length of months
        if ($diff >= 604800) {
            $current = array();
            $date = array();

            list($future['H'], $future['i'], $future['s'], $future['d'], $future['m'], $future['Y']) = explode('/', date('H/i/s/d/m/Y', $futureTime));

            list($past['H'], $past['i'], $past['s'], $past['d'], $past['m'], $past['Y']) = explode('/', date('H/i/s/d/m/Y', $pastTime));
            $years = $months = $weeks = $days = $hours = $minutes = $seconds = 0;

            if ($future['Y'] == $past['Y'] && $future['m'] == $past['m']) {
                $months = 0;
                $years = 0;
            } else {
                if ($future['Y'] == $past['Y']) {
                    $months = $future['m'] - $past['m'];
                } else {
                    $years = $future['Y'] - $past['Y'];
                    $months = $future['m'] + ((12 * $years) - $past['m']);

                    if ($months >= 12) {
                        $years = floor($months / 12);
                        $months = $months - ($years * 12);
                    }

                    if ($future['m'] < $past['m'] && $future['Y'] - $past['Y'] == 1) {
                        $years --;
                    }
                }
            }

            if ($future['d'] >= $past['d']) {
                $days = $future['d'] - $past['d'];
            } else {
                $daysInPastMonth = date('t', $pastTime);
                $daysInFutureMonth = date('t', mktime(0, 0, 0, $future['m'] - 1, 1, $future['Y']));

                if (!$backwards) {
                    $days = ($daysInPastMonth - $past['d']) + $future['d'];
                } else {
                    $days = ($daysInFutureMonth - $past['d']) + $future['d'];
                }

                if ($future['m'] != $past['m']) {
                    $months --;
                }
            }

            if ($months == 0 && $years >= 1 && $diff < ($years * 31536000)) {
                $months = 11;
                $years --;
            }

            if ($months >= 12) {
                $years = $years + 1;
                $months = $months - 12;
            }

            if ($days >= 7) {
                $weeks = floor($days / 7);
                $days = $days - ($weeks * 7);
            }
        } else {
            $years = $months = $weeks = 0;
            $days = floor($diff / 86400);

            $diff = $diff - ($days * 86400);

            $hours = floor($diff / 3600);
            $diff = $diff - ($hours * 3600);

            $minutes = floor($diff / 60);
            $diff = $diff - ($minutes * 60);
            $seconds = $diff;
        }
        $relativeDate = '';
        $diff = $futureTime - $pastTime;

        if ($diff > abs($now - strtotime($end))) {
            $relativeDate = sprintf('on %s', date($format, $inSeconds));
        } else {
            if ($years > 0) {
                // years and months and days
                $relativeDate .= ($relativeDate ? ', ' : '') . $years . ' ' . ($years==1 ? 'year':'years');
                $relativeDate .= $months > 0 ? ($relativeDate ? ', ' : '') . $months . ' ' . ($months==1 ? 'month':'months') : '';
                $relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . $weeks . ' ' . ($weeks==1 ? 'week':'weeks') : '';
                $relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . ($days==1 ? 'day':'days') : '';
            } elseif (abs($months) > 0) {
                // months, weeks and days
                $relativeDate .= ($relativeDate ? ', ' : '') . $months . ' ' . ($months==1 ? 'month':'months');
                $relativeDate .= $weeks > 0 ? ($relativeDate ? ', ' : '') . $weeks . ' ' . ($weeks==1 ? 'week':'weeks') : '';
                $relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . ($days==1 ? 'day':'days') : '';
            } elseif (abs($weeks) > 0) {
                // weeks and days
                $relativeDate .= ($relativeDate ? ', ' : '') . $weeks . ' ' . ($weeks==1 ? 'week':'weeks');
                $relativeDate .= $days > 0 ? ($relativeDate ? ', ' : '') . $days . ' ' . ($days==1 ? 'day':'days') : '';
            } elseif (abs($days) > 0) {
                // days and hours
                $relativeDate .= ($relativeDate ? ', ' : '') . $days . ' ' . ($days==1 ? 'day':'days');
                $relativeDate .= $hours > 0 ? ($relativeDate ? ', ' : '') . $hours . ' ' . ($hours==1 ? 'hour':'hours') : '';
            } elseif (abs($hours) > 0) {
                // hours and minutes
                $relativeDate .= ($relativeDate ? ', ' : '') . $hours . ' ' . ($hours==1 ? 'hour':'hours');
                $relativeDate .= $minutes > 0 ? ($relativeDate ? ', ' : '') . $minutes . ' ' . ($minutes==1 ? 'minute':'minutes') : '';
            } elseif (abs($minutes) > 0) {
                // minutes only
                $relativeDate .= ($relativeDate ? ', ' : '') . $minutes . ' ' . ($minutes==1 ? 'minute':'minutes');
            } else {
                // seconds only
                $relativeDate .= ($relativeDate ? ', ' : '') . $seconds . ' ' . ($seconds==1 ? 'second':'seconds');
            }

            if (!$backwards) {
                $relativeDate = sprintf('%s ago', $relativeDate);
            }
        }
        return '<acronym title="' . Yii::app()->dateFormatter->formatDateTime($dateTime) . $title_extra . '">' . $relativeDate . '</acronym>';
    }

        // This is a simple script to calculate the difference between two dates
        // and express it in years, months and days
        //
        // use as in: "my daughter is 4 years, 2 month and 17 days old" ... :-)
        //
        // Feel free to use this script for whatever you want
        //
        // version 0.1 / 2002-10-3
        //
        // please send comments and feedback to webmaster@lotekk.net
        public static function dueDateInWords($dateTime) {

            $the_date = date_parse($dateTime);

            $overdue = false;
            if(strtotime($dateTime) < strtotime(date("Y-m-d"))) {
                $overdue = true;
                $base_day = $the_date['day'];
                $base_mon = $the_date['month'];
                $base_yr = $the_date['year'];

                $due_day = date ("j");
                $due_mon = date ("n");
                $due_yr = date ("Y");
            } else {
                // get the base date here (today)
                $base_day       = date ("j");
                $base_mon       = date ("n");
                $base_yr        = date ("Y");

                $due_day = $the_date['day'];
                $due_mon = $the_date['month'];
                $due_yr = $the_date['year'];
            }

            // and now .... calculate the difference! :-)

            // overflow is always caused by max days of $base_mon
            // so we need to know how many days $base_mon had
            $base_mon_max       = date ("t",mktime (0,0,0,$base_mon,$base_day,$base_yr));

            // days left till the end of that month
            $base_day_diff      = $base_mon_max - $base_day;

            // month left till end of that year
            // substract one to handle overflow correctly
            $base_mon_diff      = 12 - $base_mon - 1;

            // start on jan 1st of the next year
            $start_day      = 1;
            $start_mon      = 1;
            $start_yr       = $base_yr + 1;

            // difference to that 1st of jan
            $day_diff   = ($due_day - $start_day) + 1;  // add today
            $mon_diff   = ($due_mon - $start_mon) + 1;  // add current month
            $yr_diff    = ($due_yr - $start_yr);

            // and add the rest of $base_yr
            $day_diff   = $day_diff + $base_day_diff;
            $mon_diff   = $mon_diff + $base_mon_diff;

            // handle overflow of days
            if ($day_diff >= $base_mon_max)
            {
                    $day_diff = $day_diff - $base_mon_max;
                    $mon_diff = $mon_diff + 1;
            }

            // handle overflow of years
            if ($mon_diff >= 12)
            {
                    $mon_diff = $mon_diff - 12;
                    $yr_diff = $yr_diff + 1;
            }
            // this is just to make it look nicer
            $years = "years";
            $days = "days";
            $months = "months";

            if ($yr_diff == "1") $years = "year";
            if ($yr_diff == "-1") $years = "year";
            if ($day_diff == "1") $days = "day";
            if ($day_diff == "-1") $days = "day";
            if ($mon_diff == "1") $months = "month";
            if ($mon_diff == "-1") $months = "month";

            // here we go
            $due = '';
            if($yr_diff <> 0)
                $due = $due . $yr_diff." ".$years.", ";
            if($mon_diff <> 0)
                $due = $due . $mon_diff." " .$months. " and ";
            $due = $due . $day_diff." ".$days;

            if($overdue) {
                $due = $due . ' <b>overdue</b>';
            } elseif('0 days' == $due) {
                $due = "Due today";
            } else {
                $due = "Due in " . $due;
            }

            return $due;
        }
}
