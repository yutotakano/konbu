=== Konbu Really Simple CAPTCHA ===

Modified version of the SiteGuard Really Simple CAPTCHA PHP file included in the SiteGuard Wordpress Plugin, which itself is a modified version of the Really Simple CAPTCHA PHP file produced by Takayuki Miyoshi, distributed under the GNU General Public License. 

To generate the dataset of 50,000 images to the "images" directory (which has to exist), run `php run.php` in the terminal. The images will have the filename structure of "あいうえ00001.png" where the first four characters are the CAPTCHA solution, and the last 5 digits are the 1-indexed alternative image for that combination of characters. I.E., the generates files may look like the following:

"ああああ00001.png"
"ああああ00002.png"
"ああああ00003.png"
"あああい00001.png"
"ああせた00001.png"
...

Although most of the time unnecessary, the 5 digits are there for the rare case that all 50,000 images end up with the same character combination.