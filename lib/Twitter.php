<?php
/**
 * Wrapper class around the Twitter API for PHP
 * Based on the class originally developed by David Billingham
 * and accessible at http://twitter.slawcup.com/twitter.class.phps
 *
 * @author     David Billingham <david@slawcup.com>
 * @author     Aaron Brazell <aaron@technosailor.com>
 * @author     Keith Casey <caseysoftware@gmail.com>
 * @version    1.1
 * @package    php-twitter
 * @subpackage classes
 */

namespace Lib;

require __DIR__ . '/../vendor/autoload.php';

use Codebird\Codebird;

class Twitter
{
    protected $consumer_key = 'WvG0qP3gZxR3PePpwD1U8t6cx';
    protected $consumer_secret = 'MqlOK1K7zCpMlMU6nAjWY17FLWTLDd11IB96BRWHAiy2RhliA3';
    protected $access_token = '50072103-vIE0GF7GDDr2Iqds93azid2t2Ir2zPit2zl057y9p';
    protected $access_secret = 'pZ5kRB13udVW2vs7D4E1IJroITHniezkldjQ8D4ZdGfFN';
    protected $twitter;

    public function __construct()
    {
        // Fetch new Twitter Instance
        Codebird::setConsumerKey($this->consumer_key, $this->consumer_secret);
        $this->twitter = Codebird::getInstance();

        // Set access token
        $this->twitter->setToken($this->access_token, $this->access_secret);
    }

    public function getTimeLine()
    {
        $params  = array(
          'q'=>'filter:images',
          'user_id' => '265872526',
          'exclude_replies' => true,
          'include_rts' => false,
          'include_entities'=> true,
          'count' => '50',
        );
        return (array)$this->twitter->statuses_userTimeline($params);
    }

    public function tweet($message)
    {
        return $this->twitter->statuses_update(['status' => $message]);
    }
}