<?php

namespace OCA\Tutorial5\Tests;

use OCA\Tutorial5\AppInfo\Application;

class NoteServiceTest extends \PHPUnit\Framework\TestCase {

	public function testDummy() {
		$app = new Application();
		$this->assertEquals('tutorial_5', $app::APP_ID);
	}
}
