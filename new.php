<?php
  require_once('core.php');
	$oFilePath = new filepath($_REQUEST);
  $sFullFilePath = $oFilePath->getFullFile();
  
  if (file_exists($sFullFilePath))
  {
    $sText = json_encode(array('exists' => 
                                array( 'file'  => $oFilePath->getFile(),
                                       'name'  => $oFilePath->getName()
                                       )
                                ));
    echo $sText;
    exit;
  }
  
  $oTemplatePath = clone $oFilePath;
  $oTemplatePath->setName('template');
  $oTemplatePath->setPath('');
  $sTemplate = file_get_contents($oTemplatePath->getFullFile());
  if($oTemplatePath->getType() == 'set')
  {
    $sTemplate = str_replace('<'.$oTemplatePath->getType().' name="template">', '<set name="'.$oFilePath->getName().'">', $sTemplate);
  }
    
  file_put_contents($sFullFilePath, $sTemplate);
  
  if(CONST_SVN_AUTO && defined('SVN_REVISION_HEAD'))
  {
    svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_USERNAME, $_SERVER['PHP_AUTH_USER']);
    svn_auth_set_parameter(SVN_AUTH_PARAM_DEFAULT_PASSWORD, $_SERVER['PHP_AUTH_PW']);
    $aCommitLog = svn_add(realpath($sFullFilePath));
    if($aCommitLog === false)
    {
       throw(new exception('Could Not Add File'));
    }
    $aCommitLog = svn_commit('Intial auto commit from MooSong user '.$_SERVER['PHP_AUTH_USER'], array(realpath($sFullFilePath)));
    if($aCommitLog === false)
    {
       throw(new exception('Could Not Commit File'));
    }
  }
  
  //chown  ( $sFullPath  , 'martyn'  );
  $sText = json_encode(array('newset' =>
                              array( 'file'  => $oFilePath->getFile(),
                                     'name'  => $oFilePath->getName()
                                     )
                              ));
  echo $sText;
  exit;

