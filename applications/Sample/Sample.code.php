<?php
require_once(__DIR__ . '/lib/traits/SampleTrait.php');

class SampleApp extends NoorApplication {
	use Sample\lib\traits\SampleTrait;

    public $keypair;
	public $public_key;

    public $formMain;

    /**
	* Constructor of class
	* @todo: Constructor of class
	**/
    function __construct(){
		parent::__construct();
    }
    
    function run()
	{
		//TODO: Run application
        parent::run();

		$this->generateSealKeypair($this->keypair, $this->public_key);
		
        $this->setSplashImage("themes/images/splash.png?v=1");

		$this->addRequiredResource("JS", "resources/js/sample.js", false);
		$this->addRequiredResource("JS", "resources/js/startup.js", false);
		$this->addRequiredResource("CSS", "resources/css/sample.css", false);

        $this->formMain = new NoorForm(new NoorParent($this, $this->formMain));
        $this->formMain->setTop(100);
        $this->formMain->setLeft(100);
        $this->formMain->setWidth(640);
        $this->formMain->setHeight(500);
		$this->formMain->setCaption($this->getLanguage('caption'));
		$this->formMain->setIcon($this->relative_path."/"."themes/images/sample_icon.svg", true);
        $this->formMain->centerScreen();

        $this->assignTemplate('copyright', \Sample\lib\Helper::copyright());
        
		$this->formMain->loadTemplate("themes/templates/index.html");
		$this->formMain->activate(true);

		$noorDisplay = new NoorDisplay();
    	$noorDisplay->push("this.startSample('sample_".$this->id."', '".$this->formMain->id."', '".base64_encode($this->public_key). "');");
    	$noorDisplay->display();    

        /**
		 * Set InstantHandler for formMain
		 * Note: If the InstantEventHandler contain global variable of application, must call this after init that variable
		 */
		$this->formMain->setInstantMoveHandler(new NoorEvent($this, "formMainMove", $this));

		$this->formMain->setInstantResizeHandler(new NoorEvent($this, "formMainResize", $this));
    }

    function formMainMove()
    {

    }

	function formMainResize() {
		$noorDisplay = new NoorDisplay();
    	$noorDisplay->push("this.resizeSample(".$this->formMain->getContentWidth().",".$this->formMain->getContentHeight().");");
    	$noorDisplay->display();
	}
}

?>