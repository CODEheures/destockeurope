$('.shape.error.title').shape();
$('.shape.error.message').shape();
setInterval(function() {
  $('.shape.error-title').shape('flip over');
  $('.shape.error-message').shape('flip over');
},3500)