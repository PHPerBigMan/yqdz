$(function () {
  'use strict';
  
    $(".swiper-container").swiper({
      pagination: '.swiper-pagination',
      paginationClickable: true,
      centeredSlides: true,
      autoplay: 2000,
      autoplayDisableOnInteraction: false
    });


    $(document).on("pageInit", "#page-city-picker", function(e) {
      $("#city-picker").cityPicker({
        toolbarTemplate: '<header class="bar bar-nav">\
      <button class="button button-link pull-right close-picker">确定</button>\
      <h1 class="title">选择收货地址</h1>\
      </header>',
        
      });
    });



  $.init();
});
