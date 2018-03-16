(function($, Drupal) {
  function init() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
      video.srcObject = stream;
      video.setAttribute("playsinline", true);
      video.play();
      requestAnimationFrame(tick);
    });
  }

  $('body').append('<button id="initialize_scan_button" style="position:fixed;bottom:15px;right:15px;height:30px;width:50px;">Scan</button>');
  $('body').append('<canvas id="pole_manager_code_reader_canvas" style="display:none;position:fixed;top:0px;left:0px;height:90%;width:100%"></canvas>');
  $('body').append('<div id="pole_manager_code_reader_message" style="display:none;position:fixed;bottom:0px;left:0px;height:10%;width:100%"></div>')

  var video = document.createElement("video");
  var canvasElement = document.getElementById("pole_manager_code_reader_canvas");
  var canvas = canvasElement.getContext("2d");
  var message = document.getElementById("pole_manager_code_reader_message");
  var initializeButton = document.getElementById("initialize_scan_button");

  initializeButton.onclick = init;

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
      initializeButton.style.display = "none";
      message.style.display = "initial";
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

        message.innerHTML = code.data;
      } else {

      }
    }

    requestAnimationFrame(tick);
  }
}(jQuery, Drupal));
