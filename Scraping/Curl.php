<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bernardo
 */

namespace Scraping;

use Scraping\Interfaces\CurlInterface;
use Scraping\Exceptions\CurlException;

class Curl implements CurlInterface
{
    const VERSION = '0.1';

    protected $target;
    protected $options = array(
        'timeout' => 10,
        'useragent' => 'Googlebot/2.1 (http://www.googlebot.com/bot.html)',
        'failonerror' => true,
        'followlocation' => true,
        'autoreferer' => true,
        'returntransfer' => true
    );

    /**
     * @param $target
     * @param array $options
     */
    public function __construct($target, Array $options = array())
    {
        $this->target = $target;
        $this->setOptions($options);
    }

    /**
     * @return mixed
     */
    public function perform()
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->target);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->options['useragent']);
        curl_setopt($ch, CURLOPT_FAILONERROR, $this->options['failonerror']);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, $this->options['followlocation']);
        curl_setopt($ch, CURLOPT_AUTOREFERER, $this->options['autoreferer']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, $this->options['returntransfer']);
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->options['timeout']);

        $response = curl_exec($ch);

        curl_close($ch);

        if (false === $response) {
            throw new CurlException(sprintf("%s : error curl", __METHOD__));
        }

        return $response;
    }

    /**
     * @param array $options
     */
    protected function setOptions(Array $options = array())
    {
        if (!empty($options)) {
            $this->options = array_merge($this->options, $options);
        }
    }

}