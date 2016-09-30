<?php
use Cmfcmf\OpenWeatherMap\AbstractCache;

function color($c) {
	$r = round(($c * (255 / 20)));
	$b = round(255 - ($c * (255 / 50))) ;
	$g = $c < 15 ? round($b  * 0.5) : round($r * 0.5);
	return array($r, $g, $b);
}

function gradient($c) {
  return array(
    color(($c - 5)),
    color(($c + 5))
  );
}

class FileSystemCache extends AbstractCache {

    private function urlToPath($url) {
        if(!is_dir(__DIR__ . "/data/cache/")) mkdir(__DIR__ . "/data/cache/");
        return __DIR__ . "/data/cache/" . md5($url);
    }

    public function isCached($url) {
        $path = $this->urlToPath($url);
        if (!file_exists($path) || filectime($path) + $this->seconds < time()) {
            return false;
        }
        return true;
    }

    public function getCached($url) {
        return file_get_contents($this->urlToPath($url));
    }

    public function setCached($url, $content) {
        file_put_contents($this->urlToPath($url), $content);
    }

}
?>