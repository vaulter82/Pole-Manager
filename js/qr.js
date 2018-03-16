(function($, Drupal) {
  $('body').append('<button id="initialize_scan" onclick="init()" style="position:fixed;bottom:15px;right:15px;height:30px;width:30px;">Scan</button>');
  $('body').append('<div id="pole_manager_code_reader_canvas" style="display:none;position:fixed;height:100%;width:100%">');
  
  var video = document.createElement("video");
  var canvasElement = document.getElementById("pole_manager_code_reader_canvas");
  var canvas = canvasElement.getContext("2d");
  
  function init() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
      video.srcObject = stream;
      video.setAttribute("playsinline", true);
      video.play();
      requestAnimationFrame(tick);
    });
  }
  
  function drawLine(begin, end, color) {
    canvas.beginPath();
    canvas.moveTo(begin.x, begin.y);
    canvas.lineTo(end.x, end.y);
    canvas.lineWidth = 4;
    canvas.strokeStyle = color;
    canvas.stroke();
  }
  
  function tick() {
    if( video.readyState === video.HAVE_ENOUGH_DATA ) {
      canvasElement.style.display = "initial";
      canvasElement.height = video.videoHeight;
      canvasElement.width = video.videoWidth;
      canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
      
      var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
      var code = jsQR(imageData.data, imageData.width, imageData.height);
      
      if( code ) {
        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
        drawLine(code.location.bottomLeftCorner, code.location.bottomRightCorner, "#FF3B58");
        drawLine(code.location.topLeftCorner, code.location.bottomleftCorner, "#FF3B58");
      }
    }
    
    requestAnimationFrame(tick);
  }
}(jQuery, Drupal));
