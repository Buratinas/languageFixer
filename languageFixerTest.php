<?php
/**
 * #PHPHEADER_OXID_LICENSE_INFORMATION#
 *
 * @link      http://www.oxid-esales.com
 * @package   tests
 * @copyright (c) OXID eSales AG 2003-#OXID_VERSION_YEAR#
 * @version   SVN: $Id: languageFixerTest.php 56456 13.3.22 11.21Z tadas.rimkus $
 */

require_once realpath( "." ).'/unit/OxidTestCase.php';
require_once realpath( "." ).'/unit/test_config.inc.php';
/**
 * Tests language alteration script. Checks include:
 * - checking map can be replaced
 * - checking language translation can be replaced
 *
 */
class languageFixerTest extends OxidTestCase{
    /**
     * Initialize the fixture.
     *
     * @return null
     */
    protected function setUp()
    {
        parent::setUp();

    }

    /**
     * Test script language getter when it was not set. De is set by constructor
     *
     * @dataProvider langProvider
     */
    public function testGetLanguageIsNotSet()
    {
        $oFixer = new oxLanguageFixer();

        $this->assertEquals( 'de', $oFixer->getLanguage() );
    }

    /**
     * Test if script can include language files
     */
    public function testGetLanguage()
    {
        $oFixer = new oxLanguageFixer();
        $oFixer->setLanguage( 'de' );

        $this->assertEquals( 'de', $oFixer->getLanguage() );
    }

    /**
     * Tests Default Theme getter, should get 'azure' assigned by constructor
     */
    public function testGetThemeIsNotSet( ) {
        $oFixer = new oxLanguageFixer();

        $this->assertEquals( 'azure', $oFixer->getTheme() );
    }

    /**
     * Tests Theme getter
     */
    public function testGetThemeIsSet( ) {
        $oFixer = new oxLanguageFixer();
        $oFixer->setTheme( 'basic' );
        $this->assertEquals( 'basic', $oFixer->getTheme() );
    }

    /**
     * Tests Default getMap getter, should get default theme set map
     * Assert that returned array count is more than 0
     *
     */
    public function testGetMapIsNotSet( ) {
        $oFixer = new oxLanguageFixer();

        $this->assertTrue( $oFixer->getMap() > 0 );
    }

    /**
     * Tests LanguageTransformation getter
     */
    public function testGetMapIsSet( ) {
        $oFixer = new oxLanguageFixer();
        $oFixer->setMap( array( 'a' ) );
        $this->assertEquals( array( 'a' ), $oFixer->getMap() );
    }

    /**
     * Tests Default getTemplateFiles getter, should get azure templates by default
     */
    public function testGetTemplateFileStorageNotSet( ) {
        $oFixer = new oxLanguageFixer();

        $this->assertTrue( count($oFixer->getTemplateFiles()) > 0 );
    }

    /**
     * Tests TemplateFileStorage getter
     */
    public function testGetTemplateFileStorageIsSet( ) {
        $oFixer = new oxLanguageFixer();
        $oFixer->setTemplateFiles( array( 'here', 'there' ) );
        $this->assertEquals( array( 'here', 'there' ), $oFixer->getTemplateFiles() );
    }

    /*
     * Test LoadThemeMap method, should return an empty array, because parameters are incorrect
     */
    public function testLoadThemeMapIncorrectParams() {
        $oFixer = new oxLanguageFixer();
        $oFixer->setTheme( 'ThisIsNonExistingTheme' );
        $oFixer->loadMap();
        $aMap = $oFixer->getMap();

        $this->assertEquals( null, $aMap, 'map was loaded even with incorrect parameters' );
    }

    /**
     * Provides map location and result for it
     *
     * @return array
     */
    public function mapFileProvider()
    {
        return array(
            array( 'incorrect_path', null ),
            array( __DIR__ . '/testTheme/de/map.php', array(
                'WRITE_PRODUCT_REVIEW'         => 'WRITE_REVIEW',
                'YOUR_REVIEW'                  => 'REVIEW',
                'PRODUCT_DETAILS'              => 'DETAILS'
            ) ),
        );
    }

    /**
     * Test LoadThemeMap method with incorrect/correct parameters
     *
     * @dataProvider mapFileProvider
     */
    public function testLoadMap( $sFile, $result ) {
        $oFixer = new oxLanguageFixer();

        $oFixer->setMapPath( $sFile );
        $oFixer->loadMap();
        $aMap = $oFixer->getMap();

        $this->assertEquals( $result, $aMap );
    }

    /**
     * Tests loadTemplates method without parameters
     * Default implementation returns azure theme templates
     * Assert that template count is higher than 0
     */
    public function testIncludeTemplatesMethodDefault()
    {
        $oFixer = new oxLanguageFixer();
        $oFixer->loadTemplates();

        $this->assertTrue( count( $oFixer->getTemplateFiles() ) > 0, 'Templates are not included' );
    }

    /**
     * Provides filename and result count
     *
     * @return array
     */
    public function templateLocationProvider()
    {
        return array(
            array( 'NonExistingLocation', 0),
            array( __DIR__ . '/testTheme/tpl', 2 ),
        );
    }
    /**
     * Tests loadTemplates method with incorrect/correct parameters.
     *
     * @dataProvider templateLocationProvider
     */
    public function testIncludeTemplatesMethodWithParams( $sDir, $iResult )
    {
        $oFixer = new oxLanguageFixer();

        $oFixer->setSourcePath( $sDir );
        $oFixer->loadTemplates();

        $this->assertEquals( $iResult ,count( $oFixer->getTemplateFiles() ));
    }

    public function incorrectDataProvider() {
        return array(
            array('falseTheme', 'de'),
            array('azure', 'falseLang'),
            array('falseTheme', 'falseLang'),
        );
    }

    /**
     * Test that search and replace works in templates with incorrect parameters.
     *
     * @dataProvider incorrectDataProvider
     */
    public function testTemplateReplacementIncorrectParams( $sTheme, $sLang )
    {
        $oFixer = new oxLanguageFixer();
        $oFixer->setTheme( $sTheme );
        $oFixer->setLanguage( $sLang );

        $this->assertFalse( $oFixer->fixMappedConstants() );
    }

    /**
     * Test that search and replace works in templates.
     */
    public function testTemplateReplacement()
    {
        $this->_prepareForTest();

        $oFixer = new oxLanguageFixer();
        $sTestTheme = "../../../tests/unit/maintenance/testTheme";
        $oFixer->setTheme( $sTestTheme );
        $oFixer->setLanguage( 'de' );

        $oFixer->fixMappedConstants();

        $testFile = file_get_contents( getcwd() . '/unit/maintenance/testTheme/tpl/review.tpl' );
        $compareFile = file_get_contents( getcwd() . '/unit/maintenance/testTheme/tpl/reviewAfterChange.tpl' );

        $this->assertEquals( $compareFile, $testFile );
    }

    /**
     * Test different approach to changing templates
     */
    public function testAlternativeTemplateReplacement()
    {
        $this->_prepareForTest();

        $oFixer = new oxLanguageFixer();

        $oFixer->setSourcePath( __DIR__ . '/testTheme/tpl' );
        $oFixer->setMapPath( __DIR__ . '/testTheme/de/map.php' );
        $oFixer->fixMappedConstants();

        $testFile = file_get_contents( getcwd() . '/unit/maintenance/testTheme/tpl/review.tpl' );
        $compareFile = file_get_contents( getcwd() . '/unit/maintenance/testTheme/tpl/reviewAfterChange.tpl' );

        $this->assertEquals( $compareFile, $testFile );
    }
    /**
     * Special setup function for testTemplateReplacement, so that it would have template to work with
     */
    protected function _prepareForTest()
    {
        $sMapFile = __DIR__ . "/testTheme/de/map.php";
        $aMap = array();
        if ( file_exists( $sMapFile ) && is_readable( $sMapFile ) ) {
            include $sMapFile;
        }
        $aSearch = preg_replace( '/(\b[A-Z_]+\b)/', "/\b$1\b/", array_values( $aMap ));
        $aReplace = array_keys( $aMap );

        $sFile = __DIR__ . '/testTheme/tpl/review.tpl';
        $testFile = file_get_contents( $sFile );
        file_put_contents( $sFile, preg_replace( $aSearch, $aReplace, $testFile));
    }
}