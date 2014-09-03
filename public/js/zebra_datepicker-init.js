$(document).ready(function() {
  $('#datepickerFrom').Zebra_DatePicker({
    direction: true,
    pair: $('#datepickerTo'),
    days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
    days_abbr: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    show_select_today: 'Сегодня',
    lang_clear_date: 'Очистить',
    format: 'd.m.y',
    header_navigation: ['','']
  });

  $('#datepickerTo').Zebra_DatePicker({
    direction: 1,
    days: ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'],
    days_abbr: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
    months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
    show_select_today: 'Сегодня',
    lang_clear_date: 'Очистить',
    format: 'd.m.y',
    header_navigation: ['','']
  });
});