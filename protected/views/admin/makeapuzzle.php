<?$this->breadcrumbs=array(
    empty($this->album->componentUrl)?'Admin Options':$this->album->componentUrl,
    'Make A Puzzle',
);?>
<table style="width:100%;">
    <tr>
        <td style="width:250px;background-color:#ffffee;vertical-align:top;">
            <?$this->widget('menuAdmin')?>
        </td>
        <td style="vertical-align:top;">
            <?$this->widget('menuAdmin', array(
                'currentPage' => $this->action->id,
                'contentMenu' => true,
                //'currentAction' => 'index',
            ));?>
            <div id="divAdminContent">
                <?#=========================================================================?>
                <h1>Make A Puzzle</h1>
                <div class="form">
                    <?$form=$this->beginWidget('CActiveForm', array(
                        'id'=>'editPuzzle',
                        'enableAjaxValidation'=>false,
                    ))?>

                    <?=$form->hiddenField($model,'id')?>
                    <?=$form->hiddenField($attr,'id')?>

                    <div class="divError"><?=$form->errorSummary($model); ?></div>

                    <h2>Component Url</h2>
                    <div class="row">
                        <?=$albumName?><br />
                        <?=$form->textField($model,'componentUrl', array('class' => 'formInput',)); ?>
                        <?=$form->error($model,'componentUrl'); ?>
                    </div>

                    <h2>Title</h2>
                    <div class="row">
                        <?=$form->textField($model,'title', array('class' => 'formInput',)); ?>
                        <?=$form->error($model,'title'); ?>
                    </div>
                    <h2>Keywords (<span id="spKeywords"><?=(255-strlen($attr->keywords))?></span>)</h2>
                    <div class="row">
                        <?=$form->textArea($attr,'keywords', array(
                            'class' => 'formInput','cols' => 60, 'rows' => 2, 'id' => 'inpKeywords'
                        ))?>
                        <?=$form->error($attr,'keywords'); ?>
                    </div>

                    <h2>Description</h2>
                    <?=$form->textArea($attr,'description', array(
                        'class' => 'formInput','cols' => 60, 'rows' => 4,
                    ))?>
                    <?=$form->error($attr,'description'); ?>

                    <br /><hr /><br />

                    <h2>Photo taken</h2>
                    <?$this->widget('ext.ActiveDateSelect',array(
                        'model'=>$attr,
                        'attribute'     => 'dateImageCreated',
                        'reverse_years' => true,
                        'field_order'   => 'DMY',
                        'start_year'    => 1970,
                        'end_year'      => date("Y", time()) + 1,
                        'year_empty'    => '',
                        'month_empty'   => '',
                        'day_empty'     => '',
                    ))?>
                    <br />

                    <?$date=new DateTime();$nowDate=$date->format('Y-m-d');$date->modify('+1 day');$nextDate=$date->format('Y-m-d');?>
                    <script type="text/javascript">
                        //var time = <?=time()*100?>;
                        var nowDate = new Date('<?=$nowDate?>'); //nowDate = getDate(nowDate, true);
                        var nextDate = new Date('<?=$nextDate?>'); // +1 день
                        var toString;
                        //var inpDate; // Значение в поле ввода
                        $(function(){
                            var puzzleDate = new Date('<?=$model->dateCreated?>');
                            if (nowDate < getDate(puzzleDate, true))
                                $("#chScheduled").attr('checked', 'checked');

                            $("#chScheduled").live("click", function(){
                                if ($(this).is(":checked")) {
                                    //inpDate = $(".hasDatepicker").val(); // Сохраняем пред. значение input-a
                                    $(".hasDatepicker").val(getDate(nextDate, false));
                                } else {
                                    //if (inpDate) $(".hasDatepicker").val(inpDate);
                                    //else
                                    $(".hasDatepicker").val(getDate(nowDate, false));
                                }
                            });
                        });
                        // Если дата в будущем при изменении поля ввода, взводим checkbox
                        function dateChanged()
                        {
                            /*nowDate = getDate(nowDate, true);
                             if (nowDate < getDate($(this).val(), true))
                             $("#chScheduled").attr('checked', 'checked');*/ //alert('inside');
                        }
                        //Преобразование даты
                        function getDate(date, toInt)
                        {
                            var str; // для хранения преобразованной даты
                            var y, m, d; // Для хранения года, месяца и дня соответственно
                            if (toInt) { // Обратное преобразование в миллисекунды
                                str = date.getTime();
                            } else { // Милисекунды в формат (YY-mm-dd)
                                y = date.getFullYear();
                                m = lpad(date.getMonth()+1);
                                d = lpad(date.getDate());
                                str = y + '-' + m + '-' + d;
                            }
                            return str;
                        }
                        // Дополнить нулями слева до двух символов
                        function lpad(par)
                        {   var str = String(par);
                            if (2 <= str.length) return str.substr(-2); // Возвращаем два последних символа
                            else if (0 == str.length) return '00';
                            else return '0' + str;
                        }
                        // Количество оставшихся символов для keywords
                        function keywordsCount()
                        {
                            var rem = <?=$attr->keywords?>;
                        }
                        $("#inpKeywords").bind("click keyup", function(event){ // Слушаем нажатие клавиш и клики мыши (для отслеживания вставкок)
                            var text_inp = $(this).val();               // Получить текст
                            var max_letters = 255;          // Максимально допустимое кол-во символов
                            var remain = max_letters - text_inp.length; // Получить остаток символов
                            if (text_inp.length <= max_letters) {       // Меньше допустимой длины
                                if ($("#spKeywords").hasClass('red'))
                                    $("#spKeywords").removeClass('red');
                            } else { // Больше допустимой длины
                                if (0 > remain) // Превышен лимит букв
                                    if (!$("#spKeywords").hasClass('red'))
                                        $("#spKeywords").addClass('red')
                            }
                            $("#spKeywords").text(remain); // Изменяем счетчик оставшихся символов
                        });
                    </script>

                    <br />
                    <h2>Puzzle Date and Time</h2>
                    <?=CHtml::checkBox('chScheduled', null, array('id'=>'chScheduled'))?>

                    <?=$this->widget('zii.widgets.jui.CJuiDatePicker', array(
                        'model' => $model,
                        'attribute' => 'dateCreated',
                        'language'  => 'ru',
                        'options'   => array(
                            'showAnim'       => 'fold',
                            'dateFormat'     => 'yy-mm-dd',
                            'changeMonth'    => 'true',
                            'changeYear'     => 'true',
                            'showButtonPanel'=> 'false',
                            //'onClose'=> 'dateChanged()',
                        ),
                        'htmlOptions'=>array(
                            'style'=>'width:90px;height:20px;',
                            //'onchange'=>'dateChanged()',
                        ),
                    ), true)?>

                    <br /><hr /><br />

                    <div class="row buttons">
                        <?=CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save')?>
                    </div>

                <?$this->endWidget()?>
                </div><!-- form -->
                <?#=========================================================================?>
            </div>
        </td>
    </tr>
</table>