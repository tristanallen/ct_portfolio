(function($){ $(function(){

var $window = $( window );
var $workRows = $( '.work-row' );
var c = 0;
$workRows.each(function(){
  var leftY = Math.random() * 50; 
  var rightY = Math.random() * 50; 
  $(this).prepend( ''
   + '<svg width="100%" height="500" '
   +    'viewPort="0 0 120 120" version="1.1" '
   +    'xmlns="http://www.w3.org/2000/svg" '
   +    'style="top: -70px" '
   + '>'
   +   '<defs>'
   +     '<linearGradient '
   +       'id="gradient" '
   +       'x1="0%" y1="0%" x2="100%" y2="0%" '
   +       'gradientTransform="rotate(90)" '
   +     '>'
   +       '<stop offset="50%" style="stop-color:rgb(0,0,0);stop-opacity:1" />'
   +       '<stop offset="100%" style="stop-color:rgb(120,120,120);stop-opacity:1" />'
   +     '</linearGradient>'
   +   '</defs>'
   +   '<polygon points="'
   +      '0,'+ (leftY+1) +' '
   +      '1500,'+ (rightY+1) +' '
   +      '1500,500 ' 
   +      '0,500" '
   +      'fill="url(#gradient)"/>'
   +   '<polygon '
   +      'class="color"'
   +      'points="'
   +        '0,'+ leftY +' '
   +        '1500,'+ rightY +' '
   +        '1500,500 ' 
   +        '0,500" '
   +      'fill="#cb'+c+'"/>'
   + '</svg>'
  );
  c += 3;
});

$splash = $( '#content .splash' );
var workRowTop = $splash.offset().top + $splash.outerHeight() - 50;
console.log( workRowTop );
var workRowIntervalY = $('.work-row').outerHeight(true) + 0;

$window.on( 'resize', function(event){
  if( $window.width() >= 768 ){
    $window.on( 'scroll', onScroll );
  }else{
    $window.off( 'scroll', onScroll );
    $workRows.find('.outfit img').css('opacity', 1);
  }
});
$window.trigger( 'resize' );

function onScroll(event){
  var scrollTop = $window.scrollTop();
  var i = 0;

  $splash.css( 'top', scrollTop * -0.3 );
  $splash.children( 'p' ).css( 'top', scrollTop * -0.5 );

  $workRows 
    .each(function(){
      var row = ++i - 1;
      var container = $(this);
      var threshold = row * workRowIntervalY + workRowTop;
      var rotation = ( scrollTop - threshold ) / 4.5;
      var opacity = 1;
      if( scrollTop > threshold ){
        container.children('.inner').css( {
          'transform': ''
            + 'rotateX('+rotation+'deg)'
        });
        container.find('.work-thumb, svg .color').css({
          'opacity': ''
            + ((90-rotation)/90)
        });
        container.find('.work-thumb').css({
          'opacity': ''
            + ((180-rotation)/180)
        });
      }else{
        container.children('.inner').css( {
          'transform': ''
            + 'rotateX(0deg)'
        });
      }
    });
}
$( '#container' ).css( 'height', 0
  + $( '#masthead' ).outerHeight( true )
  + $( '#content'  ).outerHeight( true )
);

}); })(jQuery);
