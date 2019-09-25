function sortByField(field, sorder, prefix) {
	prefix = prefix || '';
	$('#'+prefix+'sortBy').val(field);
	$('#'+prefix+'sortOrder').val(sorder);
	$('#taskslist').submit();
}

function exporttasks(){
	$('#event').val('PrintTasks');
	$('#taskslist').submit();
	$('#event').val('search');
}

function exportCSreport(){
	$('#event').val('ExportCourierOrders');
	$('#taskslist').submit();
	$('#event').val('search');
}

function exportAUPreport(){
	$('#event').val('ExportAutPostDeliveries');
	$('#taskslist').submit();
	$('#event').val('search');
}

function exportTransfersReport(){
	$('#event').val('ExportTransfers');
	$('#taskslist').submit();
	$('#event').val('search');
}

function exportNewPostReport(){
	$('#event').val('ExportNewPostDeliveries');
	$('#taskslist').submit();
	$('#event').val('search');
}

function clear_filter() {
	$('#event').val('clearfilter');
	$('#taskslist').submit();		
}

function setmenu(id) {
	if ($('#'+id).css('display') == 'none') {
		$('#menu'+id).val('0');
	}else{
		$('#menu'+id).val('1');
	}
}
function clearForm(){
	$(':input','#taskslist')
	 .not(':button, :submit, :reset, :hidden')
	 .val('')
	 .removeAttr('checked')
	 .removeAttr('selected');
	//$('#varDepartmentID').val('');	
}

function setfilter(menu,state,dep,executor){
	clearForm();
	$('#menuBlock').val(menu);
	$('#intState').val(state);	
	$('#varDepartmentID').val(dep);
	$('#intExecutorID').val(executor);
	$('#taskslist').submit();
}

function loadPage(pagenum, prefix){
	$('#'+prefix+'page').val(pagenum);
	$('#tasks_page').val(pagenum);
	$('#taskslist').submit();
}

function ShowPopup(elem) {
	var popup = $(elem).find('.popup');
	var blocks = $(popup).children('.popup_text');
	var show = false;
	for (var i=0; i < blocks.length; i++) {
		if ($(blocks[i]).html().toString().trim().length > 0) {
			show = true;
			break;
		}
	}
	if (show) {
		$(popup).css('left', ($(elem).position().left + 15) + 'px').css('top', ($(elem).position().top + 15) + 'px');
		$(popup).show();
	}
}

function HidePopup(elem) {
	$(elem).find('.popup').hide();
}

$(document).ready(function(){
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
	
	$('#varCreationFrom').datepicker({dateFormat:'dd.mm.yy'});
	$('#varCreationTo').datepicker({dateFormat:'dd.mm.yy'});
	$('#varEndFrom').datepicker({dateFormat:'dd.mm.yy'});
	$('#varEndTo').datepicker({dateFormat:'dd.mm.yy'});
	$('#contentdivobj').height($('#contenttd').height() - 38);
	$('.comment_popup').bind({
		mouseenter: function() {
			ShowPopup(this);
		},
		mouseleave: function() {
			HidePopup(this);
		}
	});
});