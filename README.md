languageFixer
=============

Warning! The tool provided here is not an official OXID release. Use this at your own risk. While this will 
not break your shop it might change your templates, because that's what this tool is meant to do.

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

Explanation of the script.

