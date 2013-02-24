<?php
/**
 * Created by JetBrains PhpStorm.
 * User: bernardo
 */

namespace Scraping;

use Scraping\Exceptions\ScrapingException;
use Scraping\Interfaces\ScrapingInterface;
use Scraping\Interfaces\CurlInterface;

class Scraping implements ScrapingInterface
{
    const VERSION = '0.1';

    protected $query_xpath;
    protected $attribute;
    protected $curl;
    protected $options = array(
        'debug' => false,
    );

    /**
     * @param CurlInterface $curl
     * @param array $options
     */
    public function __construct(CurlInterface $curl, array $options = array())
    {
        $this->curl = $curl;
        $this->setOptions($options);
    }

    /**
     * @param $query_xpath
     * @param null $attribute
     */
    public function filter($query_xpath, $attribute = null)
    {
        $this->query_xpath = $query_xpath;
        $this->attribute = $attribute;
    }

    /**
     * @return array(dom_element|value attribute)|bool
     * @throws ScrapingException
     */
    public function perform()
    {
        if (!$this->query_xpath) {
            throw new ScrapingException(sprintf("%s : undefined query_xpath", __METHOD__));
        }

        $response = $this->curl->perform(); // html or xml

        $dom = new \DOMDocument();
        @$dom->loadHTML($response);

        $xpath = new \DOMXPath($dom);
        $list = $xpath->query($this->query_xpath);
        $result = array();

        if (0 === $list->length) {
            return false;
        } else {

            for ($i = 0; $i < $list->length; $i++) {

                if ($this->options['debug']) {
                    echo "Iteration $i \n";
                }

                $dom_element = $list->item($i);
                $result[] = ($this->attribute)? $dom_element->getAttribute($this->attribute): $dom_element;
            }
        }

        return $result;
    }

    /**
     * @return Interfaces\CurlInterface
     */
    public function getCurl()
    {
        return $this->curl;
    }

    /**
     * @param array $options
     */
    protected function setOptions(array $options = array())
    {
        if (!empty($options))
        {
            $this->options = array_merge($this->options, $options);
        }
    }

}