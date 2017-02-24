<?php
class UserStatsForGame {

    const surl = "http://api.steampowered.com/ISteamUserStats/GetUserStatsForGame/v0002/";

    function __construct($app_id, $api_key, $steam_profile_id) {
        $this->api_url = self::surl ."?appid=" . $app_id . "&key=". $api_key . "&steamid=" . $steam_profile_id;
    }

    public function serve() {
        $str = file_get_contents($this->api_url);
        $json = json_decode($str, true);

        // Add to array and sort
        $stats = array();
        foreach ($json['playerstats']['stats'] as $i) {
            $stats[$i['name']] = $i['value'];
        }

        ksort($stats);


        header('Content-type: application/json');
        print(json_encode($stats));

    }
}
