<?php

class Frontend_PicController extends Zend_Controller_Action
{
	/**
	 * Config-Objekt mit der Bild-Definition des gewählten Typs
	 * @var Zend_Config_Ini
	 */
	protected $config;
	/**
	 * Dateiname des Bildes (ohne Endung)
	 * @var string
	 */
	protected $fileBasename;
	/**
	 * Gewünschte Bildgröße
	 * @var string
	 */
	protected $size;
	/**
	 * Datei-Endung
	 * @var string
	 */
	protected $extension;
	/**
	 * Pfad zur Original-Datei 
	 * @var string
	 */
	protected $origFile;
	/**
	 * Basis-Pfad der Frontend-Datei (alles bis einschließlich des Basisnamens), wo dann die
	 * Bildgrößen-spezifischen Identifier noch drangehängt werden
	 * @var string
	 */
	protected $newFileBase;
	
	public function init()
	{
		// Layout und View brauchen wir hier nicht
		$this->_helper->getHelper('viewRenderer')->setNoRender();
		$this->_helper->getHelper('layout')->disableLayout();
		
		// Konfigurations-File laden
		try {
			$iniFile = new Zend_Config_Ini(APPLICATION_PATH . '/config/imageSizes.ini');
		} catch (Exception $e) {
			$this->getResponse()->setHttpResponseCode(404);
			$request->setDispatched(true);
			return;
		}
		
		$request = $this->getRequest();
		
		$this->fileBasename = $request->getParam('fileBasename');
		$this->size         = $request->getParam('size');
		$this->extension    = $request->getParam('extension');
		
		$this->config = $iniFile->{$this->size};
		if (!$this->config) {
			$this->getResponse()->setHttpResponseCode(404);
			$request->setDispatched(true);
			return;
		}

		$this->origFile = FILES_PATH . '/' . $this->fileBasename . '.' . $this->extension;
		if (!file_exists($this->origFile)) {
			$this->getResponse()->setHttpResponseCode(404);
			$request->setDispatched(true);
			return;
		}
		
		$this->newFileBase  = CACHE_PATH . '/files/' . $this->fileBasename;
	}

	/**
	 * Standard-Route: Bild-Name und Bildgröße
	 */
	public function standardAction()
	{
		$targetFile = $this->newFileBase . '.' . $this->size . '.' . $this->extension;
		
		$this->createPic($targetFile);
	}

	/**
	 * Startet die Bild-Erzeugung
	 * 
	 * @param string $targetFile
	 * @param array $params zusätzliche Parameter
	 */
	protected function createPic($targetFile, $params = array())
	{
		$destinationDir = dirname($targetFile);
		if (!is_dir($destinationDir)) {
			mkdir($destinationDir, true);
			chmod($destinationDir, 0775);
		}
		if (!is_writable($destinationDir)){
			error_log('Image dir "'.$destinationDir.'" not writable');
		}		
		if (!is_readable($this->origFile)){
			error_log('Source image "'.$this->origFile.'" not readable');
		}

		$picCreator = new Core_Service_Imagemagick();
		$picCreator->setOriginalFile($this->origFile);
		
		// Wenn das Original angefragt ist, einfach das Original zurückgeben und wir haben fertig.
		if ($this->size == 'original') {
			$picCreator->output($this->origFile, $this->extension);
			return;
		}
		
		// Service konfigurieren und Bild berechnen
		$picCreator->setNewFile($targetFile);
		$picCreator->setWidth($this->config->width);
		$picCreator->setHeight($this->config->height);
		$picCreator->setFixedSize($this->config->fixedSize);
		$picCreator->setUnsharp($this->config->unsharp);
		$picCreator->setExtendToSize($this->config->extendToSize);
		$picCreator->setBackground($this->config->background);
		$picCreator->setEnlarge($this->config->enlarge);
		$picCreator->convert();
		
		// Sofort die neue Datei ausgeben
		$picCreator->output($targetFile, $this->extension);
	}
	
}
