languageFixer
=============
Warning! The tool provided here is not an official OXID release. Use this at your own risk. Make changes to the script at your own risk. While this will not break your shop it might change your templates, because that's what this tool is meant to do.

This script is meant to remove all maps from the given theme's template, and uses constants from the language file instead. 
It searches through the template files in the theme, and replaces all keys that are mapped via map.php by a proper language key.
Please note that this script can only replace language keys that are used by either English or German map.php.
Additionally, some keys were changed for logical or orthographical reasons. Please use languageTransformation.php to replace the old keys by the new ones. Please feel free to adapt this file by your own needs.


##Usage.


In order to use this tool you need some basic knowledge in PHP, and knowledge of your OXID eShop.
Recommended steps:

1. Backup your shop template files.
1. Move this script directory (source/) to the shop's root directory.
1. Edit languageFix.php by changing the following variables to your needs:
 1. $sDefaultLanguage - change to the language locale you want the script to be based on (for explanation, read below).
 1. $sDefaultTheme - theme to work with. The script will look for theme defined here, and change it's template files.
 1. $blUseLanguageTransformation - use languageTransformation.php?
1. Save the modified file.
1. To execute the script, you can use the command "php languageFix.php" from within the directory (if php compiler is reachable via command line), or fire up your browser at http://yourshop.com/language_fix_script/source/languageFix.php.

##Additional information about the script.

* If the script doesn't run, make sure the path to the shop's bootstrap.php is correct in languageFix.php, especially if you're not running it from the suggested directory.
* In OXID eShop version 5.1, the mapped constants were removed from the templates, but the mapping feature still exists.
* "languageTransformation.php" is used to replace one language key with another. If you want, you can also add your own changes there, if you're using the script.
* File takes a map file based on the language defined inside the script, and looks for its keys in defined theme's templates. When it finds the key, it replaces the current value with the one key the map.php linked to. i.e:
'ADD_TO_CART' => 'TO_CART' // if the script finds key 'ADD_TO_CART', it replaces the occurence with 'TO_CART'.
* The script only looks for occurences with the following regular expressions: '/oxmultilang +ident="([A-Z\_0-9]+)"/i' and 
'/"([A-Z\_0-9]+)"\|oxmultilangassign/i'.
