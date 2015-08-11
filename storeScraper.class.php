<?php

class storeScraper
{
    /*
     * Static method to scrape Google Play store data for a given application name
     * @param $app_name
     * @return $return
     */

    public static function scrapePlayStore($app_name)
    {
        $result = self::getPage("https://play.google.com/store/apps/details?id={$app_name}&hl=en", "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420  (KHTML, like Gecko) Version/3.0 Mobile/1A543a Safari/419.3");

        if(!$result) {
            return false;
        }

        // Get date of last update
        $return['last_update'] = strtotime(self::getContent($result, '<div class="content" itemprop="datePublished">', '</div>'));

        // Get version of last update
        $return['version'] = self::getContent($result, '<div class="content" itemprop="softwareVersion">', '</div>');

        if($return['version'] != "Varies with device") {
            // Get size of last update
            $return['file_size'] = preg_replace("/[^0-9,.]/", "", self::getContent($result, '<div class="content" itemprop="fileSize">', '</div>'));
        } else {
            $return['file_size'] = NULL;
        }

        return $return;
    }

    /*
     * Static method to scrape Apple App store data for a given application name and ID
     * @param $app_name
     * @param $app_id
     * @return $return
     */

    public static function scrapeAppStore($app_name, $app_id)
    {
        $result = self::getPage("https://itunes.apple.com/en/app/{$app_name}/id{$app_id}", "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/22.0.1207.1 Safari/537.1");

        if(!$result) {
            return false;
        }

        // Get date of last update
        $return['last_update'] = strtotime(self::getContent($result, 'Etc/GMT">', '</span>'));

        // Get size of last update
        $return['file_size'] = preg_replace("/[^0-9,.]/", "", self::getContent($result, '<li><span class="label">Size: </span>', '</li>'));

        // Get version of last update
        $return['version'] = self::getContent($result, '<span itemprop="softwareVersion">', '</span>');

        return $return;
    }

    /*
     * Fetches a given URL with the provided user agent
     * @param $url
     * @param $user_agent
     * @return $result
     */

    private static function getPage($url, $user_agent)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

        $result = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($code != 200) {
            return false;
        }

        return $result;
    }

    /*
     * Returns the content between provided unique start and end tags from the given html code
     * @param $html
     * @param $start
     * @param $end
     * @return string
     */

    private static function getContent($html, $start, $end)
    {
        $start_pos = strpos($html, $start) + strlen($start);

        if($start_pos === FALSE) {
            return false;
        }

        $end_pos = strpos($html, $end, $start_pos);

        if($end_pos === FALSE) {
            return false;
        }

        return trim(substr($html, $start_pos, $end_pos - $start_pos));
    }
}

?>

