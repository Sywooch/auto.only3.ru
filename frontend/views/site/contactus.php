<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use frontend\models\ContactForm;

$this->title = 'Обратная связь';
$this->registerCssFile('/css/site-index.css');
?>


<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

 <div class='left-part-contacts'>
      Если у вас возникли вопросы по работе нашего сервиса?<br> 
      Вас интересует сотрудничество в сфере автопроката?<br> 
      Напишите нам и мы найдем оптимальное решение.


 </div>
 <div class='right-part-contacts'>
   <?php if(Yii::$app->session->hasFlash('contactFormSubmitted')){ ?>
    <div class="info">
       Ваше сообщение отправлено! Ожидайте, скоро мы свяжемся с вами.
    </div>
<?php } else { ?>
   <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'name') ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'subject') ?>

                    <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

               

                    <div class="form-group r-button">
                        <?= Html::submitButton('Отправить', ['class' => 'bty', 'name' => 'contact-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>
  <?php }  ?>              
<!--
<form method="post" id="contact-form" action="/site/contactus">

  <div class="row">
    <input type="text" name="name" placeholder='Имя' />
  </div>

  <div class="row">
      <input type="text" name="mail" placeholder='Электронная почта' />
  </div>

  <div class="row">
      <input type="text" class='phone' name="tel" placeholder='Телефон' />
  </div>

  <div class="row">
    <textarea name="content" rows="10"  placeholder='Текст сообщения'></textarea>
  </div>
  
  <div class="row">
    <input type="submit" name="enter" value="Отправить" class="bty" />
  </div>

  </form>  -->



</div>

<div style="clear:both;"></div>

</div>
<?php 

$js = "    
   $('#contact-form').validate({
    rules: {
      name: {
        minlength: 3,
        required: true
      },
      tel: {
        required: true
      },
      mail: {
        required: true
      },
      content: {
        required: true
      },
    },
   messages: {
    name: {
         required: 'Нужно указать имя',
         minlength: 'Имя скишком короткое'
     },
    tel: {
         required: 'Нужно указать телефон'
     },
    mail: {
         required: 'Нужно указать Email'
     },
    content: {
         required: 'Напишите сообщение или вопрос'
     }
   },
    success: function(label) {
      label.text('').addClass('valid');
    }
  });

";
$this->registerJsFile('/js/jquery.validate.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs($js);

?>