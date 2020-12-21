=== Konbu Really Simple CAPTCHA ===

Modified version of the SiteGuard Really Simple CAPTCHA PHP file included in the SiteGuard Wordpress Plugin, which itself is a modified version of the Really Simple CAPTCHA PHP file produced by Takayuki Miyoshi, distributed under the GNU General Public License. 

The target server uses this plugin to generate 4-character images. In order to keep our dataset as similar to the target server as possible, we will also generate 4-character images, which we will subsequently split into 4 pieces labelling each with their associated hiragana.

E.G., in a single for loop (out of 10,000), a random four character sequence あいうえ and its image is generated in memory (using the exact same algorithm as the target server). This (72x24) image is then separated into blocks of 15x24 for each character, and labelled as あXXXXX.png, いXXXXX.png, うXXXXX.png, えXXXXX.png, where XXXXX is a 1-indexed number for the variation of that image.

How much of each character is generated depends on the algorithm of the original `generate_random_word()` function, but it is fairy uniform.

These images are then used as training/cross-validation/testing data in Konbu.

To actually run this script (which will generate 40,000 images into the "images" directory, which has to exist), run `php run.php` in the terminal. 