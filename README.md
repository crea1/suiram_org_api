# suiram_org_api


### Steam :video_game:
**Usage:**
```php
// Id of game. 730=CS:GO, 240=CS:S, 440=TF2
$app_id = "730";
// Steam API Key https://steamcommunity.com/dev/apikey
$api_key="1234567890ABCDEF1234567890ABCDEF";
// Your Steam profile id
$profile_id="700000000000000000";

$userStatsForGame = new UserStatsForGame($app_id, $api_key, $profile_id);
$userStatsForGame->serve();
```

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