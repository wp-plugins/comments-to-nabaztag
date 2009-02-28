<?php 
/**
 * Help you construct a Nab Chorus
 *
 * @author Robert Curth
 * @package NabPHP
 * @license GNU General Public License v3
 */

class NabChor
{
  private $chor;

  /**
   *  Starts a choeographie
   *
   * @return $this
   */
  
  public function __construct($slice_lenght)
  {
     $this->chor = $slice_lenght;
  }

  /**
   * Get $this->chor
   * @return strin This chor
   */
  public function getChor()
  {
    return $this->chor;	
  }

  /**
   * Add led command
   * @param integer ear (1 left, 1 right)
   * @param integer angle (between 1 and 180)
   * @param integer rotation direction (0 or 1)
   * @param integer l'heure (time)
   * @return $this
   */
  public function addEar($timeslice, $ear, $angle, $rotation)
  {
	// Validating input
	if(!($ear === 1 || $ear === 0))
	{
		throw new InvalidArgumentException("Invalid Ear. Only 0 for the left and 1 for the right ears are allowed.");
	}
	if(($angle < 0 || $angle > 180))
	{
		throw new InvalidArgumentException("Invalid Angle. Value must be between 0 and 360.");
	}
	if(!($rotation === 0 || $rotation === 1))
	{
		throw new InvalidArgumentException("Invalid rotation direction. Value 0 or 1.");
	}	
	
	$this->chor .= ",".$timeslice.",motor,".$ear.",".$angle.",0,".$rotation;
	return $this;
  }

  /**
   * Add ear command
   * 
   * @param integer timeslice
   * @param integer Led (0 bottom, 1 left, 2 middle, 3 right, 4 nose)
   * @param array Hex-Color
   * @return $this
   */
  public function addLed($timeslice, $led, $color)
  {
	$this->chor .= ",".$timeslice.",led,".$led.",".implode(",",$color).",";
	return $this;
  }	
}