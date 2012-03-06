<?php


require 'base_facebook.php'; 
require 'facebook.php'; 
/*
 * Created on Mar 15, 2011
 * 
 * Provides access to Custom application properties along with the Facebook Platform.
 * 
 * @author makeurownrules <info@makeurownrules.com>
 */
 class FacebookWrapper extends Facebook{
  
  /**
   * The Application name.
   */
  protected $appName;
  /**
   * The Application canvas page.
   */
  protected $canvasPage;
  
  protected $canvasUrl;
  /* Initialize a Facebook Application.
   *
   * The configuration:
   * - appId: the application ID
   * - secret: the application secret
   * - cookie: (optional) boolean true to enable cookie support
   * - domain: (optional) domain for the cookie
   * - fileUpload: (optional) boolean indicating if file uploads are enabled
   * - appName: (optional)Name of the Application,
   * - canvasPage: (optional)Canvas Page of the Application,
   * @param Array $config the application configuration
   */
  public function __construct($config) {
  	parent::__construct($config);
  	if (isset($config['appName'])) {
      $this->setAppName($config['appName']);
    }
    if (isset($config['canvasPage'])) {
      $this->setCanvasPage($config['canvasPage']);
    }
    if (isset($config['canvasUrl'])) {
      $this->setCanvasUrl($config['canvasUrl']);
    }
  }
  
  
  /**
   *  Set the Application Name.
   *
   */
  public function setAppName($appName) {
    $this->appName = $appName;
    return $this;
  }

  /**
   * Get the Application Name.
   *
   */
  public function getAppName() {
    return $this->appName;
  }

  /**
   * Set the Canvas Page.
   *
   */
  public function setCanvasPage($canvasPage) {
    $this->canvasPage = $canvasPage;
    return $this;
  }

  /**
   * Get the Canvas Page url.
   *
   */
  public function getCanvasPage() {
    return $this->canvasPage;
  }
   /**
   * Set the Canvas Url.
   *
   */
  public function setCanvasUrl($canvasUrl) {
    $this->canvasUrl = $canvasUrl;
    return $this;
  }

  /**
   * Get the Canvas url.
   *
   */
  public function getCanvasUrl() {
    return $this->canvasUrl;
  } 
  
 }
 
?>
