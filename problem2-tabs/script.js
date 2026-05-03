$(function () {
  $('.tab-link').on('click', function (event) {
    event.preventDefault();
    const target = $(this).attr('href');

    $('.tab-link').removeClass('active');
    $(this).addClass('active');

    $('.tab-panel').removeClass('active');
    $(target).addClass('active');
  });
});
