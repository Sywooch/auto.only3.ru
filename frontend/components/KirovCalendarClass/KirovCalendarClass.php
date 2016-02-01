<?php
namespace frontend\components\KirovCalendarClass;

use yii\base\Object;

class KirovCalendarClass extends Object
{

    public static $tarifBasic = 600;

    public static $tarifDomen = 600;

    public static $tarifOneSystem = 60;

    public static function getTarifBasic() { return self::$tarifBasic; }
    public static function getTarifDomen() { return self::$tarifDomen; }
    public static function getOneSystem() { return self::$tarifOneSystem; }


    public static $month = array(1=>"январь",2=>"февраль",3=>"март",4=>"апрель",5=>"май", 6=>"июнь", 7=>"июль",8=>"август",9=>"сентябрь",10=>"октябрь",11=>"ноябрь",12=>"декабрь");

    public static $picto = array(1=>'person',2=>'user',3=>'ticket',4=>'glass',5=>'instrument',6=>'home',7=>'sofa',8=>'heart',9=>'list',);

    public static $time_min_15 = array("00:00", "00:15","00:30","00:45",
        "01:00", "01:15","01:30","01:45",
        "02:00", "02:15","02:30","02:45",
        "03:00", "03:15","03:30","03:45",
        "04:00", "04:15","04:30","04:45",
        "05:00", "05:15","05:30","05:45",
        "06:00", "06:15","06:30","06:45",
        "07:00", "07:15","07:30","07:45",
        "08:00", "08:15","08:30","08:45",
        "09:00", "09:15","09:30","09:45",
        "10:00", "10:15","10:30","10:45",
        "11:00", "11:15","11:30","11:45",
        "12:00", "12:15","12:30","12:45",
        "13:00", "13:15","13:30","13:45",
        "14:00", "14:15","14:30","14:45",
        "15:00", "15:15","15:30","15:45",
        "16:00", "16:15","16:30","16:45",
        "17:00", "17:15","17:30","17:45",
        "18:00", "18:15","18:30","18:45",
        "19:00", "19:15","19:30","19:45",
        "20:00", "20:15","20:30","20:45",
        "21:00", "21:15","21:30","21:45",
        "22:00", "22:15","22:30","22:45",
        "23:00", "23:15","23:30","23:45",
        "24:00"
    );

    public static $time_min_20 = array("00:00", "00:20","00:40",
        "01:00", "01:20","01:40",
        "02:00", "02:20","02:40",
        "03:00", "03:20","03:40",
        "04:00", "04:20","04:40",
        "05:00", "05:20","05:40",
        "06:00", "06:20","06:40",
        "07:00", "07:20","07:40",
        "08:00", "08:20","08:40",
        "09:00", "09:20","09:40",
        "10:00", "10:20","10:40",
        "11:00", "11:20","11:40",
        "12:00", "12:20","12:40",
        "13:00", "13:20","13:40",
        "14:00", "14:20","14:40",
        "15:00", "15:20","15:40",
        "16:00", "16:20","16:40",
        "17:00", "17:20","17:40",
        "18:00", "18:20","18:40",
        "19:00", "19:20","19:40",
        "20:00", "20:20","20:40",
        "21:00", "21:20","21:40",
        "22:00", "22:20","22:40",
        "23:00", "23:20","23:40","24:00"
    );

    public static $time_min_30 = array("00:00", "00:30",
        "01:00", "01:30",
        "02:00", "02:30",
        "03:00", "03:30",
        "04:00", "04:30",
        "05:00", "05:30",
        "06:00", "06:30",
        "07:00", "07:30",
        "08:00", "08:30",
        "09:00", "09:30",
        "10:00", "10:30",
        "11:00", "11:30",
        "12:00", "12:30",
        "13:00", "13:30",
        "14:00", "14:30",
        "15:00", "15:30",
        "16:00", "16:30",
        "17:00", "17:30",
        "18:00", "18:30",
        "19:00", "19:30",
        "20:00", "20:30",
        "21:00", "21:30",
        "22:00", "22:30",
        "23:00", "23:30",
        "24:00");

    public static $time_hour_1 = array(
        "00:00", "01:00",
        "02:00", "03:00",
        "04:00", "05:00",
        "06:00", "07:00",
        "08:00", "09:00",
        "10:00", "11:00",
        "12:00", "13:00",
        "14:00", "15:00",
        "16:00", "17:00",
        "18:00", "19:00",
        "20:00", "21:00",
        "22:00", "23:00",
        "24:00"
    );
    public static $time_hour_2 = array(
        "00:00", "02:00",
        "04:00", "06:00",
        "08:00", "10:00",
        "12:00", "14:00",
        "16:00", "18:00",
        "20:00", "22:00",
        "24:00"
    );
    public static $time_hour_3 = array(
        "00:00", "03:00",
        "06:00", "09:00",
        "12:00", "15:00",
        "18:00", "21:00",
        "24:00"
    );

    public static function getTime_min_30()
    {
        return self::$time_min_30;
    }
    public static function getTime_min_20()
    {
        return self::$time_min_20;
    }
    public static function getTime_min_15()
    {
        return self::$time_min_15;
    }
    public static function getTime_hour_1()
    {
        return self::$time_hour_1;
    }
    public static function getTime_hour_2()
    {
        return self::$time_hour_2;
    }
    public static function getTime_hour_3()
    {
        return self::$time_hour_3;
    }



    public static function getTime_min_30_byId($id)
    {
        return self::$time_min_30[$id];
    }
    public static function getTime_min_20_byId($id)
    {
        return self::$time_min_20[$id];
    }
    public static function getTime_min_15_byId($id)
    {
        return self::$time_min_15[$id];
    }
    public static function getTime_hour_1_byId($id)
    {
        return self::$time_hour_1[$id];
    }
    public static function getTime_hour_2_byId($id)
    {
        return self::$time_hour_2[$id];
    }
    public static function getTime_hour_3_byId($id)
    {
        return self::$time_hour_3[$id];
    }


    public static function getPicto($id)
    {
        return self::$picto[$id];
    }

    public static function getMonthName()
    {
        return self::$month;
    }

    public static function getCalendar($m, $y, $allday, $actives=NULL, $weekend = array(), $beetween = array())
    {
        $month = self::getMonthName();

        $today=0;

        $cur_month_id = $m; //текущий месяц;

        if(strlen($cur_month_id) != 2) {
            $cur_month_id_2 = '0' . $cur_month_id;
        } else {
            $cur_month_id_2 = $cur_month_id;
        }

        $cur_year = $y;

        $count_month = date("t", mktime(0, 0, 0, $cur_month_id, 1, $cur_year));
        $cur_month_name = $month[$cur_month_id];
        $first_num = date("w", mktime(0, 0, 0, $cur_month_id, 1, $cur_year));
        if($first_num==0) $first_num = 7;
        $count_week = $count_month+$first_num;
        $count_week = ceil($count_week/7);
        $day = 1;
        echo "<div>";

        if($cur_month_id==date('n'))  {  echo "<div class='m_name' id='cur_mont'>";   }
        else  {  echo "<div class='m_name'>";   }
        //  echo $cur_month_name.' '.$cur_year;
            echo $cur_month_name;
        echo "</div>";

        echo "<ul class='list-name-week'><li>пн</li><li>вт</li><li>ср</li><li>чт</li><li>пт</li><li>сб</li><li>вс</li></ul>";

        $realnum = 0;

        for($i=1;$i<=$count_week;$i++)
        {
            for($j=1;$j<=7;$j++)
            {
                if($i==1)
                {
                    if($j>=$first_num)
                    {
                        if(in_array($day.'.'.$cur_month_id, $actives)) { $activ = 'cell_active'; } else { $activ = ''; }

                        if($day==date('j') && $cur_month_id==date('n')) {

                            echo "<div class='cell_nobrone cell_cal cell_noempty ".$activ."' id='cell_today' data-num='".($realnum++)."'>".$day."<input type='hidden' data-datestr='".$day.".".$cur_month_id_2.".".$cur_year."' class='res_brone_timer' value='".$day.".".$cur_month_id_2.".".$cur_year."'></div>"; $today=1;

                        }

                        else {

                            if($today==0 && $day<date('j') && $cur_month_id==date('n')) {
                                echo "<div class='cell_cal cell_noempty cell_past ".$activ."' data-num='".($realnum++)."''>".$day."</div>";
                            } else {

                                if(in_array($day.'.'.$cur_month_id_2.'.'.$cur_year, $allday)) { $brod = 'cell_brone';       } else { $brod = 'cell_nobrone';   }

                                if(in_array($day.'.'.$cur_month_id_2.'.'.$cur_year, $beetween)) { $brod = 'cell_beetween';       }   // beetween

                                if(in_array($j, $weekend)){ $brod = 'cell_brone'; }


                                echo "<div class='".$activ." cell_cal cell_noempty cell_future ".$brod."' data-num='".($realnum++)."' data-day='".sprintf("%02d", $day).".".$cur_month_id_2.".".$cur_year."'>".$day."<input type='hidden' data-datestr='".$day.".".$cur_month_id_2.".".$cur_year."' class='res_brone_timer' value='".$day.".".$cur_month_id_2.".".$cur_year."'></div>";
                            }
                        }

                        $day++;
                    }
                    else
                    {
                        echo "<div class='cell_cal cell_empty'>x</div>";
                    }
                }
                else
                {
                    if($day<=$count_month)
                    {
                        if(in_array($day.'.'.$cur_month_id, $actives)) { $activ = 'cell_active'; } else { $activ = ''; }

                        if($day==date('j') && $cur_month_id==date('n')) {
                            echo "<div class='cell_nobrone cell_cal cell_noempty ".$activ."' id='cell_today' data-num='".($realnum++)."' data-day='".sprintf("%02d",$day).".".sprintf("%02d",$cur_month_id_2).".".$cur_year."'>".$day."<input type='hidden' data-datestr='".$day.".".$cur_month_id_2.".".$cur_year."' class='res_brone_timer' value='".$day.".".$cur_month_id_2.".".$cur_year."'></div>"; $today=1;
                        } else {
                            if($today==0 && $day<date('j') && $cur_month_id==date('n')) {
                                echo "<div class='cell_cal cell_noempty cell_past ".$activ."' data-num='".($realnum++)."'>".$day."</div>";
                            }
                            else	{
                                if(in_array($day.'.'.$cur_month_id_2.'.'.$cur_year, $allday)) { $brod = 'cell_brone';       } else { $brod = 'cell_nobrone';   }
                                if(in_array($day.'.'.$cur_month_id_2.'.'.$cur_year, $beetween)) { $brod = 'cell_beetween';       }    // beetween
                                if(in_array($j, $weekend)){ $brod = 'cell_brone'; }
                                echo "<div class='".$activ." cell_cal cell_noempty cell_future ".$brod."' data-num='".($realnum++)."' data-day='".sprintf("%02d",$day).".".$cur_month_id_2.".".$cur_year."'>".$day."<input type='hidden' data-datestr='".$day.".".$cur_month_id_2.".".$cur_year."' class='res_brone_timer' value='".$day.".".$cur_month_id_2.".".$cur_year."'></div>";
                            }
                        }

                        $day++;
                    }
                    else
                    {
                        echo "<div class='cell_cal cell_empty'>x</div>";
                    }
                }
            }
            echo "<div style='clear:both;'></div>";
        }

        echo "</div>";

        return $realnum;

    }

}

?>