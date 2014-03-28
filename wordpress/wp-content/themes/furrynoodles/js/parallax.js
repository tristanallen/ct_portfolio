(function($){ $(function(){

var $window = $( window );
var $workThumbs = $( 'article.work-thumb' );
$workThumbs.each(function(){
  var $this = $(this);
  $( '<div class="inner" ></div>' )
    .append( $this.children() )
    .appendTo( $this )
    ;
});

var workThumbTop = $( '#content' ).offset().top - 20;
var workThumbIntervalY = $('.work-thumb').outerHeight(true);

$window.on( 'resize', function(event){
  if( $window.width() >= 768 ){
    $window.on( 'scroll', onScroll );
  }else{
    $window.off( 'scroll', onScroll );
    $workThumbs.find('.outfit img').css('opacity', 1);
  }
});
$window.trigger( 'resize' );

function onScroll(event){
  var scrollTop = $window.scrollTop();
  var i = 0;

  $workThumbs 
    .each(function(){
      var row = Math.ceil( ++i / 2 ) - 1;
      //var row = ++i - 1;//Math.ceil( ++i / 2 ) - 1;
      var container = $(this);
      var threshold = row * workThumbIntervalY + workThumbTop;
      var rotation = ( scrollTop - threshold ) / 4.5;
      if( scrollTop > threshold ){
        container.children('.inner').css( {
          'transform': ''
            + 'rotateX('+rotation+'deg)'
        });
        container.find('.outfit img').css({
          'opacity': ''
            + ((90-rotation)/90)
        });
      }
    });
}
$( '#container' ).css( 'height', 0
  + $( '#masthead' ).outerHeight( true )
  + $( '#content'  ).outerHeight( true )
);

}); })(jQuery);
