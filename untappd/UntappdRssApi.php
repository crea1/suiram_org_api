<?php
class UntappdRssApi {
	
	function __construct($rss_url, $cache_expire_seconds) {
		$this->rss_url = $rss_url;
		$this->cache_expire_seconds = $cache_expire_seconds;
	}

	## Start method
	public function serve() {
		$beers = null;
		if ($this->cache_expire_seconds > 0) {
			$beers = $this->get_beers_from_cache();
		}
		if ($beers == null) {
			$rss_feed = simplexml_load_file($this->rss_url);
			$beers = $this->get_all_beers($rss_feed);
		}

		# Return result
		header('Content-type: application/json');
		print json_encode($beers);

		$this->write_cachefile($beers);
	}

	## Get latest
	private function get_latest($rss_feed) {
		return $this->item_to_array($rss_feed->channel->item[0]);		
	}	

	## Get all from rss
	private function get_all_beers($rss_feed) {
		$array = array();
		foreach($rss_feed->channel->item as $item) {
			array_push($array, $this->item_to_array($item));
		}
		
		return $array;
	}	

	## Extract beer and brewery from title
	private function item_to_array($item) {
		# /^.*an? (.*)\s\bby\b\s*(.*)(\s\bat\b|$)/
		# ^.*drinking an?       - Matches "Marius K. is drinking a" or ".. an"
		# (.*)         - Gets the beer
		# \s\bby\b\s*  - Matches " by  "
		# (.*)         - Gets the brewery, which should stop at next match.
		# (\s\bat\b|$) - Matches end of line, or if there is an location. Then it matches " at "
		#$pattern = '/^.*an? (.*)\s\bby\b\s*(.*)(?=at|$)/';	
		$pattern = '/^.*drinking an? (.+)\s\bby\b\s*(.+?)(\sat|\z)/';

		preg_match($pattern, $item->title, $matches);
		
		$date = (string) $item->pubDate;
		
		$array = array('beer'=>$matches[1], 'brewery'=>$matches[2], 'date'=>$date);

		return $array;
	}

	private function write_cachefile($beers) {
		$cache_array = array("beers"=>$beers, "timestamp"=>time());
		$cache_file = fopen("rss_feed_cache.xml", "w") or die("unable to open cache file");
		fwrite($cache_file, json_encode($cache_array));
		fclose($cache_file);
	}

	private function read_cachefile() {
		if (file_exists("rss_feed_cache.xml")) {
			$cache_file = fopen("rss_feed_cache.xml", "r");
			$cache = json_decode(fread($cache_file, filesize("rss_feed_cache.xml")), true);
			fclose($cache_file);
			return $cache;
		} else {
			return null;
		}

	}

	## Get beers from cache if not expired
	private function get_beers_from_cache() {
		$cache = $this->read_cachefile();
		if ($cache != null && time() - $cache['timestamp'] < $this->cache_expire_seconds) {
			return $cache['beers'];
		} else {
			return null;
		}
	}


}
?>
