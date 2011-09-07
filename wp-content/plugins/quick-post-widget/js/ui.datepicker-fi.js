/* Finnish initialisation for the jQuery UI date picker plugin. */
/* Written by Mediamonttu. */
jQuery(function($){
	$.datepicker.regional['fi'] = {
		closeText: 'Sulje',
		prevText: '&laquo;Edellinen',
		nextText: 'Seuraava&raquo;',
		currentText: 'T&auml;n&auml;&auml;n',
		monthNames: ['Tammikuu','Helmikuu','Maaliskuu','Huhtikuu','Toukokuu','Kes&auml;kuu',
		'Hein&auml;kuu','Elokuu','Syyskuu','Lokakuu','Marraskuu','Joulukuu'],
		monthNamesShort: ['Tammi','Helmi','Maalis','Huhti','Touko','Kes&auml;',
		'Hein&auml;','Elo','Syys','Loka','Marras','Joulu'],
		dayNames: ['Sunnuntai','Maanantai','Tiistai','Keskiviikko','Torstai','Perjantai','Lauantai'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: ['Su','Ma','Ti','Ke','To','Pe','La'],
		weekHeader: 'Vk',
		dateFormat: 'dd.mm.yy',
		firstDay: 1,
		isRTL: false,
		showMonthAfterYear: false,
		yearSuffix: ''};
	$.datepicker.setDefaults($.datepicker.regional['fi']);
	
	$.timepicker.regional.fi = {
		timeOnlyTitle: 'Valitse aika',
		timeText: 'Aika',
		hourText: 'Tunti',
		minuteText: 'Min',
		secondText: 'Sek',
		currentText: 'Nyt',
		closeText: 'Sulje'};
	$.timepicker.setDefaults($.timepicker.regional.fi);

});
