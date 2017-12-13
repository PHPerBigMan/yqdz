/**
 * picLazyLoad 图片延时加载，包含背景图片
 * $(img).picLazyLoad({...})
 * data-original 预加载图片地址
 * alon
 */
;(function($){
    $.fn.imgLazyLoad = function(settings){
        var $this = $(this),
            _winScrollTop = 0,
            _winHeight = $(window).height();
        settings = $.extend({
            threshold: 0, // 提前高度加载
            placeholder: 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsQAAA7EAZUrDhsAAAANSURBVBhXYzh8+PB/AAffA0nNPuCLAAAAAElFTkSuQmCC',
            callback:function(){}
        }, settings||{});
        // 执行懒加载图片
        lazyLoadPic();
        // 滚动触发换图
        $(window).on('scroll',function(){
            _winScrollTop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
            lazyLoadPic();
        });
        // 懒加载图片
        function lazyLoadPic(){
            $this.each(function(){
                var $self = $(this);
                if($self.is('img')){
                    if($self.attr('data-original')){
                        var _offsetTop = $self.offset().top;
                        if((_offsetTop - settings.threshold) <= (_winHeight + _winScrollTop)){
                            $self.attr('src',$self.attr('data-original'));
                            $self.removeAttr('data-original');
                            $self.removeClass('loadH');
                            settings.callback($self);
                        }
                    }
                }else{
                    if($self.attr('data-original')){// 默认占位图片
                        if($self.css('background-image') == 'none'){
                            $self.css('background-image','url('+settings.placeholder+')');
                        }
                        var _offsetTop = $self.offset().top;
                        if((_offsetTop - settings.threshold) <= (_winHeight + _winScrollTop)){
                            $self.css('background-image','url('+$self.attr('data-original')+')');
                            $self.removeAttr('data-original');
                            settings.callback($self);
                        }
                    }
                }
            });
        }
    }
})(Zepto);
