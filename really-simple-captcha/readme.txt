=== Konbu Really Simple CAPTCHA ===

Modified version of the SiteGuard Really Simple CAPTCHA PHP file included in the SiteGuard Wordpress Plugin, which itself is a modified version of the Really Simple CAPTCHA PHP file produced by Takayuki Miyoshi, distributed under the GNU General Public License. 

To generate the dataset of 18,000 images to the "images" directory (which has to exist), run `php run.php` in the terminal. The images will have the filename structure of "あ001.png" where the first character is the CAPTCHA solution, and the last 3 digits are the 1-indexed alternative image for that combination of characters. I.E., the generated files may look like the following:

"あ001.png"
"あ002.png"
"あ003.png"
"あ004.png"
"い001.png"
...