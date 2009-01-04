<?php

  require_once('config.php');
  
  class filepath
  {
    private $sOpenSongRoot = CONST_OpenSongData;
    private $sType = '';
    private $sName = '';
    private $sPath = '';
    
    function __construct($aFileInfo)
    {
      if (isset($aFileInfo['type'])) $this->setType($aFileInfo['type']);
      if (isset($aFileInfo['file'])) $this->setFile($aFileInfo['file']);
      if (isset($aFileInfo['name'])) $this->setName($aFileInfo['name']);
      if (isset($aFileInfo['path'])) $this->setPath($aFileInfo['path']);
    }
    
    function setType($sValue)
    {
      $this->sType = $sValue;
    }
    
    function setFile($sValue)
    {
      $this->setName(basename($sValue));
      $this->setPath(dirname($sValue));
    }
    
    function setFullFile($sValue)
    {
      $this->setFile(str_replace($this->sOpenSongRoot.$this->getDataFolder(), '', $sValue));
    }
    
    function setName($sValue)
    {
      $this->sName = $sValue;
    }
    
    function setPath($sValue)
    {
      if ($sValue == '.')
      {
        $this->sPath = '';
      }
      else
      {
        $this->sPath = $sValue.'/';
      }
    }
    
   
    
    function getPath()
    {
      return $this->sPath;
    }
    
    function getFullDataFolder()
    {
      $sDir = $this->getDataFolder();
      return $this->sOpenSongRoot.$sDir;
    }
    
    function getDataFolder()
    {
      $sDir = '';
      switch ($this->sType)
      {
        case 'song':
          $sDir = 'Songs/';
          break;
        case 'set':
          $sDir = 'Sets/';
          break;
      }
      return $sDir;
    }
    
    function getType()
    {
      return $this->sType;
    }
    
    function getFullPath()
    {
      $sDir = $this->getDataFolder();
      return $this->sOpenSongRoot.$sDir.$this->sPath;
    }
    
    function getName()
    {
      return $this->sName;
    }
    
    function getBaseName()
    {
      return  str_replace(array('\'','"','!','/'), '', $this->sName);
    }
    
    function getFile()
    {
      return $this->getPath().$this->getBaseName();
    }
    
    function getFullFile()
    {
      return $this->getFullPath().$this->getBaseName();
    }
  }
