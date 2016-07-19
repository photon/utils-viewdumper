<?php

use \photon\utils\viewDumper;

class viewDumperTest extends \photon\test\TestCase
{
    public function testNoLinks()
    {
        viewDumper::fromView('/tmp/nolinks-view.html', 'nolinks');
        viewDumper::fromURL('/tmp/nolinks-url.html', '/nolinks');

        $view = file_get_contents('/tmp/nolinks-view.html');
        $url = file_get_contents('/tmp/nolinks-url.html');
        $this->assertEquals($view, $url);
    }
}

