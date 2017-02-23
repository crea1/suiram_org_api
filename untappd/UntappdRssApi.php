<?php
class UntappdRssApi {
	private $rss_url = 'https://untappd.com/rss/user/crea1?key=304cd4ac3b0ae27c65ac50a0ba4789d7';
	
	## Start method
	public function serve() {
		$rss_feed = simplexml_load_file($this->rss_url);

		# Return result
		header('Content-type: application/json');
		print json_encode($this->get_all_beers($rss_feed));
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
		# ^.*an?       - Matches "Marius K. is drinking a" or ".. an"
		# (.*)         - Gets the beer
		# \s\bby\b\s*  - Matches " by  "
		# (.*)         - Gets the brewery, which should stop at next match.
		# (\s\bat\b|$) - Matches end of line, or if there is an location. Then it matches " at "
		#$pattern = '/^.*an? (.*)\s\bby\b\s*(.*)(?=at|$)/';	
		$pattern = '/^.*an? (.+)\s\bby\b\s*(.+?)(\sat|\z)/';	

		preg_match($pattern, $item->title, $matches);
		
		$date = (string) $item->pubDate;
		
		$array = array('beer'=>$matches[1], 'brewery'=>$matches[2], 'date'=>$date);

		return $array;
	}


}
$untappd_rss_api = new UntappdRssApi();
$untappd_rss_api->serve(); 
?>
