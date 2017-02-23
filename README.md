# suiram_org_api


### Untappd :beer:

**Usage:**

```php
<?php
include_once("UntappdRssApi.php");

// Your Untappd RSS feed
$rss_url = 'https://untappd.com/rss/user/crea1?key=304cd4ac3b0ae27c65ac50a0ba4789d7';

// How long time you want to use the cache. Use 0 to avoid caching
$cache_expire_seconds = 60;

$untappd_rss_api = new UntappdRssApi($rss_url, $cache_expire_seconds);
$untappd_rss_api->serve();
```