<div class="login_form_heder padding_block ">
	<h2 class="h20px_light">Заявление на оборудование точки поставки приборами учета (замену прибора учета)</h2>
</div>
<div class="login_form_container">
    <form method="post" id="main_form" enctype="multipart/form-data">
        <!--<div class="nomer_zayavki">Задать вопрос</div>-->
        <div class="inputs_container">
	        <textarea name="MESSAGE_1" allmessage style="display:none"></textarea>
            <div class="row">
                <div class="col-xs-6 labels_left">
                    <span class="star_red">*</span> <span nameinput="1">Наименование организации</span>
                </div>
                <div class="col-xs-6 inputs_right">
                    <input type="text" name="FIELD_1" inputvalue="1" require>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 labels_left">
                    <span class="star_red">*</span> <span nameinput="2">Фамилия Имя Отчество</span>
                </div>
                <div class="col-xs-6 inputs_right">
                    <input type="text" name="FIELD_2" inputvalue="2" require>
                </div>
            </div>
	          <div class="row">
	                <div class="col-xs-6 labels_left">
	                    <span class="star_red">*</span> <span nameinput="3">Должность</span>
	                </div>
	                <div class="col-xs-6 inputs_right">
	                    <input type="text" name="FIELD_3" inputvalue="3" require>
	                </div>
	            </div>

            <div class="row">
                <div class="col-xs-12 labels_left">
                    <span >Просим оборудовать точки поставки электрической энергии  </span>
	                <span nameinput="4" style="display: none">Текст</span>
                </div>
                <div class="col-xs-6 inputs_right" style="display: none;">
                    <input type="email" name="FIELD_4" inputvalue="4" value="Просим оборудовать точки поставки электрической энергии ">
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 labels_left">
	                <span class="star_red">*</span> <span nameinput="5">Наименование объекта(-ов)</span>
                </div>
                <div class="col-xs-6 inputs_right">
                    <input type="text" name="FIELD_5" inputvalue="5" require>
                </div>
            </div>
	        <div class="row">
		        <div class="col-xs-12 labels_left">
			        <span > приборами учета. </span>

		        </div>
		        <div class="col-xs-6 inputs_right" style="display: none;">

		        </div>
	        </div>
	        <div class="row">
		        <div class="col-xs-6 labels_left">
			        <span class="star_red">*</span> <span nameinput="6">Месторасположение (адрес, объект(-ов))</span>
		        </div>
		        <div class="col-xs-6 inputs_right">
			        <input type="text" name="FIELD_6" inputvalue="6" require>
		        </div>
	        </div>

	        <div class="row">
		        <div class="col-xs-6 labels_left">
			        <span class="star_red">*</span> <span nameinput="7">Количество точек учета</span>
		        </div>
		        <div class="col-xs-6 inputs_right">
			        <input type="text" name="FIELD_7" inputvalue="7" require>
		        </div>
	        </div>
	        <div class="row">
		        <div class="col-xs-6 labels_left">
			        <span class="star_red">*</span> <span nameinput="8">Наименование точки(-ек) учета (присоединения)</span>
		        </div>
		        <div class="col-xs-6 inputs_right">
			        <input type="text" name="FIELD_8" inputvalue="8" require>
		        </div>
	        </div>
	        <div class="row">
		        <div class="col-xs-6 labels_left">
			        <span class="star_red">*</span> <span nameinput="9">Уровень напряжения</span>
		        </div>
		        <div class="col-xs-6 inputs_right">
			        <input type="text" name="FIELD_9" inputvalue="9" require>
		        </div>
	        </div>
            <div class="row">
                <div class="col-xs-6 labels_left">
                    <span nameinput="10">
                        В связи с
                    </span>
                </div>
                <div class="col-xs-6 inputs_right">
                    <textarea name="FIELD_10" inputvalue="10"></textarea>
	                (указать причину)
                </div>
            </div>
	        <div class="row">
		        <div class="col-xs-6 labels_left">
			        <span class="star_red">*</span> <span nameinput="11">Ф.И.О. контактного лица</span>
		        </div>
		        <div class="col-xs-6 inputs_right">
			        <input type="text" name="FIELD_11" inputvalue="11" require>
		        </div>
	        </div>
	        <div class="row">
		        <div class="col-xs-6 labels_left">
			        <span class="star_red">*</span> <span nameinput="12">Телефон контактного лица</span>
		        </div>
		        <div class="col-xs-6 inputs_right">
			        <input type="text" name="FIELD_12" inputvalue="12" require>
		        </div>
	        </div>
            <div class="row">
                    <input type="hidden" value="Заявление на оборудование точки поставки приборами учета (замену прибора учета)" name="formname">
            </div>

            <div class="row">
                <div class="col-xs-6 labels_left">

                </div>
	            <div class="col-xs-6 inputs_right">
                    <div><a class="send_form">ОТПРАВИТЬ</a></div>
                    <div class="star_description"><span class="star_red">*</span> — обязательные поля</div>
				</div>


            </div>

        </div>
    </form>


 <div class="steps_block">
  <div class="step_item step_item_inputs_message_send" <?=(isset($arResult["MESSAGE_SEND"]) && $arResult["MESSAGE_SEND"] == 'OK') ? '' : 'style="display:none"'?>>
    <div class="col-xs-12 step_item_inputs_heder">
    <h3>Заявление на оборудование точки поставки приборами учета (замену прибора учета)</h3>
    </div>
  </div>
 </div>
</div>
