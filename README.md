# Description

StoreScraper is a PHP scraper for the Google Play store and Apple App store that provides the following information about an application:
* Last update date
* File size of the application package
* Application version

# Requirements

* `php5`
* `php5-curl`

# Usage

### Android Application

    <?php
    
    include("storeScraper.class.php");
    $snapchat = storeScraper::scrapePlayStore("com.snapchat.android");
    
    if(!$snapchat) {
        echo "Failed to get Google Play Store data for the application.";
    } else {
        echo "Last updated: " . date("d/m/Y", $snapchat['last_update']) . "<br/>";
        echo "Latest version: " . $snapchat['version'] . "<br/>";
        echo "File size: " . $snapchat['file_size'] . " MB";
    }
    
    ?>
    
The application name can be found in the Google Play store URL for the application, for example:  

    https://play.google.com/store/apps/details?id=com.snapchat.android
                                                 |______App_Name______|


Note: Some Android applications provide different application packages depending on the device platform. This causes the latest version to display as "Varies with device" and the resulting file size is `NULL`.

### iOS Application

    <?php
    
    include("storeScraper.class.php");
    $snapchat = storeScraper::scrapeAppStore("Snapchat", 447188370);
    
    if(!$snapchat) {
        echo "Failed to get Apple App Store data for the application.";
    } else {
        echo "Last updated: " . date("d/m/Y", $snapchat['last_update']) . "<br/>";
        echo "Latest version: " . $snapchat['version'] . "<br/>";
        echo "File size: " . $snapchat['file_size'] . " MB";
    }
    
    ?>
    
The application name and id can be found in the Apple App store URL for the application, for example: 

    https://itunes.apple.com/us/app/Snapchat/id447188370
                                   |App_Name| |_App_ID__|
    
    
# Disclaimer

This repository is in no way affiliated with Google™ or Apple Inc™.

