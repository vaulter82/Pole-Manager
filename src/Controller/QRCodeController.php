<?php

namespace Drupal\pole_manager\Controller;

use Symfony\Component\HttpFoundation\Response;
use ZXing\QRReader;

class QRCodeController {
	public function decode($image="test") {
		return new Response($image);
	}
}
