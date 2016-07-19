<?php

return array(
    'secret_key' => 'abc123',
    'tmp_folder' => sys_get_temp_dir(),
    'template_folders' => array(
        __DIR__ .'/tests/templates',
    ),
    'base_urls' => '',
    'urls' => array(
        array('regex' => '#^/nolinks$#',
              'view' => array('\photon\views\Template', 'simple'),
              'params' => 'nolinks.html',
              'name' => 'nolinks'),
    ),
);
