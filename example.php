#!/usr/bin/php
<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bernardo
 * Example execute: ./example.php > out_images_links
 */

require_once(__DIR__ . '/loader.php');

use Scraping\Scraping;
use Scraping\Curl;
use Scraping\Exceptions\CurlException;
use Scraping\Exceptions\ScrapingException;

try {

    $curl = new Curl('http://www.elcorteingles.es');

    $scraping = new Scraping($curl);
    $scraping->filter('//html/body//img','src');
    $result = $scraping->perform();
    // Images
    print_r($result);

    $scraping->filter('//html/body//a','href');
    $result = $scraping->perform();
    // Links
    print_r($result);

} catch (CurlException $e) {

    echo "Error curl: " . $e->getMessage() . "\n";

} catch (ScrapingException $e) {

    echo "Error Scraping: " . $e->getMessage() . "\n";
}
