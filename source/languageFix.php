<?php
/**
 * #PHPHEADER_OXID_LICENSE_INFORMATION#
 *
 * @link      http://www.oxid-esales.com
 * @package   tests
 * @copyright (c) OXID eSales AG 2003-#OXID_VERSION_YEAR#
 * @version   SVN: $Id: languageFix.php 56456 13.3.22 16.02Z tadas.rimkus $
 */
require_once dirname(__FILE__) . "/../../source/bootstrap.php";
require_once dirname(__FILE__) . "/oxLanguageFixer.php";

/**
 * Script configuration.
 */
// set default language. Based on this language maps will be replaced
$sDefaultLanguage = 'de';
// default theme to work with
$sDefaultTheme = 'azure';
// use languageTransformation.php?
$blUseLanguageTransformation = true;
/**
 * End of script configuration
 */



$langFix = new oxLanguageFixer( );
// not well displayed in browser
$langFix->intro();

$langFix->setLanguage( $sDefaultLanguage );
$langFix->setTheme( $sDefaultTheme );
if ( !$langFix->fixMappedConstants( '.' ) ) {
    echo 'Error accessing templates at: ' . $langFix->getSourcePath() . ' using map: ' . $langFix->getMapPath() . PHP_EOL;
}

// optional
if ( $blUseLanguageTransformation ) {
    $langFix->setSourcePath( oxConfig::getInstance()->getAppDir() . 'views/' . $sDefaultTheme . '/tpl' );
    $langFix->setMapPath(  __DIR__ . '/languageTransformation.php' );
    if ( !$langFix->fixMappedConstants( '.' ) ) {
        echo 'Error accessing templates at: ' . $langFix->getSourcePath() . ' using map: ' . $langFix->getMapPath() . PHP_EOL;
    }
}


