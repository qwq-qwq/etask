/**
 * Взять задачу на исполнение
 * @param mode
 */
function setExecutor(mode) {
	mode = mode || null;
	if (mode) {
		$.post("ajax.php", {"event":"setExecutor", "ID":$("#ID").val()},
			function(data){	$("#btnSetExecutor").attr('disabled', 'disabled'); });
	} else {
		$("#event").val('setExecutor');
		$("#orderForm").submit();
	}
}

/**
 * Отредерить вкладку "Котактные данные"
 */
function companyInfoDisplay(){
	var isSet = false;
	$("#companyInfo input, #companyInfo textarea").each( function (i, e) {
		if ($(this).val()) {
			isSet = true;
			return false;
		}
	});
	if (isSet) {
		$("#legalPerson").attr('checked', 'checked');
		$("#companyInfo").show();
	} else {
		$("#legalPerson").removeAttr('checked');
		$("#companyInfo").hide();
	}
}

/**
 * Отрендерить вкладку "Оплапта и доставка"
 */
function paymentInfoDisplay() {
	if ($("#Delivery_type").val() != 1) {
//		$("#Courier_id_row").hide();
		$("#Delivery_date_row").hide();
	}
	if ($("#Delivery_type").val() != 3) $("#Shop_id_row").hide();
}

/**
 * Установить режим "Только для чтения" формы
 * @param form id формы
 */
function readonlyForm(form) {
	$("#"+form+" input, #"+form+" textarea, #"+form+" select").each( function (i, e) {
		if ($(this).is("select") ||	["submit","button","checkbox"].exists($(this).attr('type'))) {
			$(this).attr('disabled', 'disabled');
		} else {
			$(this).attr('readonly', 'readonly');
		}
	});
	$(".deleteProduct").hide();
}

/**
 * Сделать кнопки формы не работоспособными
 * @param form id формы
 * @param element элементы формы которые не нужно устанавливать в disabled
 */
function disableButton(form, elements){
	elements = elements || new Array();
	$("#"+form+" input, #"+form+" button").each( function (i, e) {
		if ($(this).attr('type') == 'submit' || $(this).attr('type') == 'button') {
			if ( ! elements.exists($(this).attr('id'))) $(this).attr('disabled', 'disabled');
		}
	});
}

/**
 * Добавить комментарий к заказу
 */
function addComment () {
	if ( ! empty($("#comments_textComment").val())){
		$("#event").val('addComment');
		$("#orderForm").submit();
	}
}

/**
 * Добавить комментарий менеджера
 */
function addAdminComment() {
	var text = $("#adminComment").val();
	if ( ! empty(text)) {
		var now = new Date();
		var header = now.toLocaleString() +  " " + $("#User_name").val() + ":\n";
		var curr_text = $("#Adm_comment").val();
		text = header + text;
		if( ! empty(curr_text)) text = curr_text + "\n" + text;
		else  text = curr_text + text;
		$("#Adm_comment").val(text);
		$("#adminComment").val("");
	}
}

/**
 * Добавить товар к заказу
 */
function addProduct() {
	var p = $("#productArticle").val();
	//var c = $("#productAmount").val();
	if( ! empty(p) && ! isNaN(p) /*&& !empty(c)  &&  !isNaN(c)*/) {
		$("#productArticle").val(p.trim());
		$("#event").val("AddProduct");
		$("#orderForm").submit();
	} else {
		alert('Введите корректный "Артикул"');
	}
}

/**
 * Удалить товар из заказа
 * @param id артикул товара
 * @param name название товара
 */
function deleteProduct(id, name) {
	var text = 'Вы действительно хотите удалить товар "'+name+'" из заказа?';
	if (confirm(text)) {
		$("#event").val("DeleteProduct");
		$("#Wares_id").val(id);
		$("#orderForm").submit();
	}
}
/**
*
*
*/
function RejectVIPCard() {
	var text = 'Вы уверенны, что клиент отказывается от VIP карты?';
	if (confirm(text)) {
		$("#event").val("RejectVIPCard");
		$("#orderForm").submit();
	}
}

/**
 * Сохранить и установить заказ в выполненый
 */
function performed() {
	//var text = 'Будут созданы задачи необходимые для выполнения заказа. Продолжить?';
	//if (confirm(text)) {
		$("#event").val('Performed');
		$("#orderForm").submit();
	//}
}

function remains() {
		$("#event").val('ShowRemains');
		$("#orderForm").submit();
}

/**
 * Отменить заказ
 */
function cancelOrder() {
	var text = 'Вы действительно хотите отменить заказ?';
	if (confirm(text)) {
		$("#event").val('cancelOrder');
		$("#orderForm").submit();
	}
}

/**
 * Поменять Заказ на предзаказ или наоборот
 */
/**
 * Взять задачу на исполнение
 * @param mode
 */
function setPreorder() {
	$("#event").val('setPreorder');
	$("#orderForm").submit();
}

/**
 * Сменить страницу (для коментариев)
 * @param pagenum
 * @param prefix
 */
function loadPage(pagenum, prefix){
	$('#'+prefix+'page').val(pagenum);
	$('#orderForm').submit();
}

/**
 * Сортировать коментарии
 * @param field
 * @param sorder
 * @param prefix
 */
function sortByField(field, sorder, prefix) {
	$('#'+prefix+'sortBy').val(field);
	$('#'+prefix+'sortOrder').val(sorder);
	$('#orderForm').submit();
}

/**
 * Сделать перерасчет общей стоимости заказа
 */
function reCalculate() {
	var c = 0;
	var total = new Number(0);
	$(".qty").each(function(){
		c = $(this).attr('count');
		var pd = new Number($("#priceDiscount"+c).html());
		var qty = new Number($(this).val());
		if (isNaN(qty) || qty < 1) qty = new Number(1);
		$(this).val(qty);
		var r = new Number(pd * qty);
		$("#sum"+c).html(r.toFixed(2));
		total += r;
	});
	
	var p = new Number($("#DeliveryPrice").val());
	if (isNaN(p) || p < 0) {
		p = new Number(0);
		$("#DeliveryPrice").val(p);
	}
	$("#DeliveryPriceSum").html(p.toFixed(2));
	total += p;
	
	$("#totalPrice").html(total.toFixed(2));
}

function setNewCard() {
// Fatal error: Call to undefined function oci_connect()
/*	if (isNaN($('#User_id').val())) return;
	if ($('#Barcode_pos').val().length != 13) {
		alert('Введите номер карты!');
		return;
	}
	var msg = 'Изменить номер карты аккаунта '+$('#name').val()+' на '+$('#Barcode_pos').val()+'?';
	if (confirm(msg)) {
		var barCode = $('#Barcode_pos').val();
		$('#Barcode_pos').attr('disabled', 'disabled');
		$.post("ajax.php", {"event":"setCard","barCode":barCode,"UserID":$("#User_id").val()},function(data){
			if (data)
			switch (data.result) {
				case -3: alert('Номер карты не зарегистрирован в СПРУТе'); break;
				case -2: alert('Карта уже привязана к другому аккаунту'); break;
				case -1: alert('Неверный формат номера карты'); break;
				default: alert('Карта успешно привязана');
			}
			$('#Barcode_pos').removeAttr('disabled');
		}, 'json');
	}*/
}

/**
 * Устанавливает поля формы заказа в режим "Только для чтения"\"Редактировать"
 * - список товаров
 * - добавление товара
 * - данные об оплате и достаке
 * @param readonly
 */
function changePayState (readonly) {
	$("#paymenyInfo input, #paymenyInfo select").each( function (i, e) {
		var id = $(this).attr('id');
		if (id && id != 'Pay_state' && id != 'Delivery_date_from_hour' && id != 'Delivery_date_from_minutes'
			&& id != 'Delivery_date_to_hour' && id != 'Delivery_date_to_minutes' && id != 'Delivery_date') {
			if (readonly == 1) $(this).attr('disabled', 'disabled');
			else $(this).removeAttr('disabled');
		}
	});
	if (readonly == 1) {
		$(".qty").attr('disabled', 'disabled');
		$("#DeliveryPrice").attr('disabled', 'disabled');
		$(".deleteProduct").hide();
		$("#addProduct_block").hide();
		$("#btnPreorder").hide();
	} else {
		$(".qty").removeAttr('disabled');
		$("#DeliveryPrice").removeAttr('disabled');
		$(".deleteProduct").show();
		$("#addProduct_block").show();
		$("#btnPreorder").show();
	}
}

$(function () {
         $("#User_Search").autocomplete({
             // req: request has all the outgoing info (to server)
             // res: response is a callback used by jQueryUI's autocomplete
             source: "User_Search.php", // url-адрес
              close: function( event, ui ) {
                     var data = $(this).val().split(' ');
		     $("#User_id").html('<option value="">не зарегистрирован</option> <option selected="selected" value="'
			+ data[0] + '">' + data[1] + ' '+ data[2] + ' ('+ data[3] + ')</option>');
    		     $("#btnUpdateContactInfo").removeAttr('disabled');
		     $("#saveOrder").removeAttr('disabled');
             //$("#btnUpdateContactInfo").click();<->
        }
    });
});

/**
 * On document loaded
 */
$(document).ready(function () {

	/**
	 * Event change
	 *
	 * On changed User_id
	 */
	$("#User_id").change( function () {
		// get account information
		$("#btnUpdateContactInfo").removeAttr('disabled');
	});

	/**
	 * On changed Country
	 */
	$("#Country_id").change( function () {
		$.post("ajax.php", { "event" : "GetCitiesCountry", "id" : $(this).val() },
			function(data){
				if (data) {
					var str = "";
					for(var i=0; i<data.length; i++) {
						str += '<option value="'+data[i]['City_id']+'">'+data[i]['Name_RU']+'</option>';
					}
					$("#City_id").html(str);
					$("#City_id").change();
				}
			}, "json");
	});

	/**
	 * On changed City
	 */
	$("#City_id").change( function () {
		$.post("ajax.php", { "event" : "GetDeliveryTypesCity", "id" : $(this).val() },
			function(data){
				if (data) {
					var str = "";
					for(var i=0; i<data.length; i++) {
						str += '<option value="'+data[i]['Delivery_type']+'">'+data[i]['Name_RU']+'</option>';
					}
					$("#Delivery_type").html(str);
					$("#Delivery_type").change();
				}
			}, "json");
		$.post("ajax.php", { "event" : "GetShopsCity", "id" : $(this).val() },
			function(data){
				if (data) {
					var str = "";
					for(var i=0; i<data.length; i++) {
						str += '<option value="'+data[i]['sprut_code']+'">'+data[i]['name_ru']+'</option>';
					}
					$("#Shop_id").html(str);
				}
			}, "json");
	});

	/**
	 * On changed Delivery type
	 */
	$("#Delivery_type").change( function () {
		var id = Math.round($(this).val());
		if (id == 1) {
			$("#Delivery_date_row").show();
		} else {
			$("#Delivery_date_row").hide();
		}
		if (id == 3){
			$("#Shop_id_row").show();
			$("[name=Shop_id]").removeAttr('disabled');
		}
		else {
			$("#Shop_id_row").hide();
			$("[name=Shop_id]").attr('disabled', 'disabled');
		}

		$.post("ajax.php", { "event" : "GetPaymentTypes", "id" : id },
				function(data){
					if (data) {
						var str = "";
						for(var i=0; i<data.length; i++) {
							str += '<option value="'+data[i]['Payment_type']+'">'+data[i]['Name_RU']+'</option>';
						}
						$("#Payment_type").html(str);
						$("#Payment_type").change();
					}
				}, "json");

	});

	/**
	 * On changed qty product
	 */
	$(".qty").change(function(){
		reCalculate();
	});

	/**
	 * On changed DeliveryPrice
	 */
	$("#DeliveryPrice").change(function(){
		reCalculate();
	});

	/**
	 * On changed Barcode
	 */
	$("#Barcode_pos").change(function(){
		setNewCard();
	});

	/**
	 * On changed any input form
	 */
	var disabled = new Array();
	$("#orderForm input, #orderForm textarea, #orderForm select").each(function(i,e){
		var id = $(this).attr('id');
		if (id && ! ["comments_textComment","comments_textComment","productArticle","productAmount"].exists(id)) {
			$("#"+id).change(function(){
				$("#saveOrder").removeAttr('disabled');
				$("#btnGraph").attr('disabled', 'disabled');
				$("#btnPerformed").attr('disabled', 'disabled');
			});
		}
	});

	/**
	 * On changed Pay_state
	 */
	$("#Pay_state").change(function(){
		// changePayState($(this).val());
		$("#event").val('setPayState');
		$("#orderForm").submit();
	});

	/**
	 * On changed legalPerson
	 */
	$("#legalPerson").change(function(){
		$('#companyInfo').toggle();
	});
	
	
	/**
	 * Event click
	 *
	 * On clicked button "Сохранить"
	 */
	$("#saveOrder").click(function(){
		$("#event").val("Save");
		$("#orderForm").submit();
	});
	/**
	*Button "Построить граф"
	*
	*/
	$('#btnGraph').click(function(){
		$("#event").val("Buildgraph");
		$("#orderForm").submit();
		var GWin = window.open('', 'Graph');
		GWin.close();
	});

    $("#btnCreateWFPInvoice").click(function(){
        $("#event").val('CreateWFPInvoice');
        $("#orderForm").submit();
    });

	/**
	 * On clicked button "Выполнено"
	 */
	$("#btnPerformed").click(function(){
		performed();
	});

	$("#btnRemainsOrder").click(function(){
		remains();
	});

	/**
	 * On clicked button "Взять на исполнение"
	 */
	$("#btnSetExecutor").click(function(){
		setExecutor();
	});

	/**
	 * On clicked button "Добавить товар"
	 */
	$("#btnAddProduct").click(function(){
		addProduct();
	});

	/**
	 *  On clicked button "Добавить комментарий" (менеджера)
	 */
	$("#btnAddAdminComment").click(function(){
		addAdminComment();
	});

	/**
	 * On clicked button "Добавить комментарий" (для задачи)
	 */
	$("#btnAddComment").click(function(){
		addComment();
	});

	/**
	 * On clicked button "Удалить товар"
	 */
	$(".deleteProduct").click(function(){
		deleteProduct($(this).attr('waresId'),$(this).attr('waresName'));
	});

	/**
	 * On clicked button "Отмена заказ"
	 */
	$("#btnCancelOrder").click(function(){
		cancelOrder();
	});

	/**
	 * On click button "Предзаказ -> Заказ" или "Заказ -> Предзаказ"
	 */
	$("#btnPreorder").click(function(){
		setPreorder();
	});

	/**
	 * On click button "Обновить информацию"
	 */
	$("#btnUpdateContactInfo").click(function(){
		$.post("ajax.php", { "event" : "GetAccount", "id" : $("#User_id").val() },
			function(data){
				$("#accountInfo input, #accountInfo textarea").each(function(i,e){
					var id = $(this).attr('id');
					if ((id != 'btnUpdateContactInfo' && id != 'User_Search') && (id)) {$("#"+id).val(data[id]);};
				});
				companyInfoDisplay();
			}, "json");
	});

	/**
	 * Скрываем и отображаем поля вкладки "Оплата и доставка"
	 * в зависимости от состояния оплаты
	 */
	changePayState($("#Pay_state").val());

	// Initialize tabs
	$("#tabs").tabs();

	// Tab of the payment and delivery
	paymentInfoDisplay();

	// Tab of the client contacts
	companyInfoDisplay();

	// disabled comments
	$("#Ord_comment").attr('readonly', 'readonly');
	$("#Adm_comment").attr('readonly', 'readonly');

	$.datepicker.regional['my'] = { // Default regional settings
		clearText: 'Очистить', // Display text for clear link
		clearStatus: 'Стереть текущую дату', // Status text for clear link
		closeText: 'Закрыть', // Display text for close link
		closeStatus: 'Закрыть без сохранения', // Status text for close link
		prevText: '&#x3c;Пред', // Display text for previous month link
		prevStatus: 'Предыдущий месяц', // Status text for previous month link
		nextText: 'След&#x3e;', // Display text for next month link
		nextStatus: 'Следующий месяц', // Status text for next month link
		currentText: 'Сегодня', // Display text for current month link
		currentStatus: 'Текущий месяц', // Status text for current month link
		monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
		'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'], // Names of months for drop-down and formatting
		monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'], // For formatting
		monthStatus: 'Показать другой месяц', // Status text for selecting a month
		yearStatus: 'Показать другой год', // Status text for selecting a year
		weekHeader: 'Нед', // Header for the week of the year column
		weekStatus: 'Неделя года', // Status text for the week of the year column
		dayNames: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'], // For formatting
		dayNamesShort: ['Вск', 'Пнд', 'Втр', 'Срд', 'Чтр', 'Птн', 'Суб'], // For formatting
		dayNamesMin: ['Вс','Пд','Вт','Ср','Чт','Пт','Сб'], // Column headings for days starting at Sunday
		dayStatus: 'Установить первым днем недели', // Status text for the day of the week selection
		dateStatus: 'Выбрать день, месяц, год', // Status text for the date selection
		dateFormat: 'dd.mm.yy', // See format options on parseDate
		firstDay: 1, // The first day of the week, Sun = 0, Mon = 1, ...
		initStatus: 'Выбрать дату', // Initial Status text on opening
		isRTL: false // True if right-to-left language, false if left-to-right
	};
	$.datepicker.setDefaults($.datepicker.regional['my']);

	// Initialize DateTimePicker for Delivery_date
	$('#Delivery_date').datepicker({dateFormat:'dd.mm.yy'});


});
