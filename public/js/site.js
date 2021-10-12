// jQuery(document).ready(function ($) {
$(function() {
    // Общие настройки ajax-запросов, отправка на сервер csrf-токена
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Раскрытие и скрытие пунктов меню каталога в левой колонке
    $('#catalog-sidebar > ul ul').hide();
    $('#catalog-sidebar .badge').on('click', function () {
        var $badge = $(this);
        var closed = $badge.siblings('ul') && !$badge.siblings('ul').is(':visible');
        if (closed) {
            $badge.siblings('ul').slideDown('normal', function () {
                $badge.children('i').removeClass('fa-plus').addClass('fa-minus');
            });
        } else {
            $badge.siblings('ul').slideUp('normal', function () {
                $badge.children('i').removeClass('fa-minus').addClass('fa-plus');
            });
        }
    });

    // Получение данных профиля пользователя при оформлении заказа
    $('form#profiles button[type="submit"]').hide();
    $('form#profiles select').on('change', function () {  // при выборе профиля отправляем ajax-запрос, чтобы получить данные
        if ($(this).val() == 0) {  // если выбран элемент «Выберите профиль»
            $('#checkout').trigger('reset'); // очищаем все поля формы оформления заказа
            return;
        }
        var data = new FormData($('form#profiles')[0]);
        $.ajax({
            url: '/basket/profile',
            data: data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'JSON',
            success: function(data) {
                if (data.profile === undefined) {
                    console.log('data undefined');
                }
                $('input[name="name"]').val(data.profile.name);
                $('input[name="email"]').val(data.profile.email);
                $('input[name="phone"]').val(data.profile.phone);
                $('input[name="address"]').val(data.profile.address);
                $('textarea[name="comment"]').val(data.profile.comment);
            },
            error: function (reject) {
                alert(reject.responseJSON.error);
            }
        });
    });

    // Добавление товара в корзину с помощью ajax-запроса без перезагрузки
    $('form.add-to-basket').on('submit', function(event){
        event.preventDefault(); // отменяем отправку формы стандартным способом
        var $form = $(this); // получаем данные этой формы добавления в корзину
        var data = new FormData($form[0]);
        $.ajax({
            url: $form.attr('action'),
            data: data,
            processData: false,
            contentType: false,
            type: 'POST',
            dataType: 'HTML',
            beforeSend: function () {
                var spinner = ' <span class="spinner-border spinner-border-sm"></span>';
                $form.find('button').append(spinner);
            },
            success: function (html) {
                $form.find('.spinner-border').remove();
                $('#top-basket').html(html);
            }
        });
    });
});
