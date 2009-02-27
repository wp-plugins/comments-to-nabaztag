<?php

class NabaztagAPI
{
  private $endpoint = "http://api.nabaztag.com/vl/FR/api.jsp?";
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
    $response = $this->callNabaztag();
    return ($response instanceof SimpleXMLElement && isset($response->rabbitName));
  }
  
  /**
   * Get the nabaztags name
   * @return string name
   */
  public function getRabbitName()
  {
    $this->call_params["action"] = 10;
    $response = $this->callNabaztag();
    return (string)$response->rabbitName;
  }
  
  /**
   *  Starts a choeographie
   *
   * @return $this
   */
  
  public function startChoreographie()
  {
     $this->call_params["chor"] = "1,";
     return $this;
  }

  /**
   * Add led command
   * @param integer ear (1 left, 1 right)
   * @param integer angle (between 1 and 180)
   * @param integer rotation direction (1 or 2)
   * @param integer l'heure (time)
   * @return $this
   */
  public function addEarCommand($timeslice, $ear, $angle, $rotation)
  {
	$this->call_params["chor"] .= $timeslice.",motor,".$ear.",".$angle.",0,".$rotation;
	return $this;
  }

  /**
   * Add ear command
   * @param integer Led (0 bottom, 1 left, 2 middle, 3 right, 4 nose)
   * @param array Hex-Color
   * @param integer l'heure (time)
   * @return $this
   */
  public function addLedCommand($timeslice, $led, $color)
  {
	$this->call_params["chor"] .= $timeslice.",led,".$led.",".implode(",",$color).",";
	return $this;
  }

  /**
   * Sends a TTS (text to speech) message to the nabaztag
   * @param string $message
   * @return string
   */  

  public function sendTts($message)
  {
    $this->call_params = array("tts" => urlencode($message));
    return $this->callNabaztag();
  }

  /**
   * Returns a list with the available voices for the nabaztag
   */
  public function getVoicesList()
  {
    $this->call_params["action"] = 9;
    $voices = $this->callNabaztag();
    $langs  = $this->getSelectedLanguages();
    $proper_voices = array();
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
    $result = $this->callNabaztag();
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
  public function callNabaztag()
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
}
?>