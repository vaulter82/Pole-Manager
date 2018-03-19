(function($, Drupal) {
  function init() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(function(stream) {
      video.srcObject = stream;
      video.setAttribute("playsinline", true);
      video.play();
      requestAnimationFrame(tick);
    });
  }
  
  function dinit() {
    video.pause();
  }
  
  $('body').append('<div id="pole_manager_code_reader_container" style="display:none;position:fixed;top:0;left:0;height:100%;width:100%;"></div>');
  $('body').append('<button id="initialize_scan_button" class="pole_manager_code_reader_button" style="position:fixed;bottom:15px;right:15px;"><i class="fas fa-camera"></i></button>');

  $('#pole_manager_code_reader_container').append('<button id="pole_manager_code_reader_close_button" class="pole_manager_code_reader_button"><i class="fas fa-times"></i></button>');
  $('#pole_manager_code_reader_container').append('<canvas id="pole_manager_code_reader_canvas"></canvas>');
  $('#pole_manager_code_reader_container').append('<div id="pole_manager_code_reader_message"></div>')

  var video = document.createElement("video");
  var canvasElement = document.getElementById("pole_manager_code_reader_canvas");
  var canvas = canvasElement.getContext("2d");
  var container = document.getElementById("pole_manager_code_reader_container");
  var message = document.getElementById("pole_manager_code_reader_message");
  var initializeButton = document.getElementById("initialize_scan_button");
  var closeButton = document.getElementById("pole_manager_code_reader_close_button");

  initializeButton.onclick = init;
  closeButton.onclick = dinit;

  function drawLine(begin, end, color) {
    canvas.beginPath();
    canvas.moveTo(begin.x, begin.y);
    canvas.lineTo(end.x, end.y);
    canvas.lineWidth = 4;
    canvas.strokeStyle = color;
    canvas.stroke();
  }

  function tick() {
    if( video.readyState === video.HAVE_ENOUGH_DATA && !video.paused ) {
      $('body > :not(#pole_manager_code_reader_container)').hide();
      
      container.style.display = "initial";
      canvasElement.height = video.videoHeight;
      canvasElement.width = video.videoWidth;
      canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);

      var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
      var code = jsQR(imageData.data, imageData.width, imageData.height);

      if( code ) {
        drawLine(code.location.topLeftCorner, code.location.topRightCorner, "#FF3B58");
        drawLine(code.location.topRightCorner, code.location.bottomRightCorner, "#FF3B58");
        drawLine(code.location.bottomLeftCorner, code.location.bottomRightCorner, "#FF3B58");
        drawLine(code.location.topLeftCorner, code.location.bottomLeftCorner, "#FF3B58");

        if( $(message).find(':contains(' + code.data + ')') ) {
          $(message).append('<p>' + code.data + '</p>');
        }
      } else {

      }
    } else {
      container.style.display = "none";
      
      $('body > :not(#pole_manager_code_reader_container)').show();
    }

    requestAnimationFrame(tick);
  }
}(jQuery, Drupal));
