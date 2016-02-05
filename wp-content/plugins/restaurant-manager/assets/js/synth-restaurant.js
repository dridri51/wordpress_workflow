"use strict";

jQuery(document).ready(function()
{
    var selected_day = null;
    var scheduler = JSON.parse(syn_restaurant_manager.scheduler);

    var todays_date = new Date();

    jQuery('.syn-datepicker').datepicker({
        minDate: todays_date,
        onSelect: function(date) {
            var startDate = new Date(date);
            var day = startDate.getDay();

            switch (day) {
                case 0:
                    selected_day = 'sunday';
                    break;
                case 1:
                    selected_day = 'monday';
                    break;
                case 2:
                    selected_day = 'tuesday';
                    break;
                case 3:
                    selected_day = 'wednesday';
                    break;
                case 4:
                    selected_day = 'thursday';
                    break;
                case 5:
                    selected_day = 'friday';
                    break;
                case 6:
                    selected_day = 'saturday';
                    break;
                default:
                    selected_day = null;
                    break;
            }

            syn_restaurant_manager_set_timepicker(scheduler, selected_day);
        }
    });
});

function syn_restaurant_manager_set_timepicker(scheduler, selected_day) {

    var scheduled_times = new Array();
    var times = get_scheduler_enabled_times(scheduled_times);

    var timecontrol = jQuery('.syn-timepicker').pickatime({
        format: 'H:i',
        interval: 5
    });

    if (times.enabled.length > 0) {
        var enable_times = new Array();

        enable_times.push({
            from: [0, 0],
            to: [24, 0],
            inverted: false
        });

        for (var i = 0; i < times.enabled.length; i++) {
            enable_times.push({
                from: [times.enabled[i]['min_hour'], times.enabled[i]['min_minute']],
                to: [times.enabled[i]['max_hour'], times.enabled[i]['max_minute']],
                inverted: true
            });
        }

        var timepicker = timecontrol.pickatime('picker');
        timepicker.set('disable', false);
        timepicker.set('disable', enable_times);
    }

    function get_scheduler_enabled_times(scheduled_times) {

        var min_hour;
        var min_minute;
        var max_hour;
        var max_minute;

        scheduled_times['enabled'] = new Array();

        for (var i = 0; i < scheduler.length; i++) {

            var is_schedule_day = scheduler[i].weekday[selected_day];
            min_hour = null;
            min_minute = null;
            max_hour = null;
            max_minute = null;

            if (is_schedule_day) {

                var enabled_times = new Array();
                var start_time = scheduler[i].timeslot.starttime;
                var end_time = scheduler[i].timeslot.endtime;

                var res = start_time.split(':');
                var hour = res[0];
                var minute = res[1];

                if (!min_hour || !min_minute) {
                    min_hour = hour;
                    min_minute = minute;
                }
                if (min_hour > hour) {
                    min_hour = hour;
                }
                if (min_minute > minute) {
                    min_minute = minute;
                }

                enabled_times['min_hour'] = min_hour;
                enabled_times['min_minute'] = min_minute;

                var res = end_time.split(':');
                hour = res[0];
                minute = res[1];

                if (!max_hour || !max_minute) {
                    max_hour = hour;
                    max_minute = minute;
                }
                if (max_hour < hour) {
                    max_hour = hour;
                }
                if (max_minute < minute) {
                    max_minute = minute;
                }

                enabled_times['max_hour'] = max_hour;
                enabled_times['max_minute'] = max_minute;

                scheduled_times['enabled'].push(enabled_times);
            }
        }

        return scheduled_times;
    }

}