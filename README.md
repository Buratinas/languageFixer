languageFixer
=============
Warning! The tool provided here is not an official OXID release. Use this at your own risk. Make changes to the script at your own risk. While this will not break your shop it might change your templates, because that's what this tool is meant to do.

This script is meant to remove all maps from given theme's template, and use constants from language file instead. 
It searches throught the template files in the theme, and replaces all usages of it with the key the map is mapped to.
Since maps can link to different translations, the script only checks it with one language, defined in script.
Additionally, some keys were changed, and with the use of languageTransformation.php, one key is replaced with the other, where defined in languageTransformation.php.


Usage.


In order to use this tool you need some basic knowledge in PHP, and knowledge of your shop.
Recommended steps:
1. Backup your shop template files.

2. Move this script directory to the shop's source directory.

3. Edit languageFix.php, in there, change the following variables to your needs:

   a) $sDefaultLanguage - change to the language you want the scrip to be based upon(for explanation, read below)

   b) $sDefaultTheme - theme to work with. Script will look for theme defined here, and change it's template

   c) $blUseLanguageTransformation - should languageTransformation be used.

4. Save the modified file.

5. To execute the script, you can use the command php languageFix.php from within the directory, 
or from your shop's url( "http://yourshop.com/language_fix_script/source/languageFix.php"  ).

Additional information about the script.


If script doesn't run, make sure the path to bootstrap is correct, especially if you're not running 
it from suggested directory.

In 5.1 shop version, mapped constants were removed from the templates, but the support for them is still there.

If you need to use maps, use it, if you want to clean your templates from maps with more ease, use this script.

languageTransformation.php is used to replace on language key with the other, and if you need to, you can also
add your own changes there, if you're using the script.

File takes a map file based on a defined language inside the script, and looks for it's keys in defined theme's templates. When it finds the key, it replaces the current value with the one key links to. i.e:
'ADD_TO_CART' => 'TO_CART' // if the script finds key 'ADD_TO_CART', it replaces the occurence with 'TO_CART'.

The script only looks for occurences with following regular expressions: '/oxmultilang +ident="([A-Z\_0-9]+)"/i' and 
'/"([A-Z\_0-9]+)"\|oxmultilangassign/i', to prevent it from changing non translation keys.




