$('.js-menu-toggle').on('click', function() {
  $(this).toggleClass('menu-toggle--is-active');
  $('.main-navigation').toggleClass('main-navigation--is-active');
});
