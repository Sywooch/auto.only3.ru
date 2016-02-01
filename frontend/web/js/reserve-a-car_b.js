$(document).ready(function($) {

    var Select = 0;

    $('.cell_nobrone').click(function () {

        Select = Select + 1;

        if(Select == 3){
            $('.cell_cal').removeClass('cell_active');
            Select = 0;
            return true;
        }

        $(this).toggleClass('cell_active');

        $('.cell_cal').removeClass('cell_active_br');
        $('#list-choise-place li').empty();
        $('#list-choise-date li').empty();

        if ($(this).hasClass('cell_active')) {
            var date_brone = $(this).children('input').val(); // получаем дату на которую нажали

            var all_res = $('.res_brone_date').val();
            all_res = all_res + date_brone + ',';
            $('.res_brone_date').val(all_res);

        } else {
            var date_brone = $(this).children('input').val();
            var all_res = $('.res_brone_date').val();
            var arr = all_res.split(',');

            var i = 0;
            jQuery.each(arr, function () {

                if (date_brone == this) {
                    arr.splice(i, 1);
                }

                i++;
            });

            $('.res_brone_date').val(arr.join(','));
        }


    });

});