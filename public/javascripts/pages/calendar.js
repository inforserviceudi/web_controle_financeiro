
(function( $ ) {

	'use strict';

	var initCalendar = function() {
		var $calendar = $('#calendar');
		var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();

		$calendar.fullCalendar({
            locale: 'pt-br',
			header: {
				left: 'title',
				// right: 'prev,today,next,basicDay,basicWeek,month'
				right: ''
			},
			timeFormat: 'h:mm',
			titleFormat: {
				month: 'MMMM YYYY',      // September 2009
			    week: "MMM d YYYY",      // Sep 13 2009
			    day: 'dddd, MMM d, YYYY' // Tuesday, Sep 8, 2009
			},
			themeButtonIcons: {
				prev: 'fa fa-caret-left',
				next: 'fa fa-caret-right',
			},
			editable: true,
			droppable: false, // this allows things to be dropped onto the calendar !!!
			events: [
                {
                    start: '2023-01-23',
                    end: '2023-01-23',
                }
            ],
		});

		// FIX INPUTS TO BOOTSTRAP VERSIONS
		var $calendarButtons = $calendar.find('.fc-header-right > span');
		$calendarButtons
			.filter('.fc-button-prev, .fc-button-today, .fc-button-next')
				.wrapAll('<div class="btn-group mt-sm mr-md mb-sm ml-sm"></div>')
				.parent()
				.after('<br class="hidden"/>');

		$calendarButtons
			.not('.fc-button-prev, .fc-button-today, .fc-button-next')
				.wrapAll('<div class="btn-group mb-sm mt-sm"></div>');

		$calendarButtons
			.attr({ 'class': 'btn btn-sm btn-default' });
	};

	$(function() {
		initCalendar();
	});

}).apply(this, [ jQuery ]);
