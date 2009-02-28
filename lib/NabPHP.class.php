<?php
/**
 * NabPHP is a Open Source PHP-Library for communication with the Nabaztag
 * 
 * @author Robert Curth
 * @package NabPHP
 * @license GNU General Public License v3
 */

class NabPHP
{
  private $endpoint = "http://api.nabaztag.com/vl/FR/api.jsp?";
  private $stream_endpoint = "http://api.nabaztag.com/vl/FR/api_stream.jsp?";
  private $auth_params; // Associative array with Sn + Token
  private $call_params; // Associative array with the params for this API-Call
  private $led_store;
  private $settings;  

  /**
   * Constructs the first part of the nabaztag url, including credentials
   * @param string Serialnumber of the nabaztag ($sn)
   * @param string Auth-Token of the nabaztag ($token)
   * @param array settings
   */
  function __construct($sn, $token, $settings = array())
  {
    $this->auth_params = array("sn" => $sn, "token" => $token);
    $this->call_params = array();
    $this->settings = $settings;
  }
  
  /**
   * Validates the current credentials
   * @return boolean
   */
  public function validateCredentials()
  {
    $this->call_params["action"] = 10;
    $response = $this->callApi();
    return ($response instanceof SimpleXMLElement && isset($response->rabbitName));
  }
  
  /**
   * Get the nabaztags name
   * @return string name
   */
  public function getRabbitName()
  {
    $this->call_params["action"] = 10;
    $response = $this->callApi();
    return (string)$response->rabbitName;
  }

  /**
   * Sends a TTS (text to speech) message to the nabaztag
   * @param string $message
   * @return string
   */  

  public function sendTts($message)
  {
    $this->call_params = array("tts" => urlencode($message));
    return $this->callApi();
  }

  /**
   * Returns a list with the available voices for the nabaztag
   */
  public function getVoicesList()
  {
    $this->call_params["action"] = 9;
    $voices = $this->callApi();
    $langs  = $this->getSelectedLanguages();
    $result = array();

    foreach($voices->voice as $voice)
    {
	  if(in_array($voice["lang"], $langs))
	  {
	    $result[] = (string)$voice["command"];
	  }
    }
    return $result;	
  }

  /**
   * Retrieve languages for this nabaztag
   */
  public function getSelectedLanguages()
  {
    $this->call_params["action"] = 11;
    $result = $this->callApi();
    $langs = array();
    foreach($result->myLang as $lang)
    {
	  $langs[] = (string)$lang["lang"];
    }    

    return $langs;
  }

  /**
   * Constructs url and fires the request returns answer
   */
  private function callApi()
  {
    $params = array_merge($this->auth_params, $this->call_params, $this->settings);
    $url = "";  

    $param_url = "";
    foreach($params as $key => $val){
      $param_url .= sprintf("&%s=%s", $key, $val);
    }

    $url = $this->endpoint.substr($param_url, 1);
    $this->call_params = array();
    return simplexml_load_file($url);
  }

  /**
   * Send a chor
   *
   * @param NabChor
   * @return result
   */
   public function sendChor(NabChor $chor)
   {
     $this->call_params["chor"] = $chor->getChor();
     return $this->callApi();
   } 
}