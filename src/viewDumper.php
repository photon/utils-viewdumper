<?php

namespace photon\utils;

class viewDumper
{
    /**
     * Dump from a view name defined in the configuration
     *
     * @param string path of the file to create (i.e. /home/foobar/index.html).
     * @param string View.
     * @param array Parameters for the view (array()).
     * @param array Extra GET parameters for the view (array()).
     */
    static function fromView($path, $view, $params=array(), $get_params=array())
    {
        $url = \photon\core\URL::forView($view, $params, $get_params);
        static::fromURL($path, $url);
    }

    /**
     * Dump from a URL
     *
     * @param string path of the file to create (i.e. /home/foobar/index.html).
     * @param string url.
     */
    static function fromURL($path, $url)
    {
        echo 'Saving "' . $url . '" into "' . $path . '"' . PHP_EOL;
        
        // Generate the view content
        $request = \photon\test\HTTP::baseRequest('GET', $url);
        $response = \photon\core\Dispatcher::match($request);

        // If the output is NOT HTML, write the content on disk
        $type = $response->headers['Content-Type'];
        if (strstr($type, 'text/html') === false) {
            file_put_contents($path, $response->content, LOCK_EX);
            return;
        }
    
        // If the output is HTML, generate extra data to write on disk
        echo 'Downloading extra content and patching links' . PHP_EOL;

        $dom = new \DOMDocument();
        @$dom->loadHTML($response->content);   // Lot of warning on HTML5 tags like nav
        $xpath = new \DOMXpath($dom);

        foreach ($xpath->query('//script[@src]') as $script) {
            $src = $script->getAttribute('src');
            $script->setAttribute('src', static::downloadExtra($path, $src));
        }

        foreach ($xpath->query('//a[@href]') as $a) {
            $href = $a->getAttribute('href');
            if (substr($href, -1) === '/') {
                continue;
            }

            $a->setAttribute('href', static::downloadExtra($path, $href));
        }

        foreach ($xpath->query('//link[@href]') as $link) {
            $href = $link->getAttribute('href');
            $link->setAttribute('href', static::downloadExtra($path, $href));
        }

        foreach ($xpath->query('//img[@src]') as $img) {
            $src = $img->getAttribute('src');
            $img->setAttribute('src', static::downloadExtra($path, $src));
        }

        $content = $dom->saveHTML();
        file_put_contents($path, $content, LOCK_EX);
    }

    static private function downloadExtra($path, $url)
    {
        // Relative path
        if (substr($url, 0, 1) === '/') {
            $ret = basename($path) . '_data' . $url;

            // Avoid multiple download
            $pathDownload = $path . '_data' . $url;
            if (file_exists($pathDownload)) {
                return $ret;
            }

            // Ensure folder exists
            $folder = dirname($pathDownload);
            if (is_dir($folder) === false) {
                mkdir($folder, 0777, true);
            }

            // Download content
            static::fromURL($pathDownload, $url);
            
            return $ret;
        }

        // Absolute path, do not modify
        return $url;
    }
}

