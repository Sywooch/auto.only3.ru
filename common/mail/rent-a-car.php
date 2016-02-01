<?php
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
    <html>
<body>

    <h2  style='margin: 10px;
  font-weight: normal;
  font-family: Tahoma;
  letter-spacing: 1px;
  width: 555px;
  text-align: center;line-height: 30px;'>Бронирование автомобиля<br> "<?php echo $systemModel->name; ?>"</h2>
    <br>
    Доброго дня!
    <br><br>
    Сообщаем Вам, что <?php echo date('H:i d.m.Y'); ?> произошло бронирование следующего периода:
<ul>
<?php
$ds = $rentModel;
$name_p = $ds->name;
$phone_p = $ds->phone;
$comm_p = $ds->comment;
echo "<li><span style='font-weight: bold;'>с " . $ds->rent_from . " по " . $ds->rent_to . "</span>";
echo "(<a target='_blank' href=" . Url::toRoute(['profile/system/plan', 'id' => $systemModel->id, 'rent' => md5($ds->id)], true) . "  style='display: inline-block;margin: 0px 5px; color: red;font-style: italic;'>Посмотреть бронь</a>)";
echo "</li>";

?>
</ul>

    <br>
    <div style="line-height:32px;">
      <span style="display: inline-block;
  width: 100px;
  text-align: right;">Имя:</span> <span style="font-weight: bold;display: inline-block;margin-left: 6px;"><?= $name_p; ?></span></div>
    <div style="line-height:32px;">
      <span style="display: inline-block;
  width: 100px;
  text-align: right;">Телефон:</span> <span style="font-weight: bold;display: inline-block;margin-left: 6px;"><?= $phone_p; ?></span></div>
    <div style="line-height:32px;">
      <span style="display: inline-block;
  width: 100px;
  text-align: right;">Комментарии:</span> <span style="font-weight: bold;display: inline-block;margin-left: 6px;"><?php if($ds->comment != NULL && $ds->comment != '') echo $ds->comment; else echo "не указаны"; ?></span></div>
    <br><br>
    Чтобы просмотреть больше информации о совершенном бронировании, перейдите в личный кабинет - <a href="<?= Url::toRoute(['profile/system/index'], true)?>">войти</a>
<br><br>
Администрация, only3.ru
</body>
</html>