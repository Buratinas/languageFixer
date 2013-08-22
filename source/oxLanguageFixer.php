<?php
/**
 * #PHPHEADER_OXID_LICENSE_INFORMATION#
 *
 * @link      http://www.oxid-esales.com
 * @package   tests
 * @copyright (c) OXID eSales AG 2003-#OXID_VERSION_YEAR#
 * @version   SVN: $Id: languageFixer.php 56456 13.3.22 11.05Z tadas.rimkus $
 */

/**
 * Class dedicated to removing maps. Can use default map to replace multi language constants
 * in templates. Can set custom map and custom template location to change templates using custom map.
 *
 * Class oxLanguageFixer
 */
class oxLanguageFixer {
    /**
     * @var array holds language map
     */
    protected $_map = null;

    /**
     * @var string theme name
     */
    protected $_sTheme = null;

    /**
     * @var array template files list
     */
    protected $_aTemplateFiles = null;

    /**
     * @var string language abbreviation
     */
    protected $_sLanguage = null;

    /**
     * @var string map path
     */
    protected $_mapPath = null;

    /**
     * @var string source files path
     */
    protected $_sourcePath = null;

    /**
     * Sets theme name
     *
     * @param $sTheme string name of the given them
     */
    public function setTheme( $sTheme )
    {
        $this->_sTheme = $sTheme;
    }

    /**
     * Get theme name
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->_sTheme;
    }
    /**
     * Set language
     *
     * @param $sLang string language abbreviation
     */
    public function setLanguage( $sLang )
    {
        $this->_sLanguage = $sLang;
    }

    /**
     * Get language abbreviation
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->_sLanguage;
    }

    /**
     * Set template files array
     *
     * @param $aData array template array
     */
    public function setTemplateFiles( $aData )
    {
        $this->_aTemplateFiles = $aData;
    }

    /**
     * Get template files array
     *
     * @return array
     */
    public function getTemplateFiles()
    {
        if ( null === $this->_aTemplateFiles ) {
            $this->loadTemplates();
        }
        return $this->_aTemplateFiles;
    }

    /**
     * Sets the map array
     *
     * @param $aData array map array
     */
    public function setMap( $aData )
    {
        $this->_map = $aData;
    }

    /**
     * Gets map array
     *
     * @return array
     */
    public function getMap()
    {
        if ( null === $this->_map ) {
            $this->loadMap();
        }
        return $this->_map;
    }

    /**
     * Sets map path
     *
     * @param $sPath string path to map
     */
    public function setMapPath( $sPath )
    {
        $this->_mapPath = $sPath;
        $this->setMap( null );
    }

    /**
     * Gets map path
     *
     * @return string
     */
    public function getMapPath()
    {
        if ( null === $this->_mapPath ) {
            $sTheme = $this->getTheme();
            $sLang = $this->getLanguage();

            $this->setMapPath( oxConfig::getInstance()->getAppDir() . "views/$sTheme/$sLang/map.php" );
        }
        return $this->_mapPath;
    }

    /**
     * Sets source path
     *
     * @param $sPath string path to source files
     */
    public function setSourcePath( $sPath )
    {
        $this->_sourcePath = $sPath;
        $this->setTemplateFiles( null );
    }

    /**
     * Gets source path
     *
     * @return string
     */
    public function getSourcePath()
    {
        if ( null === $this->_sourcePath ) {
            $sTheme = $this->getTheme();
            $this->setSourcePath( oxConfig::getInstance()->getAppDir()."views/$sTheme/tpl" );
        }
        return $this->_sourcePath;
    }

    /**
     * Includes template files
     */
    public function loadTemplates()
    {
        $sPath = $this->getSourcePath();
        $this->setTemplateFiles( $this->_getTemplates( $sPath ) );
    }

    /**
     * Gets template files from given directory
     *
     * @param $sDir string starting directory
     *
     * @return array
     */
    protected function _getTemplates( $sDir ) {
        $aTemplates = array();
        if ( is_dir($sDir) ) {
            $oDirectoryIterator = new RecursiveDirectoryIterator($sDir);
            $aDirs = new RecursiveIteratorIterator( $oDirectoryIterator, RecursiveIteratorIterator::SELF_FIRST);

            foreach ($aDirs as $oTplDir) {
                if ( $oTplDir->isDir() ) {
                    $aTemplates = array_merge($aTemplates, glob($oTplDir->getRealpath() . DIRECTORY_SEPARATOR . "*.tpl"));
                }
            }
            $aTemplates = array_merge($aTemplates, glob($sDir . DIRECTORY_SEPARATOR . "*.tpl"));
        }
        return $aTemplates;
    }

    /**
     * Find all multi language constants in template file.
     * Returns constants that have been found.
     *
     * @param $sFile file to look in
     *
     * @return array
     */
    protected function _getTemplateConstants( $sFile )
    {
        $sTpl =  file_get_contents( $sFile );
        $sReg = '/oxmultilang +ident="([A-Z\_0-9]+)"/i';
        preg_match_all( $sReg, $sTpl, $aMatches );

        $aConstants = $aMatches[1];

        $sReg = '/"([A-Z\_0-9]+)"\|oxmultilangassign/i';
        preg_match_all( $sReg, $sTpl, $aMatches );

        $aConstants = array_merge( $aConstants, $aMatches[1] );

        return $aConstants;
    }

     /**
     * Replace mapped constant with it's generic translation key in template
     *
     * @param $sMappedConstant string constant to replace to
     * @param $sConstant string constant to find
     * @param $sFile string file name
     *
     * @return int|boolean
     */
    protected function _replaceInTemplate ( $sMappedConstant, $sConstant, $sFile )
    {
        $sTpl =  file_get_contents( $sFile );
        $sReg = '/oxmultilang +ident="(' . $sConstant . ')"/i';
        $sReplace = 'oxmultilang ident="' . $sMappedConstant . '"';
        $sTpl = preg_replace( $sReg, $sReplace, $sTpl );

        $sReg = '/"(' . $sConstant . ')"\|oxmultilangassign/i';
        $sReplace = '"' . $sMappedConstant . '"|oxmultilangassign';
        $sTpl = preg_replace( $sReg, $sReplace, $sTpl );

        return file_put_contents( $sFile, $sTpl );
    }

    /**
     * Searches in templates and files, looking for mapped constants. Replaces when finds them.
     *
     * @param string $sFileCompleteMarker custom marker when file checking is complete
     *
     * @return bool
     */
    public function fixMappedConstants( $sFileCompleteMarker = '' )
    {
        $aMap = $this->getMap();
        if ( 0 == count( $aMap ) ) {
            return false;
        }
        $aTemplates = $this->getTemplateFiles();
        if ( 0 == count( $aTemplates ) ) {
            return false;
        }
        foreach ( $aTemplates as $sFile ) {
            $aConstants = $this->_getTemplateConstants( $sFile );
            foreach ($aConstants as $sConstant) {
                if ( array_key_exists( $sConstant, $aMap ) ) {
                    if ( !$this->_replaceInTemplate( $aMap[$sConstant], $sConstant, $sFile ))
                        echo 'replacing failed in file' . $sFile . PHP_EOL;
                }
            }
            echo $sFileCompleteMarker;
        }
        return true;
    }

    /**
     * Loads custom map into array
     */
    public function loadMap( )
    {
        $sFileLocation = $this->getMapPath();

        if ( is_file( $sFileLocation ) && ( is_readable( $sFileLocation ) ) ) {
            include $sFileLocation;
            $this->setMap( $aMap );
        }
    }

    /**
     * Print intro text
     */
    public function intro()
    {
        echo '-----------------------------<< LANGUAGE SCRIPT >> ------------------------------' . PHP_EOL;
        echo '- With last patch there were a lot of changes to language translations and maps.-' . PHP_EOL;
        echo '- A lot of templates and files were changed in order to make it more flexible   -' . PHP_EOL;
        echo '- and understandable.                                                           -' . PHP_EOL ;
        echo '- This script should help reduce the work involved in changing all the files    -' . PHP_EOL ;
        echo '- that might be affected.                                                       -' . PHP_EOL ;
        echo '- It finds maps used in templates, and changes them to generic translations.    -' . PHP_EOL ;
        echo '-                                                                               -' . PHP_EOL ;
        echo '---------------------------------------------------------------------------------' . PHP_EOL ;
        echo '------------------------------<< Removing maps >> -------------------------------' . PHP_EOL;
        echo '- In this step all maps will be removed, and replaced with keys they map to     -' . PHP_EOL;
        echo '- All maps should be mapped to same keys, if they are not, you need to do that  -' . PHP_EOL;
        echo '- manually. In this step all maps will be removed based on default language     -' . PHP_EOL;
        echo '- provided in the configuration(found at the top of the script)                 -' . PHP_EOL ;
    }

    /**
     * Default constructor, so that script can run after initialization
     * without the need to setup theme and language
     */
    public function __construct()
    {
        $this->setLanguage( 'de' );
        $this->setTheme( 'azure' );
    }
}