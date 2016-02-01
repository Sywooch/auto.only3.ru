/**
 * @version: 2.0.11
 * @author: Dan Grossman http://www.dangrossman.info/
 * @copyright: Copyright (c) 2012-2015 Dan Grossman. All rights reserved.
 * @license: Licensed under the MIT license. See http://www.opensource.org/licenses/mit-license.php
 * @website: https://www.improvely.com/
 */

(function(root, factory) {

    if (typeof define === 'function' && define.amd) {
        define(['moment', 'jquery', 'exports'], function(momentjs, $, exports) {
            root.daterangepicker = factory(root, exports, momentjs, $);
        });

    } else if (typeof exports !== 'undefined') {
        var momentjs = require('moment');
        var jQuery = window.jQuery;
        if (jQuery === undefined) {
            try {
                jQuery = require('jquery');
            } catch (err) {
                if (!jQuery) throw new Error('jQuery dependency not found');
            }
        }

        factory(root, exports, momentjs, jQuery);

        // Finally, as a browser global.
    } else {
        root.daterangepicker = factory(root, {}, root.moment || moment, (root.jQuery || root.Zepto || root.ender || root.$));
    }

}(this, function(root, daterangepicker, moment, $) {

    var DateRangePicker = function(element, options, cb) {

        this.cancelUrl = '/profile/system/cancel-reserve?id=';
        this.adminMode = false;
        this.lastClickDate = '';
        //default settings for options
        this.parentEl = 'body';
        this.element = $(element);
        this.startDate = moment().startOf('day');
        this.endDate = moment().endOf('day');

        this.realStartDate = '';
        this.realEndDate = '';

        this.realStartDateTime = '';
        this.realEndDateTime = '';

        this.timeZone = moment().utcOffset();
        this.minDate = false;
        this.maxDate = false;
        this.dateLimit = false;
        this.autoApply = false;
        this.singleDatePicker = false;
        this.showDropdowns = false;
        this.showWeekNumbers = false;
        this.timePicker = false;
        this.timePicker24Hour = false;
        this.timePickerIncrement = 1;
        this.timePickerSeconds = false;
        this.linkedCalendars = true;
        this.autoUpdateInput = true;
        this.ranges = {};

        this.timeStartHours = 0;
        this.timeEndHours = 0;

        this.opens = 'right';
        if (this.element.hasClass('pull-right'))
            this.opens = 'left';

        this.drops = 'down';
        if (this.element.hasClass('dropup'))
            this.drops = 'up';

        this.buttonClasses = 'btn btn-sm';
        this.applyClass = 'btn-success';
        this.cancelClass = 'btn-default';

        this.positionFixed = false;

        this.locale = {
            format: 'MM/DD/YYYY',
            separator: ' - ',
            applyLabel: 'Apply',
            cancelLabel: 'Cancel',
            weekLabel: 'W',
            customRangeLabel: 'Custom Range',
            daysOfWeek: moment.weekdaysMin(),
            monthNames: moment.monthsShort(),
            firstDay: moment.localeData().firstDayOfWeek()
        };

        this.callback = function() { };

        //some state information
        this.isShowing = false;
        this.leftCalendar = {};
        this.rightCalendar = {};

        this.rightHalfReservedDates = [];
        this.leftHalfReservedDates = [];
        this.fullReservedDates = [];
        this.leftrightHalfReservedDates = [];

        this.resultReservedDates = {};
        this.selectedClickTime = [];

        //custom options from user
        if (typeof options !== 'object' || options === null)
            options = {};

        //allow setting options with data attributes
        //data-api options will be overwritten with custom javascript options
        options = $.extend(this.element.data(), options);

        //html template for the picker UI
        if (typeof options.template !== 'string')
            options.template = '<div class="main daterangepicker dropdown-menu">' +
//            options.template = '<div class="main daterangepicker ">' +

                '<div class="calendar left">' +
//                '<div class="daterangepicker_input">' +
//                '<input class="input-mini" type="text" name="daterangepicker_start" value="" />' +
//                '<i class="fa fa-calendar glyphicon glyphicon-calendar"></i>' +
//                '<div class="calendar-time">' +
//                '<div></div>' +
//                '<i class="fa fa-clock-o glyphicon glyphicon-time"></i>' +
//                '</div>' +
//               '</div>' +
                '<div class="calendar-table"></div>' +
                '</div>' +
                '<div class="calendar right">' +
//                '<div class="daterangepicker_input">' +
//                '<input class="input-mini" type="text" name="daterangepicker_end" value="" />' +
//                '<i class="fa fa-calendar glyphicon glyphicon-calendar"></i>' +
    //                '<div class="calendar-time">' +
        //                '<div></div>' +
        //                '<i class="fa fa-clock-o glyphicon glyphicon-time"></i>' +
    //                '</div>' +
//                '</div>' +
                '<div class="calendar-table"></div>' +
                '</div>' +


                '<div class="timerangepicker startTimer" data-timertype="startTimer">' +
                '</div>' +

                '<div class="timerangepicker endTimer" data-timertype="endTimer">' +
                '</div>' +

                '<div class="ranges">' +
//                '<div class="range_inputs">' +
//                '<button class="applyBtn" disabled="disabled" type="button"></button> ' +
//                '<button class="cancelBtn" type="button"></button>' +
//                '</div>' +
                '</div>' +

                '<div id="payInfo">' +
                '</div>' +

                '<div id="reservedInfo">' +
//                '<div class="range_inputs">' +
//                '<button class="applyBtn" disabled="disabled" type="button"></button> ' +
//                '<button class="cancelBtn" type="button"></button>' +
//                '</div>' +
                '</div>' +


                '</div>';

        this.parentEl = (options.parentEl && $(options.parentEl).length) ? $(options.parentEl) : $(this.parentEl);
        this.container = $(options.template).appendTo(this.parentEl);

        //
        // handle all the possible options overriding defaults
        //

        if (typeof options.locale === 'object') {

            if (typeof options.locale.format === 'string')
                this.locale.format = options.locale.format;

            if (typeof options.locale.separator === 'string')
                this.locale.separator = options.locale.separator;

            if (typeof options.locale.daysOfWeek === 'object')
                this.locale.daysOfWeek = options.locale.daysOfWeek.slice();

            if (typeof options.locale.monthNames === 'object')
                this.locale.monthNames = options.locale.monthNames.slice();

            if (typeof options.locale.monthNamesRod === 'object')
                this.locale.monthNamesRod = options.locale.monthNamesRod.slice();

            if (typeof options.locale.firstDay === 'number')
                this.locale.firstDay = options.locale.firstDay;

            if (typeof options.locale.applyLabel === 'string')
                this.locale.applyLabel = options.locale.applyLabel;

            if (typeof options.locale.cancelLabel === 'string')
                this.locale.cancelLabel = options.locale.cancelLabel;

            if (typeof options.locale.weekLabel === 'string')
                this.locale.weekLabel = options.locale.weekLabel;

            if (typeof options.locale.customRangeLabel === 'string')
                this.locale.customRangeLabel = options.locale.customRangeLabel;

        }

        if (typeof options.startDate === 'string')
            this.startDate = moment(options.startDate, this.locale.format);

        if (typeof options.endDate === 'string')
            this.endDate = moment(options.endDate, this.locale.format);

        if (typeof options.minDate === 'string')
            this.minDate = moment(options.minDate, this.locale.format);

        if (typeof options.maxDate === 'string')
            this.maxDate = moment(options.maxDate, this.locale.format);

        if (typeof options.startDate === 'object')
            this.startDate = moment(options.startDate);

        if (typeof options.endDate === 'object')
            this.endDate = moment(options.endDate);

        if (typeof options.minDate === 'object')
            this.minDate = moment(options.minDate);

        if (typeof options.maxDate === 'object')
            this.maxDate = moment(options.maxDate);

        // sanity check for bad options
        if (this.minDate && this.startDate.isBefore(this.minDate))
            this.startDate = this.minDate.clone();

        // sanity check for bad options
        if (this.maxDate && this.endDate.isAfter(this.maxDate))
            this.endDate = this.maxDate.clone();

        if (typeof options.applyClass === 'string')
            this.applyClass = options.applyClass;

        if (typeof options.cancelClass === 'string')
            this.cancelClass = options.cancelClass;

        if (typeof options.dateLimit === 'object')
            this.dateLimit = options.dateLimit;

        if (typeof options.opens === 'string')
            this.opens = options.opens;

        if (typeof options.drops === 'string')
            this.drops = options.drops;

        if (typeof options.positionFixed === 'boolean')
            this.positionFixed = options.positionFixed;

        if (typeof options.adminMode === 'boolean')
            this.adminMode = options.adminMode;

        if (typeof options.IsClientPayCom === 'boolean')
            this.IsClientPayCom = options.IsClientPayCom;

        if (typeof options.showWeekNumbers === 'boolean')
            this.showWeekNumbers = options.showWeekNumbers;

        if (typeof options.buttonClasses === 'string')
            this.buttonClasses = options.buttonClasses;

        if (typeof options.buttonClasses === 'object')
            this.buttonClasses = options.buttonClasses.join(' ');

        if (typeof options.showDropdowns === 'boolean')
            this.showDropdowns = options.showDropdowns;

        if (typeof options.singleDatePicker === 'boolean') {
            this.singleDatePicker = options.singleDatePicker;
            if (this.singleDatePicker)
                this.endDate = this.startDate.clone();
        }

        if (typeof options.timePicker === 'boolean')
            this.timePicker = options.timePicker;

        if (typeof options.timePickerSeconds === 'boolean')
            this.timePickerSeconds = options.timePickerSeconds;

        if (typeof options.timePickerIncrement === 'number')
            this.timePickerIncrement = options.timePickerIncrement;

        if (typeof options.timePicker24Hour === 'boolean')
            this.timePicker24Hour = options.timePicker24Hour;

        if (typeof options.autoApply === 'boolean')
            this.autoApply = options.autoApply;

        if (typeof options.autoUpdateInput === 'boolean')
            this.autoUpdateInput = options.autoUpdateInput;

        if (typeof options.linkedCalendars === 'boolean')
            this.linkedCalendars = options.linkedCalendars;

        if (typeof options.isInvalidDate === 'function')
            this.isInvalidDate = options.isInvalidDate;

        if (typeof options.timeStartHours === 'number')
            this.timeStartHours = options.timeStartHours;

        if (typeof options.timeEndHours === 'number')
            this.timeEndHours = options.timeEndHours;

            // update day names order to firstDay
        if (this.locale.firstDay != 0) {
            var iterator = this.locale.firstDay;
            while (iterator > 0) {
                this.locale.daysOfWeek.push(this.locale.daysOfWeek.shift());
                iterator--;
            }
        }


        if(typeof options.setReservedDates === 'object'){
            this.setReservedDates(options.setReservedDates);
        }

        if(typeof options.setPayType === 'string'){
            this.PayType = options.setPayType;
        }

        if(typeof options.setPaySumm === 'string'){
            this.PaySumm = options.setPaySumm;
        }

        if(typeof options.setPercent === 'string'){
            this.Percent = options.setPercent;
        }

        if(typeof options.setCost1 === 'string'){
            this.cost1 = options.setCost1;
        }

        if(typeof options.setCost2 === 'string'){
            this.cost2 = options.setCost2;
        }

        if(typeof options.setCost8 === 'string'){
            this.cost8 = options.setCost8;
        }


        var start, end, range;

        //if no start/end dates set, check if an input element contains initial values
        if (typeof options.startDate === 'undefined' && typeof options.endDate === 'undefined') {
            if ($(this.element).is('input[type=text]')) {
                var val = $(this.element).val(),
                    split = val.split(this.locale.separator);

                start = end = null;

                if (split.length == 2) {
                    start = moment(split[0], this.locale.format);
                    end = moment(split[1], this.locale.format);
                } else if (this.singleDatePicker && val !== "") {
                    start = moment(val, this.locale.format);
                    end = moment(val, this.locale.format);
                }
                if (start !== null && end !== null) {
                    this.setStartDate(start);
                    this.setEndDate(end);
                }
            }
        }

        // bind the time zone used to build the calendar to either the timeZone passed in through the options or the zone of the startDate (which will be the local time zone by default)
        if (typeof options.timeZone === 'string' || typeof options.timeZone === 'number') {
            if (typeof options.timeZone === 'string' && typeof moment.tz !== 'undefined') {
                this.timeZone = moment.tz.zone(options.timeZone).parse(new Date) * -1;  // Offset is positive if the timezone is behind UTC and negative if it is ahead.
            } else {
                this.timeZone = options.timeZone;
            }
            this.startDate.utcOffset(this.timeZone);
            this.endDate.utcOffset(this.timeZone);
        } else {
            this.timeZone = moment(this.startDate).utcOffset();
        }

        if (typeof options.ranges === 'object') {
            for (range in options.ranges) {

                if (typeof options.ranges[range][0] === 'string')
                    start = moment(options.ranges[range][0], this.locale.format);
                else
                    start = moment(options.ranges[range][0]);

                if (typeof options.ranges[range][1] === 'string')
                    end = moment(options.ranges[range][1], this.locale.format);
                else
                    end = moment(options.ranges[range][1]);

                // If the start or end date exceed those allowed by the minDate or dateLimit
                // options, shorten the range to the allowable period.
                if (this.minDate && start.isBefore(this.minDate))
                    start = this.minDate.clone();

                var maxDate = this.maxDate;
                if (this.dateLimit && start.clone().add(this.dateLimit).isAfter(maxDate))
                    maxDate = start.clone().add(this.dateLimit);
                if (maxDate && end.isAfter(maxDate))
                    end = maxDate.clone();

                // If the end of the range is before the minimum or the start of the range is
                // after the maximum, don't display this range option at all.
                if ((this.minDate && end.isBefore(this.minDate)) || (maxDate && start.isAfter(maxDate)))
                    continue;

                this.ranges[range] = [start, end];
            }

            var list = '<ul>';
            for (range in this.ranges) {
                list += '<li>' + range + '</li>';
            }
            list += '<li>' + this.locale.customRangeLabel + '</li>';
            list += '</ul>';
            this.container.find('.ranges').prepend(list);
        }

        if (typeof cb === 'function') {
            this.callback = cb;
        }

        if (!this.timePicker ) {
            this.startDate = this.startDate.startOf('day');
            this.endDate = this.endDate.endOf('day');
//            this.container.find('.calendar-time').hide();
        }

        //can't be used together for now
        if (this.timePicker && this.autoApply)
            this.autoApply = false;

        if (this.autoApply && typeof options.ranges !== 'object') {
            this.container.find('.ranges').hide();
        } else if (this.autoApply) {
            this.container.find('.applyBtn, .cancelBtn').addClass('hide');
        }

        if (this.singleDatePicker) {
            this.container.addClass('single');
            this.container.find('.calendar.left').addClass('single');
            this.container.find('.calendar.left').show();
            this.container.find('.calendar.right').hide();
            this.container.find('.daterangepicker_input input, .daterangepicker_input i').hide();
            if (!this.timePicker) {
                this.container.find('.ranges').hide();
            }
        }

        if (typeof options.ranges === 'undefined' && !this.singleDatePicker) {
            this.container.addClass('show-calendar');
        }

        this.container.addClass('opens' + this.opens);

        //swap the position of the predefined ranges if opens right
        if (typeof options.ranges !== 'undefined' && this.opens == 'right') {
            var ranges = this.container.find('.ranges');
            var html = ranges.clone();
            ranges.remove();
            this.container.find('.calendar.left').parent().prepend(html);
        }

        //apply CSS classes and labels to buttons
        this.container.find('.applyBtn, .cancelBtn').addClass(this.buttonClasses);
        if (this.applyClass.length)
            this.container.find('.applyBtn').addClass(this.applyClass);
        if (this.cancelClass.length)
            this.container.find('.cancelBtn').addClass(this.cancelClass);
        this.container.find('.applyBtn').html(this.locale.applyLabel);
        this.container.find('.cancelBtn').html(this.locale.cancelLabel);

        //
        // event listeners
        //

        this.container.find('.timerangepicker')
            .on('click.daterangepicker', 'li', $.proxy(this.clickTimeRange, this))
            .on('mouseenter.daterangepicker', 'div', $.proxy(this.hoverTimer, this))
            .on('mouseenter.daterangepicker', 'ul', $.proxy(this.hoverTimer, this));

        this.container.find('.calendar')
            .on('click.daterangepicker', '.prev', $.proxy(this.clickPrev, this))
            .on('click.daterangepicker', '.next', $.proxy(this.clickNext, this))
            .on('click.daterangepicker', 'td.available', $.proxy(this.clickDate, this))
            .on('mouseenter.daterangepicker', 'td.available', $.proxy(this.hoverDate, this))
//            .on('mouseleave.daterangepicker', 'td.available', $.proxy(this.updateFormInputs, this))
            .on('change.daterangepicker', 'select.yearselect', $.proxy(this.monthOrYearChanged, this))
            .on('change.daterangepicker', 'select.monthselect', $.proxy(this.monthOrYearChanged, this))
            .on('change.daterangepicker', 'select.hourselect,select.minuteselect,select.secondselect,select.ampmselect', $.proxy(this.timeChanged, this))

//            .on('click.daterangepicker', '.daterangepicker_input input', $.proxy(this.showCalendars, this))
            //.on('keyup.daterangepicker', '.daterangepicker_input input', $.proxy(this.formInputsChanged, this))
//            .on('change.daterangepicker', '.daterangepicker_input input', $.proxy(this.formInputsChanged, this));

        this.container.find('.ranges')
            .on('click.daterangepicker', 'button.applyBtn', $.proxy(this.clickApply, this))
            .on('click.daterangepicker', 'button.cancelBtn', $.proxy(this.clickCancel, this))
            .on('click.daterangepicker', 'li', $.proxy(this.clickRange, this))
            .on('mouseenter.daterangepicker', 'li', $.proxy(this.hoverRange, this))
//            .on('mouseleave.daterangepicker', 'li', $.proxy(this.updateFormInputs, this));

        if (this.element.is('input')) {
            this.element.on({
                'click.daterangepicker': $.proxy(this.show, this),
                'focus.daterangepicker': $.proxy(this.show, this),
                'keyup.daterangepicker': $.proxy(this.elementChanged, this),
                'keydown.daterangepicker': $.proxy(this.keydown, this)
            });
        } else {
            this.element.on('click.daterangepicker', $.proxy(this.toggle, this));
        }

        //
        // if attached to a text input, set the initial value
        //

        if (this.element.is('input') && !this.singleDatePicker && this.autoUpdateInput) {
            this.element.val(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format));
            this.element.trigger('change');
        } else if (this.element.is('input') && this.autoUpdateInput) {
            this.element.val(this.startDate.format(this.locale.format));
            this.element.trigger('change');
        }

    };

    DateRangePicker.prototype = {

        constructor: DateRangePicker,

        setReservedDates: function (reservedPeriods){

            //setReservedDates method
            if(typeof reservedPeriods === 'object'){

                /*******опредяем свободное время ***********/

                var leftrightHalf = [];
                var leftrightHalfMiddle = [];

                var leftHalfReservedDates = [];
                var rightHalfReservedDates = [];

                var fullReservedDates = [];

                var notSortedReservedDates = [];
                $.each(reservedPeriods, function( ind, val ) {

                    if (val.rent_from && val.rent_to) {

                        var rentFrom = moment(val.rent_from);
                        var rentTo   = moment(val.rent_to);

                        var daysDiff = Math.abs(moment(val.rent_to).startOf('day').diff(moment(val.rent_from).startOf('day'), 'days'));

                        var dateIndexFull = rentFrom.clone().format('YYYY-MM-DD');

                        for (var i = 0; i <= daysDiff; i++) {

                            if(i == 0 || i == daysDiff) {
                                if (!notSortedReservedDates[dateIndexFull])
                                    notSortedReservedDates[dateIndexFull] = [];
                                notSortedReservedDates[dateIndexFull].push({rent_from: rentFrom, rent_to: rentTo});

                                if (!notSortedReservedDates[dateIndexFull].rentedPeriods)
                                    notSortedReservedDates[dateIndexFull].rentedPeriods = [];

                                notSortedReservedDates[dateIndexFull].rentedPeriods.push(val);

                            } else {
                                if (!fullReservedDates[dateIndexFull])
                                    fullReservedDates[dateIndexFull] = [];
                                fullReservedDates[dateIndexFull].push({rent_from: rentFrom, rent_to: rentTo});

                                if (!fullReservedDates[dateIndexFull].rentedPeriods)
                                    fullReservedDates[dateIndexFull].rentedPeriods = [];

                                fullReservedDates[dateIndexFull].rentedPeriods.push(val);

                            }
                            dateIndexFull = moment(dateIndexFull).add(1,'day').format('YYYY-MM-DD');
                        }

                    }

                });

                //определяем свободное время для дней и производим оконачательное распределение периодов
                for (var dateIndex in notSortedReservedDates) {
                    //добавление таймера
                    notSortedReservedDates[dateIndex].timePeriods = this.getTimer(dateIndex);
                }

                //определение занятого времни
                for (var dateIndex in notSortedReservedDates) {
                    var selected = notSortedReservedDates[dateIndex];
                    var periods = selected.timePeriods;

                    var disabled = true;
                    for (var rIndex=0; rIndex < selected.length; rIndex++) {
                        for (var i = 0; i < periods.length; i++) {
                            if (periods[i].datetime >= selected[rIndex].rent_from && periods[i].datetime <= selected[rIndex].rent_to) {
                                notSortedReservedDates[dateIndex].timePeriods[i].disabled = 1;
                            }
                        }
                    }

                    //проход по всем периодам, проверка на полноую занятость дня
                    for (var tIndex in notSortedReservedDates[dateIndex].timePeriods) {
                        var timeP = notSortedReservedDates[dateIndex].timePeriods[tIndex];
                        if(timeP.disabled != 1){
                            disabled = false;
                        }
                    }

                    notSortedReservedDates[dateIndex].disabled = disabled;//если нет свободных периодов
                }

//                console.log(periods);
//                console.log(notSortedReservedDates);

                //определения стиля резерва
                for (var dateIndex in notSortedReservedDates) {

                    var selected = notSortedReservedDates[dateIndex];
                    var periods = selected.timePeriods;
                    var lastPeriod = periods.length-1;

                    if(selected.disabled){
                        fullReservedDates[dateIndex] = selected;
                    } else if(periods[0].disabled && periods[lastPeriod].disabled){
                        leftrightHalf[dateIndex] = selected;
                    } else if(periods[0].disabled){
                        leftHalfReservedDates[dateIndex] = selected;
                    } else if(periods[lastPeriod].disabled){
                        rightHalfReservedDates[dateIndex] = selected;
                    } else {
                        leftrightHalfMiddle[dateIndex] = selected;
                    }

                }

            }

            this.resultReservedDates['reserved-left-half'] = leftHalfReservedDates;
            this.resultReservedDates['reserved-right-half'] = rightHalfReservedDates;

            this.resultReservedDates['reserved-left-right'] = leftrightHalf;
            this.resultReservedDates['reserved-middle'] = leftrightHalfMiddle;

            this.resultReservedDates['reserved-full'] = fullReservedDates;

            /**************************************************************************************/


        },

        setStartDate: function (startDate) {
            if (typeof startDate === 'string')
                this.startDate = moment(startDate, this.locale.format).utcOffset(this.timeZone);

            if (typeof startDate === 'object')
                this.startDate = moment(startDate);

            if (!this.timePicker)
                this.startDate = this.startDate.startOf('day');

            if (this.timePicker && this.timePickerIncrement)
                this.startDate.minute(Math.round(this.startDate.minute() / this.timePickerIncrement) * this.timePickerIncrement);

            if (this.minDate && this.startDate.isBefore(this.minDate))
                this.startDate = this.minDate;

            if (this.maxDate && this.startDate.isAfter(this.maxDate))
                this.startDate = this.maxDate;

            if (!this.isShowing)
                this.updateElement();

            this.updateMonthsInView();
        },

        getTimer: function(dateIndex){
            //добавление таймера
            var timePeriods = [];
            for (var i = this.timeStartHours; i <= this.timeEndHours; i++) {
                var i_in_24 = i;
                var time = moment(dateIndex).startOf('day').clone().hour(i_in_24);

                for (var n = 0; n < 60; n += this.timePickerIncrement) {
                    var padded = n < 10 ? '0' + n : n;
                    var time = time.clone().minute(n);
                    var status = 'enabled';

                    var label = i_in_24+':'+padded;
                    timePeriods.push({label:label,datetime:time, status:status});
                }
            }

            return timePeriods;

        },


        setEndDate: function (endDate) {
            if (typeof endDate === 'string')
                this.endDate = moment(endDate, this.locale.format).utcOffset(this.timeZone);

            if (typeof endDate === 'object')
                this.endDate = moment(endDate);

            if (!this.timePicker)
                this.endDate = this.endDate.endOf('day');

            if (this.timePicker && this.timePickerIncrement)
                this.endDate.minute(Math.round(this.endDate.minute() / this.timePickerIncrement) * this.timePickerIncrement);

            if (this.endDate.isBefore(this.startDate))
                this.endDate = this.startDate.clone();

            if (this.maxDate && this.endDate.isAfter(this.maxDate))
                this.endDate = this.maxDate;

            if (this.dateLimit && this.startDate.clone().add(this.dateLimit).isBefore(this.endDate))
                this.endDate = this.startDate.clone().add(this.dateLimit);

            if (!this.isShowing)
                this.updateElement();

            this.updateMonthsInView();
        },

        isInvalidDate: function () {
            return false;
        },

        findReserved: function(col){

            var resReserved = {class:'',timePeriods: [], rentedPeriods:[]};
            var dayIndex = col.format('YYYY-MM-DD');

            top:
            for (var typeIndex in this.resultReservedDates){
                if(dayIndex in this.resultReservedDates[typeIndex]){
                    resReserved.class = typeIndex;
                    resReserved.timePeriods = this.resultReservedDates[typeIndex][dayIndex].timePeriods;
                    resReserved.rentedPeriods = this.resultReservedDates[typeIndex][dayIndex].rentedPeriods;

                    break top;
                }
//                for (var dateIndex in this.resultReservedDates[typeIndex]){}
            }

            return resReserved;

        },

        //check selected period if range is renterd show allert
        checkSelect: function () {

            var resetDates = false;//reset if find

            var leftCalendar = this.leftCalendar;
            var rightCalendar = this.rightCalendar;

            var datePicker = this;

            if (this.realEndDate && this.realStartDate && !this.realEndDate.isSame(this.realStartDate, 'day')) {

                var realStartDate = this.realStartDate;
                var realEndDate = this.realEndDate;

                //todo перееделать на перебор по датам
                inRange = this.container.find('.in-range');
                inRange.each(function(index, el) {

                    var title = $(el).attr('data-title');
                    var row = title.substr(1, 1);
                    var col = title.substr(3, 1);
                    var cal = $(el).parents('.calendar');
                    var dt = cal.hasClass('left') ? leftCalendar.calendar[row][col] : rightCalendar.calendar[row][col];

                    //пытаемся найти дату в зарезервированных
                    var isReserved = datePicker.findReserved(dt);

                    if(isReserved.class){
                        resetDates = true;
                        return true;
                    }
                });

                if (resetDates) {
                    this.resetSelected();
                } else {

                    var isStartReserved = datePicker.findReserved(this.realStartDate);
                    if(isStartReserved.class){
                        if(isStartReserved.class != 'reserved-left-half' && isStartReserved.class != "reserved-middle"){
                            resetDates = true;
                        }
                    }

                    var isEndReserved = datePicker.findReserved(this.realEndDate);
                    if(isEndReserved.class){
                        if(isEndReserved.class != 'reserved-right-half' && isEndReserved.class != "reserved-middle"){
                            resetDates = true;
                        }
                    }
                }

            }

            return resetDates;
        },

        resetSelected: function(){

            this.realEndDate = '';
            this.realStartDate = '';

            if (this.endDate) {
                this.container.find('input[name=daterangepicker_start]').val(this.startDate.format(this.locale.format));
            } else {
                this.container.find('input[name=daterangepicker_end]').val('');
            }

            this.endDate = null;

        },

        updateView: function () {

            if (this.realEndDate && this.realStartDate) {
                this.container.find('.right .calendar-time select').removeAttr('disabled').removeClass('disabled');
                this.container.find('.left .calendar-time select').removeAttr('disabled').removeClass('disabled');
            } else if(this.realStartDate){
                this.container.find('.left .calendar-time select').removeAttr('disabled').removeClass('disabled');
                this.container.find('.right .calendar-time select').attr('disabled', 'disabled').addClass('disabled');
            } else {
                this.container.find('.left .calendar-time select').attr('disabled', 'disabled').addClass('disabled');
                this.container.find('.right .calendar-time select').attr('disabled', 'disabled').addClass('disabled');
            }

            if (this.realEndDate) {
                this.container.find('input[name="daterangepicker_end"]').removeClass('active');
                this.container.find('input[name="daterangepicker_start"]').addClass('active');
            } else {
                this.container.find('input[name="daterangepicker_end"]').addClass('active');
                this.container.find('input[name="daterangepicker_start"]').removeClass('active');
            }

            this.updateMonthsInView();
            this.updateCalendars();
            this.updateFormInputs();
        },

        renderTimesList: function(side) {

            var html = '';

            if(side == 'startTimer') {
                var labelText = 'Выберите время начала:';
                var dateIndex = this.realStartDate;
            } else {
                var labelText = 'Выберите время окончания:';
                var dateIndex = this.realEndDate;
            }

            if(side == 'startTimer') {
                //html = '<div class="des-calendar-top">2. Выберите время начала аренды</div>';

                var labelText = '<div class="des-calendar-top">Дата начала аренды ' + moment(this.realStartDate).format('DD.MM.YYYY')+ ' , <span class="startTimeLabel">выберите время:</span>';
                var dateIndex = this.realStartDate;
            } else {

                //html = '<div class="des-calendar-top">3. Выберите время окончания аренды</div>';

                var labelText = '<div class="des-calendar-top">Дата окончания аренды ' + moment(this.realEndDate).format('DD.MM.YYYY')+ ' , <span class="endTimeLabel">выберите время:</span>';
                var dateIndex = this.realEndDate;
            }


            var currentDate = this.findReserved(dateIndex);

            if(currentDate.class){
                var timePeriods = currentDate.timePeriods;
            } else{
                var timePeriods = this.getTimer(dateIndex);
            }

            var findDisabled = false;//найдем первую задисабленную ячейку
            for (var i = 0; i < timePeriods.length; i++) {
                var period = timePeriods[i];
                var disabledflag = period.disabled;

                if(disabledflag) {
                    var findDisabled = true;
                    var disabledClass = 'disabled'
                } else {
                    var disabledClass = '';

                    if(this.realStartDate && this.realEndDate && currentDate.class == 'reserved-middle') {

                        if (side == 'startTimer') {
                            if(!findDisabled)
                                var disabledClass = 'disabled select';
                        } else {
                            if(findDisabled)
                                var disabledClass = 'disabled select';
                        }
                    }
                }

                html = html + '<li class="timeList ' + disabledClass + '" data-time="'+period.datetime+'">' + period.label + '</li>';
            }

            if(html){
                html = '<div id="list-timer-label-'+side+'">'+labelText+'</div><ul class="list-timer">' + html + '</ul>';
            }

            this.container.find('.'+ side).html(html);
            this.container.find('.'+ side).show();

            return true;
        },

        updateMonthsInView: function () {
            if (this.endDate) {
                //if both dates are visible already, do nothing
                if (this.leftCalendar.month && this.rightCalendar.month &&
                    (this.startDate.format('YYYY-MM') == this.leftCalendar.month.format('YYYY-MM') || this.startDate.format('YYYY-MM') == this.rightCalendar.month.format('YYYY-MM'))
                    &&
                    (this.endDate.format('YYYY-MM') == this.leftCalendar.month.format('YYYY-MM') || this.endDate.format('YYYY-MM') == this.rightCalendar.month.format('YYYY-MM'))
                ) {
                    return;
                }

                this.leftCalendar.month = this.startDate.clone().date(2);
                if (!this.linkedCalendars && (this.endDate.month() != this.startDate.month() || this.endDate.year() != this.startDate.year())) {
                    this.rightCalendar.month = this.endDate.clone().date(2);
                } else {
                    this.rightCalendar.month = this.startDate.clone().date(2).add(1, 'month');
                }

            } else {
                if (this.leftCalendar.month.format('YYYY-MM') != this.startDate.format('YYYY-MM') && this.rightCalendar.month.format('YYYY-MM') != this.startDate.format('YYYY-MM')) {
                    this.leftCalendar.month = this.startDate.clone().date(2);
                    this.rightCalendar.month = this.startDate.clone().date(2).add(1, 'month');
                }
            }
        },

        updateCalendars: function () {

            if (this.timePicker) {
                var hour, minute, second;
                if (this.endDate) {
                    hour = parseInt(this.container.find('.left .hourselect').val(), 10);
                    minute = parseInt(this.container.find('.left .minuteselect').val(), 10);
                    second = this.timePickerSeconds ? parseInt(this.container.find('.left .secondselect').val(), 10) : 0;
                    if (!this.timePicker24Hour) {
                        var ampm = this.container.find('.left .ampmselect').val();
                        if (ampm === 'PM' && hour < 12)
                            hour += 12;
                        if (ampm === 'AM' && hour === 12)
                            hour = 0;
                    }
                } else {
                    hour = parseInt(this.container.find('.right .hourselect').val(), 10);
                    minute = parseInt(this.container.find('.right .minuteselect').val(), 10);
                    second = this.timePickerSeconds ? parseInt(this.container.find('.right .secondselect').val(), 10) : 0;
                    if (!this.timePicker24Hour) {
                        var ampm = this.container.find('.left .ampmselect').val();
                        if (ampm === 'PM' && hour < 12)
                            hour += 12;
                        if (ampm === 'AM' && hour === 12)
                            hour = 0;
                    }
                }
                this.leftCalendar.month.hour(hour).minute(minute).second(second);
                this.rightCalendar.month.hour(hour).minute(minute).second(second);
            }

            this.renderCalendar('left');
            this.renderCalendar('right');

            //highlight any predefined range matching the current start and end dates
            this.container.find('.ranges li').removeClass('active');
            if (this.endDate == null) return;

            var customRange = true;
            var i = 0;
            for (var range in this.ranges) {
                if (this.timePicker) {
                    if (this.startDate.isSame(this.ranges[range][0]) && this.endDate.isSame(this.ranges[range][1])) {
                        customRange = false;
                        this.chosenLabel = this.container.find('.ranges li:eq(' + i + ')').addClass('active').html();
                        break;
                    }
                } else {
                    //ignore times when comparing dates if time picker is not enabled
                    if (this.startDate.format('YYYY-MM-DD') == this.ranges[range][0].format('YYYY-MM-DD') && this.endDate.format('YYYY-MM-DD') == this.ranges[range][1].format('YYYY-MM-DD')) {
                        customRange = false;
                        this.chosenLabel = this.container.find('.ranges li:eq(' + i + ')').addClass('active').html();
                        break;
                    }
                }
                i++;
            }
            if (customRange) {
                this.chosenLabel = this.container.find('.ranges li:last').addClass('active').html();
                this.showCalendars();
            }

        },

        renderCalendar: function (side) {

            //
            // Build the matrix of dates that will populate the calendar
            //

            var calendar = side == 'left' ? this.leftCalendar : this.rightCalendar;
            var month = calendar.month.month();
            var year = calendar.month.year();
            var hour = calendar.month.hour();
            var minute = calendar.month.minute();
            var second = calendar.month.second();
            var daysInMonth = moment([year, month]).daysInMonth();
            var firstDay = moment([year, month, 1]);
            var lastDay = moment([year, month, daysInMonth]);
            var lastMonth = moment(firstDay).subtract(1, 'month').month();
            var lastYear = moment(firstDay).subtract(1, 'month').year();
            var daysInLastMonth = moment([lastYear, lastMonth]).daysInMonth();
            var dayOfWeek = firstDay.day();

            //initialize a 6 rows x 7 columns array for the calendar
            var calendar = [];
            calendar.firstDay = firstDay;
            calendar.lastDay = lastDay;

            for (var i = 0; i < 6; i++) {
                calendar[i] = [];
            }

            //populate the calendar with date objects
            var startDay = daysInLastMonth - dayOfWeek + this.locale.firstDay + 1;
            if (startDay > daysInLastMonth)
                startDay -= 7;

            if (dayOfWeek == this.locale.firstDay)
                startDay = daysInLastMonth - 6;

            // Possible patch for issue #626 https://github.com/dangrossman/bootstrap-daterangepicker/issues/626
            var curDate = moment([lastYear, lastMonth, startDay, 12, minute, second]).utcOffset(this.timeZone); // .utcOffset(this.timeZone);

            var col, row;

            for (var i = 0, col = 0, row = 0; i < 42; i++, col++, curDate = moment(curDate).add(24, 'hour')) {
                if (i > 0 && col % 7 === 0) {
                    col = 0;
                    row++;
                }
                calendar[row][col] = curDate.clone().hour(hour).minute(minute).second(second);
                curDate.hour(12);

                if (this.minDate && calendar[row][col].format('YYYY-MM-DD') == this.minDate.format('YYYY-MM-DD') && calendar[row][col].isBefore(this.minDate) && side == 'left') {
                    calendar[row][col] = this.minDate.clone();
                }

                if (this.maxDate && calendar[row][col].format('YYYY-MM-DD') == this.maxDate.format('YYYY-MM-DD') && calendar[row][col].isAfter(this.maxDate) && side == 'right') {
                    calendar[row][col] = this.maxDate.clone();
                }

            }

            //make the calendar object available to hoverDate/clickDate
            if (side == 'left') {
                this.leftCalendar.calendar = calendar;
            } else {
                this.rightCalendar.calendar = calendar;
            }

            //
            // Display the calendar
            //

            var minDate = side == 'left' ? this.minDate : this.startDate;
            var maxDate = this.maxDate;
            var selected = side == 'left' ? this.startDate : this.endDate;

            var html = '<table class="table-condensed">';
            html += '<thead>';
            html += '<tr>';

            // add empty cell for week number
            if (this.showWeekNumbers)
                html += '<th></th>';

            if ((!minDate || minDate.isBefore(calendar.firstDay)) && (!this.linkedCalendars || side == 'left')) {
                html += '<th class="prev available"><i class="fa fa-chevron-left glyphicon glyphicon-chevron-left"></i></th>';
            } else {
                html += '<th></th>';
            }

            var dateHtml = this.locale.monthNames[calendar[1][1].month()] + calendar[1][1].format(" YYYY");

            if (this.showDropdowns) {
                var currentMonth = calendar[1][1].month();
                var currentYear = calendar[1][1].year();
                var maxYear = (maxDate && maxDate.year()) || (currentYear + 5);
                var minYear = (minDate && minDate.year()) || (currentYear - 50);
                var inMinYear = currentYear == minYear;
                var inMaxYear = currentYear == maxYear;

                var monthHtml = '<select class="monthselect">';
                for (var m = 0; m < 12; m++) {
                    if ((!inMinYear || m >= minDate.month()) && (!inMaxYear || m <= maxDate.month())) {
                        monthHtml += "<option value='" + m + "'" +
                            (m === currentMonth ? " selected='selected'" : "") +
                            ">" + this.locale.monthNames[m] + "</option>";
                    } else {
                        monthHtml += "<option value='" + m + "'" +
                            (m === currentMonth ? " selected='selected'" : "") +
                            " disabled='disabled'>" + this.locale.monthNames[m] + "</option>";
                    }
                }
                monthHtml += "</select>";

                var yearHtml = '<select class="yearselect">';
                for (var y = minYear; y <= maxYear; y++) {
                    yearHtml += '<option value="' + y + '"' +
                        (y === currentYear ? ' selected="selected"' : '') +
                        '>' + y + '</option>';
                }
                yearHtml += '</select>';

                dateHtml = monthHtml + yearHtml;
            }

            html += '<th colspan="5" class="month">' + dateHtml + '</th>';
            if ((!maxDate || maxDate.isAfter(calendar.lastDay)) && (!this.linkedCalendars || side == 'right' || this.singleDatePicker)) {
                html += '<th class="next available"><i class="fa fa-chevron-right glyphicon glyphicon-chevron-right"></i></th>';
            } else {
                html += '<th></th>';
            }

            html += '</tr>';
            html += '<tr>';

            // add week number label
            if (this.showWeekNumbers)
                html += '<th class="week">' + this.locale.weekLabel + '</th>';

            $.each(this.locale.daysOfWeek, function (index, dayOfWeek) {
                html += '<th>' + dayOfWeek + '</th>';
            });

            html += '</tr>';
            html += '</thead>';
            html += '<tbody>';

            //adjust maxDate to reflect the dateLimit setting in order to
            //grey out end dates beyond the dateLimit
            if (this.endDate == null && this.dateLimit) {
                var maxLimit = this.startDate.clone().add(this.dateLimit).endOf('day');
                if (!maxDate || maxLimit.isBefore(maxDate)) {
                    maxDate = maxLimit;
                }
            }

            for (var row = 0; row < 6; row++) {
                html += '<tr>';

                // add week number
                if (this.showWeekNumbers)
                    html += '<td class="week">' + calendar[row][0].week() + '</td>';

                for (var col = 0; col < 7; col++) {

                    var classes = [];

                    //highlight today's date
                    if (calendar[row][col].isSame(new Date(), "day"))
                        classes.push('today');

                    //highlight weekends
                    if (calendar[row][col].isoWeekday() > 5)
                        classes.push('weekend');

                    //grey out the dates in other months displayed at beginning and end of this calendar
                    if (calendar[row][col].month() != calendar[1][1].month())
                        classes.push('not');

                    //don't allow selection of dates before the minimum date
                    if (this.minDate && calendar[row][col].isBefore(this.minDate, 'day'))
                        classes.push('off', 'disabled');

                    //don't allow selection of dates after the maximum date
                    if (maxDate && calendar[row][col].isAfter(maxDate, 'day'))
                        classes.push('off', 'disabled');

                    //don't allow selection of date if a custom function decides it's invalid


                    var resRes = this.findReserved(calendar[row][col]);
                    if (resRes.class) {
                        classes.push(resRes.class);
                        if (resRes.class == 'reserved-full' && !this.adminMode) {
                            classes.push('disabled');
                        }
                    }

                    //highlight the currently selected start date
                    if (this.realStartDate != '' && calendar[row][col].format('YYYY-MM-DD') == this.realStartDate.format('YYYY-MM-DD'))
                        classes.push('active', 'start-date');

                    //highlight the currently selected end date

                    if (this.realEndDate != '' && calendar[row][col].format('YYYY-MM-DD') == this.realEndDate.format('YYYY-MM-DD'))
                        classes.push('active', 'end-date');

                    //highlight dates in-between the selected dates
                    if (this.realEndDate != '' && this.realStartDate != '' && calendar[row][col] > this.realStartDate && calendar[row][col] < this.realEndDate)
                        classes.push('in-range');

                    var cname = '', disabled = false;
                    for (var i = 0; i < classes.length; i++) {
                        cname += classes[i] + ' ';
                        if (classes[i] == 'disabled')
                            disabled = true;
                    }
                    if (!disabled)
                        cname += 'available';

                    html += '<td class="' + cname.replace(/^\s+|\s+$/g, '') + '" data-title="' + 'r' + row + 'c' + col + '">' + calendar[row][col].date() + '</td>';

                }
                html += '</tr>';
            }

            html += '</tbody>';
            html += '</table>';

            this.container.find('.calendar.' + side + ' .calendar-table').html(html);

        },

        renderTimePicker: function(side) {

            var html, selected, minDate, maxDate = this.maxDate;

            if(side == 'left') {
                selected = this.realStartDate;

                if (selected){
                    $.each(this.leftHalfReservedDates, function (ind, val) {
                        if (selected.isSame(moment(val.rent_to), 'day')) {
                            minDate = moment(val.rent_to);
                            if (selected < minDate) { //применяем только если дата которая была выбрана до этого больше
                                //selected = minDate;
                            }
                        }
                    });
                }
            } else {
                selected = this.realEndDate;

                if(selected)
                    $.each(this.rightHalfReservedDates, function(ind, val){
                        if(selected.isSame(moment(val.rent_from),'day')){
                            maxDate = moment(val.rent_from);
                        }
                    });
            }

            if(!selected)
                selected = moment().startOf('day');

            html = '<select id="'+side+'_timepicker" class="hourselect">';
            html += '<option value="" selected="selected" class="empty">--</option>';

            var start = this.timePicker24Hour ? 0 : 1;
            if(this.timeStartHours)
                start = this.timeStartHours;

            var end = this.timePicker24Hour ? 23 : 12;
            if(this.timeEndHours)
                end = this.timeEndHours;

            for (var i = start; i <= end; i++) {
                var i_in_24 = i;
                if (!this.timePicker24Hour)
                    i_in_24 = selected.hour() >= 12 ? (i == 12 ? 12 : i + 12) : (i == 12 ? 0 : i);

                var time = selected.clone().hour(i_in_24);

                var disabled = false;

                if (minDate && time.minute(59).isBefore(minDate) )
                    disabled = true;
                if (maxDate && time.minute(0).isAfter(maxDate))
                    disabled = true;

                if (i_in_24 == selected.hour() && !disabled) {
                    html += '<option value="' + i + '" selected="selected">' + i + '</option>';
                } else if (disabled) {
                    html += '<option value="' + i + '" disabled="disabled" class="disabled">' + i + '</option>';
                } else {
                    html += '<option value="' + i + '">' + i + '</option>';
                }
            }

            html += '</select> ';
            html += ': <select class="minuteselect">';

            if(minDate > selected){
                selected = minDate;
            }

            for (var i = 0; i < 60; i += this.timePickerIncrement) {
                var padded = i < 10 ? '0' + i : i;
                var time = selected.clone().minute(i);

                var disabled = false;
                if (minDate && time.second(59).isBefore(minDate))
                    disabled = true;
                if (maxDate && time.second(0).isAfter(maxDate))
                    disabled = true;

                if (selected.minute() == i && !disabled) {
                    html += '<option value="' + i + '" selected="selected">' + padded + '</option>';
                } else if (disabled) {
                    html += '<option value="' + i + '" disabled="disabled" class="disabled">' + padded + '</option>';
                } else {
                    html += '<option value="' + i + '">' + padded + '</option>';
                }
            }

            html += '</select> ';

            this.container.find('.calendar.' + side + ' .calendar-time div').html(html);
        },

        updateFormInputs: function() {

            //ignore mouse movements while an above-calendar text input has focus
            if (this.container.find('input[name=daterangepicker_start]').is(":focus") || this.container.find('input[name=daterangepicker_end]').is(":focus"))
                return;

            this.container.find('input[name=daterangepicker_start]').val(this.startDate.format(this.locale.format));
            if (this.endDate)
                this.container.find('input[name=daterangepicker_end]').val(this.endDate.format(this.locale.format));

            if (this.singleDatePicker || (this.endDate && (this.startDate.isBefore(this.endDate) || this.startDate.isSame(this.endDate)))) {
                this.container.find('button.applyBtn').removeAttr('disabled');
            } else {
                this.container.find('button.applyBtn').attr('disabled', 'disabled');
            }

        },

        move: function() {
            var parentOffset = { top: 0, left: 0 },
                containerTop;
            var parentRightEdge = $(window).width();
            if (!this.parentEl.is('body')) {
                parentOffset = {
                    top: this.parentEl.offset().top - this.parentEl.scrollTop(),
                    left: this.parentEl.offset().left - this.parentEl.scrollLeft()
                };
                parentRightEdge = this.parentEl[0].clientWidth + this.parentEl.offset().left;
            }

            if (this.drops == 'up')
                containerTop = this.element.offset().top - this.container.outerHeight() - parentOffset.top;
            else
                containerTop = this.element.offset().top + this.element.outerHeight() - parentOffset.top;
            this.container[this.drops == 'up' ? 'addClass' : 'removeClass']('dropup');

            if (this.opens == 'left') {
                this.container.css({
                    top: containerTop,
                    right: parentRightEdge - this.element.offset().left - this.element.outerWidth(),
                    left: 'auto'
                });
                if (this.container.offset().left < 0) {
                    this.container.css({
                        right: 'auto',
                        left: 9
                    });
                }
            } else if (this.opens == 'center') {
                this.container.css({
                    top: containerTop,
                    left: this.element.offset().left - parentOffset.left + this.element.outerWidth() / 2
                    - this.container.outerWidth() / 2,
                    right: 'auto'
                });
                if (this.container.offset().left < 0) {
                    this.container.css({
                        right: 'auto',
                        left: 9
                    });
                }
            } else {
                this.container.css({
                    top: containerTop,
                    left: this.element.offset().left - parentOffset.left,
                    right: 'auto'
                });
                if (this.container.offset().left + this.container.outerWidth() > $(window).width()) {
                    this.container.css({
                        left: 'auto',
                        right: 0
                    });
                }
            }
        },

        show: function(e) {
            if (this.isShowing) return;

            // Create a click proxy that is private to this instance of datepicker, for unbinding
            this._outsideClickProxy = $.proxy(function(e) { this.outsideClick(e); }, this);
            // Bind global datepicker mousedown for hiding and
            $(document)
                .on('mousedown.daterangepicker', this._outsideClickProxy)
                // also support mobile devices
                .on('touchend.daterangepicker', this._outsideClickProxy)
                // also explicitly play nice with Bootstrap dropdowns, which stopPropagation when clicking them
                .on('click.daterangepicker', '[data-toggle=dropdown]', this._outsideClickProxy)
                // and also close when focus changes to outside the picker (eg. tabbing between controls)
                .on('focusin.daterangepicker', this._outsideClickProxy);

            this.oldStartDate = this.startDate.clone();
            this.oldEndDate = this.endDate.clone();

            this.updateView();
            this.container.show();
            this.move();
            this.element.trigger('show.daterangepicker', this);
            this.isShowing = true;
        },

        hide: function(e) {
            if (!this.isShowing) return;

            //incomplete date selection, revert to last values
            if (!this.endDate) {
                this.startDate = this.oldStartDate.clone();
                this.endDate = this.oldEndDate.clone();
            }

            //if a new date range was selected, invoke the user callback function
            if (!this.startDate.isSame(this.oldStartDate) || !this.endDate.isSame(this.oldEndDate))
                this.callback(this.startDate, this.endDate, this.chosenLabel);

            //if picker is attached to a text input, update it
            this.updateElement();

            $(document).off('.daterangepicker');
            this.container.hide();
            this.element.trigger('hide.daterangepicker', this);
            this.isShowing = false;
        },

        toggle: function(e) {
            if (this.isShowing) {
                this.hide();
            } else {
                this.show();
            }
        },

        outsideClick: function(e) {
            /*
            if (this.isShowing) return;
             */

            if(this.positionFixed == true)
                return true;

            var target = $(e.target);
            // if the page is clicked anywhere except within the daterangerpicker/button
            // itself then call this.hide()
            if (
                // ie modal dialog fix
            e.type == "focusin" ||
            target.closest(this.element).length ||
            target.closest(this.container).length ||
            target.closest('.calendar-table').length
            ) return;
            this.hide();
        },

        showCalendars: function() {
            this.container.addClass('show-calendar');
            this.move();
            this.element.trigger('showCalendar.daterangepicker', this);
        },

        hideCalendars: function() {
            this.container.removeClass('show-calendar');
            this.element.trigger('hideCalendar.daterangepicker', this);
        },

        hoverRange: function(e) {

            //ignore mouse movements while an above-calendar text input has focus
            if (this.container.find('input[name=daterangepicker_start]').is(":focus") || this.container.find('input[name=daterangepicker_end]').is(":focus"))
                return;

            var label = e.target.innerHTML;
            if (label == this.locale.customRangeLabel) {
                this.updateView();
            } else {
                var dates = this.ranges[label];
                this.container.find('input[name=daterangepicker_start]').val(dates[0].format(this.locale.format));
                this.container.find('input[name=daterangepicker_end]').val(dates[1].format(this.locale.format));
            }

        },

        clickRange: function(e) {
            var label = e.target.innerHTML;
            this.chosenLabel = label;
            if (label == this.locale.customRangeLabel) {
                this.showCalendars();
            } else {
                var dates = this.ranges[label];
                this.startDate = dates[0];
                this.endDate = dates[1];

                if (!this.timePicker) {
                    this.startDate.startOf('day');
                    this.endDate.endOf('day');
                }

                this.hideCalendars();
                this.clickApply();
            }
        },

        clickPrev: function(e) {
            var cal = $(e.target).parents('.calendar');
            if (cal.hasClass('left')) {
                this.leftCalendar.month.subtract(1, 'month');
                if (this.linkedCalendars)
                    this.rightCalendar.month.subtract(1, 'month');
            } else {
                this.rightCalendar.month.subtract(1, 'month');
            }
            this.updateCalendars();
        },

        clickNext: function(e) {
            var cal = $(e.target).parents('.calendar');
            if (cal.hasClass('left')) {
                this.leftCalendar.month.add(1, 'month');
            } else {
                this.rightCalendar.month.add(1, 'month');
                if (this.linkedCalendars)
                    this.leftCalendar.month.add(1, 'month');
            }
            this.updateCalendars();
        },

        hoverTimer: function(e){

            if(this.realStartDate && !this.realEndDate){
                this.container.find('.calendar-table td.in-range').removeClass('in-range');
            }

        },
        hoverDate: function(e) {

            //ignore mouse movements while an above-calendar text input has focus
            if (this.container.find('input[name=daterangepicker_start]').is(":focus") || this.container.find('input[name=daterangepicker_end]').is(":focus"))
                return;

            //ignore dates that can't be selected
            if (!$(e.target).hasClass('available')) return;

            //have the text inputs above calendars reflect the date being hovered over
            var title = $(e.target).attr('data-title');
            var row = title.substr(1, 1);
            var col = title.substr(3, 1);
            var cal = $(e.target).parents('.calendar');
            var date = cal.hasClass('left') ? this.leftCalendar.calendar[row][col] : this.rightCalendar.calendar[row][col];

            if (this.endDate) {
                this.container.find('input[name=daterangepicker_start]').val(date.format(this.locale.format));
            } else {
                this.container.find('input[name=daterangepicker_end]').val(date.format(this.locale.format));
            }

            //highlight the dates between the start date and the date being hovered as a potential end date
            var leftCalendar = this.leftCalendar;
            var rightCalendar = this.rightCalendar;
            var startDate = this.realStartDate;
            if (this.realStartDate && !this.realEndDate) {

                this.container.find('.calendar td').each(function(index, el) {
                    //skip week numbers, only look at dates
                    if ($(el).hasClass('week')) return;

                    var title = $(el).attr('data-title');
                    var row = title.substr(1, 1);
                    var col = title.substr(3, 1);
                    var cal = $(el).parents('.calendar');
                    var dt = cal.hasClass('left') ? leftCalendar.calendar[row][col] : rightCalendar.calendar[row][col];

                    if (dt.isAfter(startDate)) {
                        if (dt.isBefore(date)) {
                            $(el).addClass('in-range');
                        } else {
                            $(el).removeClass('in-range');
                        }
                    } else if(dt.isBefore(startDate)) {
                        if (dt.isBefore(date) || dt.isSame(date, 'day')) {
                            $(el).removeClass('in-range');
                        } else {
                            $(el).addClass('in-range');
                        }
                    } else {
                        //$(el).addClass('in-range');
                    }
                });

            }

        },

        clickDate: function(e) {

            if (!$(e.target).hasClass('available')) return;

            var title = $(e.target).attr('data-title');
            var row = title.substr(1, 1);
            var col = title.substr(3, 1);
            var cal = $(e.target).parents('.calendar');
            var date = cal.hasClass('left') ? this.leftCalendar.calendar[row][col] : this.rightCalendar.calendar[row][col];

            if(this.realStartDate && this.realEndDate){
                this.realStartDate = date.clone();
                this.realEndDate = '';

            } else if(this.realStartDate && !this.realStartDate.isSame(date,'day')){

                this.realEndDate = date.clone();
                if(this.realEndDate < this.realStartDate){
                    var tempDate = this.realEndDate;
                    this.realEndDate = this.realStartDate;
                    this.realStartDate = tempDate;
                }

                this.updateView();

            } else {//если совсем ничего не было выбрано
                this.realStartDate = date.clone();
            }


            if(this.checkSelect()) { // if reset
                this.realStartDate = date.clone();
                this.realEndDate = '';
            }

            if(this.adminMode){
                this.lastClickDate = date.clone();
                this.renderReservedInfo();
                if($(e.target).hasClass('reserved-full')){
                    this.container.find('.startTimer').empty();
                    this.container.find('.endTimer').empty();
                }
            }

            if(this.PaySumm > 0 || this.PayType == 'calc')
                this.renderPayInfo();

            this.container.find('.endTimer').empty();

            if(!$(e.target).hasClass('reserved-full'))
                this.renderTimesList('startTimer');

            this.updateView(); //перерендеривает календарь, не лучшее решение

        },

        renderPayInfo: function() {

            if(this.PayType == 'calc'){
                var payText = 'К оплате:';
                var cost;
                var daysDiff = Math.abs(moment(this.realStartDate).startOf('day').diff(moment(this.realEndDate).startOf('day'), 'days'));
                if(!daysDiff)
                    daysDiff = 1;

                this.cost1 = parseInt(this.cost1);
                this.cost2 = parseInt(this.cost2);
                this.cost8 = parseInt(this.cost8);

                if (daysDiff == 1) {
                    cost = this.cost1;
                } else if (daysDiff >= 2 && daysDiff < 8) {

                    if(this.cost2) {
                        cost = this.cost2;
                    } else {
                        cost = this.cost1;
                    }

                } else if (daysDiff >= 8) {
                    if(this.cost8) {
                        cost = this.cost8;
                    } else {
                        if(this.cost2) {
                            cost = this.cost2;
                        } else {
                            cost = this.cost1;
                        }
                    }
                }

                var resSumm = (cost * (daysDiff));

            } else {
                var payText = 'Аванс:';
                var resSumm = this.PaySumm;
            }

            if(this.IsClientPayCom) {
                resSumm = (parseInt(resSumm) / parseFloat(this.Percent));
            } else {
                resSumm = parseInt(resSumm);
            }

            resSumm = Number((resSumm).toFixed(2));

            var html = '<div id="box-itogo">'+payText+' <span id="all-amount-my-brone">'+resSumm+'</span> руб.</div>'+
            '<div class="method-pay"><b>Принимаем к оплате:</b>'+
                '<span class="all-it-mpay"><img src="/images/i-credit.png"><span class="title-mpay">Банковские карты</span></span>'+
                '<span class="all-it-mpay"><img src="/images/i-yandex.png"><span class="title-mpay">Яндекс Деньги</span></span>'+
            '</div>';

            this.resPaySumm = resSumm;
            this.container.find('#payInfo').html(html);
        },

        renderReservedInfo: function() {

            if(this.lastClickDate == '')
                return true;

            var html = '';

            var resReserved = this.findReserved(this.lastClickDate);
            if(resReserved.class){

                html = '<div id="result-title-day">'+this.lastClickDate.format("DD ") + this.locale.monthNamesRod[this.lastClickDate.month()] + this.lastClickDate.format(" YYYY")+'</div>';

                for (var i = 0; i < resReserved.rentedPeriods.length; i++) {
                    var rent = resReserved.rentedPeriods[i];
                    var name = rent.name, phone = rent.phone, comment = rent.comment;

                    if(!name) {
                       name = '';
                    }

                    if(!phone)
                        var phone = '';

                    if(!comment)
                        var comment = '';

                    html = html + '<div class="rentPeriod"><div>Дата брони:<br/>с '+moment(rent.rent_from).format('H:mm')+' '+moment(rent.rent_from).format('DD.MM.YYYY')+'<br/>по '+moment(rent.rent_to).format('H:mm')+' '+moment(rent.rent_to).format('DD.MM.YYYY')+'</div>';
                    html = html + '<div>Имя: '+name+'</div>';
                    html = html + '<div>Телефон: '+phone+'</div>';
                    html = html + '<div>Комменатрий: '+comment+'</div>';
                    html = html + '<div class="cancel"><a href="'+this.cancelUrl+rent.id+'">отменить бронь</a></div></div>';
                }
            }
            this.container.find('#reservedInfo').html(html);
        },

        clickApply: function(e) {
            this.hide();
            this.element.trigger('apply.daterangepicker', this);
        },

        clickCancel: function(e) {
            this.startDate = this.oldStartDate;
            this.endDate = this.oldEndDate;
            this.hide();
            this.element.trigger('cancel.daterangepicker', this);
        },

        monthOrYearChanged: function(e) {
            var isLeft = $(e.target).closest('.calendar').hasClass('left'),
                leftOrRight = isLeft ? 'left' : 'right',
                cal = this.container.find('.calendar.'+leftOrRight);

            // Month must be Number for new moment versions
            var month = parseInt(cal.find('.monthselect').val(), 10);
            var year = cal.find('.yearselect').val();

            if (!isLeft) {
                if (year < this.startDate.year() || (year == this.startDate.year() && month < this.startDate.month())) {
                    month = this.startDate.month();
                    year = this.startDate.year();
                }
            }

            if (this.minDate) {
                if (year < this.minDate.year() || (year == this.minDate.year() && month < this.minDate.month())) {
                    month = this.minDate.month();
                    year = this.minDate.year();
                }
            }

            if (this.maxDate) {
                if (year > this.maxDate.year() || (year == this.maxDate.year() && month > this.maxDate.month())) {
                    month = this.maxDate.month();
                    year = this.maxDate.year();
                }
            }

            if (isLeft) {
                this.leftCalendar.month.month(month).year(year);
                if (this.linkedCalendars)
                    this.rightCalendar.month = this.leftCalendar.month.clone().add(1, 'month');
            } else {
                this.rightCalendar.month.month(month).year(year);
                if (this.linkedCalendars)
                    this.leftCalendar.month = this.rightCalendar.month.clone().subtract(1, 'month');
            }
            this.updateCalendars();
        },

        changeTimeLabel: function (e) {

            var startTimeLabel = this.container.find('.startTimeLabel')[0];
            var endTimeLabel = this.container.find('.endTimeLabel')[0];

            this.setTimeResults();

            if(this.realStartDate && this.realEndDate){
                $(startTimeLabel).html('время:'+moment(this.realStartDateTime).format('HH:mm'));
                if(this.realEndDateTime)
                    $(endTimeLabel).html('время:'+moment(this.realEndDateTime).format('HH:mm'));
            } else {
                if(this.realEndDateTime && !moment(this.realStartDateTime).isSame(moment(this.realEndDateTime))){
                    $(startTimeLabel).html('время начала:' + moment(this.realStartDateTime).format('HH:mm') + ', время окончания:' + moment(this.realEndDateTime).format('HH:mm'));
                } else {
                    $(startTimeLabel).html('время начала:' + moment(this.realStartDateTime).format('HH:mm') + ', выберите время окончания:');
                }
            }
        },

        clickTimeRange: function(e) {

            var el = $(e.target);
            if(el.hasClass('disabled'))
                return false;

            var side = el.closest('.timerangepicker').data('timertype');

            var startTimer = this.container.find('.startTimer ul').children();
            var endTimer = this.container.find('.endTimer ul').children();

            var allTimes = this.container.find('.list-timer li');

            var isStartTimerSelect = false;
            var isEndTimerSelect = false;

            if(startTimer.hasClass('timer-in-range'))
                var isStartTimerSelect = true;

            if(endTimer.hasClass('timer-in-range'))
                var isEndTimerSelect = true;

            if(this.realStartDate && this.realEndDate){

                if(side == 'startTimer'){

                    startTimer.removeClass('timer-in-range');
                    el.addClass('timer-in-range');

                    if(!isEndTimerSelect) {
                        this.renderTimesList('endTimer');
                    } else {
                        var startSelected = this.container.find('.startTimer ul li.timer-in-range')[0];

                        $.each(startTimer, function(i, val) {
                            if($(startSelected).data('time') < $(val).data('time')){
                                $(val).addClass('timer-in-range');
                            }
                        });
                    }

                } else {

                    var startSelected = this.container.find('.startTimer ul li.timer-in-range')[0];
                    endTimer.removeClass('timer-in-range');

                    $.each(allTimes, function(i, val) {
                        if($(startSelected).data('time') < $(val).data('time') && $(val).data('time') < el.data('time')){
                            $(val).addClass('timer-in-range');
                        }
                    });
                    el.addClass('timer-in-range');
                }

            } else {//если выбрана только начальная дата

                if(isStartTimerSelect){

                    var startSelected = this.container.find('.startTimer ul li.timer-in-range');

                    if(startSelected.length > 1){
                        startTimer.removeClass('timer-in-range');
                        el.addClass('timer-in-range');
                    } else {

                        var startSelected = this.container.find('.startTimer ul li.timer-in-range');

                        startSelected = $(startSelected[0]).data('time');

                        var curSelected = el.data('time');

                        if(startSelected > curSelected){
                            var tmp = startSelected;
                            startSelected = curSelected;
                            curSelected = tmp;
                        }

                        el.addClass('timer-in-range');

                        $.each(startTimer, function(i, val) {
                            if(startSelected < $(val).data('time') && curSelected > $(val).data('time')){
                                if($(val).hasClass('disabled')){
                                    startTimer.removeClass('timer-in-range');
                                    return true;
                                } else {
                                    $(val).addClass('timer-in-range');
                                }
                            }
                        });

                        el.addClass('timer-in-range');

                    }
                    /*
                    $.each(startTimer, function(i, val) {
                        if($(startSelected).data('time') < $(val).data('time')){
                            $(val).addClass('timer-in-range');
                        }
                    });
                    */
                } else {
                    el.addClass('timer-in-range');
                }
            }

            this.changeTimeLabel();

        },

        timeChanged: function(e) {

            var cal = $(e.target).closest('.calendar'),
                isLeft = cal.hasClass('left');

            var hour = parseInt(cal.find('.hourselect').val(), 10);
            var minute = parseInt(cal.find('.minuteselect').val(), 10);

            var second = this.timePickerSeconds ? parseInt(cal.find('.secondselect').val(), 10) : 0;

            if (!this.timePicker24Hour) {
                var ampm = cal.find('.ampmselect').val();
                if (ampm === 'PM' && hour < 12)
                    hour += 12;
                if (ampm === 'AM' && hour === 12)
                    hour = 0;
            }

            if (isLeft) {

                var start = this.realStartDate.clone();

                start.hour(hour);
                start.minute(minute);
                start.second(second);

                if(!start.isValid()){
                    return;
                }

                //this.setStartDate(start);
                this.realStartDate = start;

                if (this.singleDatePicker)
                    this.endDate = this.startDate.clone();

            } else {

                if(!this.realEndDate){//this day selected
                    var end = this.realStartDate.clone();
                } else {
                    var end = this.realEndDate.clone();
                }

                end.hour(hour);
                end.minute(minute);
                end.second(second);

                if(!end.isValid()){
                    return;
                }

                this.realEndDate = end;
                this.setEndDate(end);
            }

            if(this.realEndDate && this.realStartDate > this.realEndDate){
                var temp = this.realEndDate;
                this.realEndDate = this.realStartDate;
                this.realStartDate = temp;
            }

            //update the calendars so all clickable dates reflect the new time component
//            this.updateCalendars();

            //update the form inputs above the calendars with the new time
//            this.updateFormInputs();

            this.renderTimePicker('left');
            this.renderTimePicker('right');

        },

        formInputsChanged: function(e) {

            var isRight = $(e.target).closest('.calendar').hasClass('right');
            var start = moment(this.container.find('input[name="daterangepicker_start"]').val(), this.locale.format).utcOffset(this.timeZone);
            var end = moment(this.container.find('input[name="daterangepicker_end"]').val(), this.locale.format).utcOffset(this.timeZone);

            if (start.isValid() && end.isValid()) {

                if (isRight && end.isBefore(start))
                    start = end.clone();

                this.setStartDate(start);
                this.setEndDate(end);

                if (isRight) {
                    this.container.find('input[name="daterangepicker_start"]').val(this.startDate.format(this.locale.format));
                } else {
                    this.container.find('input[name="daterangepicker_end"]').val(this.endDate.format(this.locale.format));
                }

            }

            this.updateCalendars();
            if (this.timePicker) {
                this.renderTimePicker('left');
                this.renderTimePicker('right');
            }
        },

        elementChanged: function() {

            if (!this.element.is('input')) return;
            if (!this.element.val().length) return;

            var dateString = this.element.val().split(this.locale.separator),
                start = null,
                end = null;

            if (dateString.length === 2) {
                start = moment(dateString[0], this.locale.format).utcOffset(this.timeZone);
                end = moment(dateString[1], this.locale.format).utcOffset(this.timeZone);
            }

            if (this.singleDatePicker || start === null || end === null) {
                start = moment(this.element.val(), this.locale.format).utcOffset(this.timeZone);
                end = start;
            }

            this.setStartDate(start);
            this.setEndDate(end);
            this.updateView();
        },

        keydown: function(e) {
            //hide on tab or enter
            if ((e.keyCode === 9) || (e.keyCode === 13)) {
                this.hide();
            }
        },

        updateElement: function() {
            if (this.element.is('input') && !this.singleDatePicker && this.autoUpdateInput) {
                this.element.val(this.startDate.format(this.locale.format) + this.locale.separator + this.endDate.format(this.locale.format));
                this.element.trigger('change');
            } else if (this.element.is('input') && this.autoUpdateInput) {
                this.element.val(this.startDate.format(this.locale.format));
                this.element.trigger('change');
            }

        },

        setTimeResults: function() {

            this.realStartDateTime = this.container.find('.startTimer li.timer-in-range').first().data('time');
            this.realEndDateTime = this.container.find('.endTimer li.timer-in-range').last().data('time');

            if (this.realStartDate && this.realEndDate) {
                this.realStartDateTime = this.container.find('.startTimer li.timer-in-range').first().data('time');
                this.realEndDateTime = this.container.find('.endTimer li.timer-in-range').last().data('time');
            } else if(this.realStartDate){
                this.realStartDateTime = this.container.find('.startTimer li.timer-in-range').first().data('time');
                this.realEndDateTime = this.container.find('.startTimer li.timer-in-range').last().data('time');
            }

        },

        checkApply: function() {

            var apply = {rentFrom:'', rentTo:'', errorText: ''};
            var errorText = '';

            this.setTimeResults();

            if(this.realStartDate && this.realEndDate){
                if(!this.realStartDateTime ){
                    errorText = 'Выберите время начала аренды';
                } else if(!this.realEndDateTime){
                    errorText = 'Выберите время окончания аренды';
                }
            } else if(this.realStartDate){
                if(!this.realStartDateTime){
                    errorText = 'Время бронирования не выбрано';
                } else if(this.realStartDateTime == this.realEndDateTime){
                    errorText = 'Время окончания бронирования не выбрано';
                }
            } else {
                errorText = 'Период бронирования не выбран';
            }

            if(errorText){
                apply.errorText = errorText;
            } else {
                apply.rentFrom = moment(this.realStartDateTime).format(this.locale.format);
                apply.rentTo = moment(this.realEndDateTime).format(this.locale.format);
                apply.resPaySumm = this.resPaySumm;
            }

            return apply;

        },

        remove: function() {
            this.container.remove();
            this.element.off('.daterangepicker');
            this.element.removeData();
        }

    };

    $.fn.daterangepicker = function(options, callback) {
        this.each(function() {
            var el = $(this);
            if (el.data('daterangepicker'))
                el.data('daterangepicker').remove();
            el.data('daterangepicker', new DateRangePicker(el, options, callback));
        });
        return this;
    };

}))