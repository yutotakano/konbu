<?php
/*
Plugin Name: Really Simple CAPTCHA
Plugin URI: https://contactform7.com/captcha/
Description: Really Simple CAPTCHA is a CAPTCHA module intended to be called from other plugins. It is originally created for my Contact Form 7 plugin.
Author: Takayuki Miyoshi
Author URI: https://ideasilo.wordpress.com/
Text Domain: really-simple-captcha
Version: 2.0.2
*/

define( 'REALLYSIMPLECAPTCHA_VERSION', '2.0.2' );

class ReallySimpleCaptcha {

	public $chars;
	public $char_length;
	public $fonts;
	public $tmp_dir;
	public $img_size;
	public $bg;
	public $fg;
	public $base;
	public $font_size;
	public $font_char_width;
	public $img_type;
	public $file_mode;
	public $answer_file_mode;

	public function __construct() {
		/* Characters available in images */
		$this->chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

		/* Length of a word in an image */
		$this->char_length = 4;

		/* Array of fonts. Randomly picked up per character */
		$this->fonts = array(
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1c-hiragana-black.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1c-hiragana-bold.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1c-hiragana-heavy.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1c-hiragana-light.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1c-hiragana-medium.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1c-hiragana-regular.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1c-hiragana-thin.ttf',

				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1m-hiragana-bold.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1m-hiragana-light.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1m-hiragana-medium.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1m-hiragana-regular.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1m-hiragana-thin.ttf',

				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1mn-hiragana-bold.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1mn-hiragana-light.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1mn-hiragana-medium.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1mn-hiragana-regular.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1mn-hiragana-thin.ttf',

				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1p-hiragana-black.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1p-hiragana-bold.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1p-hiragana-heavy.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1p-hiragana-light.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1p-hiragana-medium.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1p-hiragana-regular.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-1p-hiragana-thin.ttf',

				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2c-hiragana-black.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2c-hiragana-bold.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2c-hiragana-heavy.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2c-hiragana-light.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2c-hiragana-medium.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2c-hiragana-regular.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2c-hiragana-thin.ttf',

				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2m-hiragana-bold.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2m-hiragana-light.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2m-hiragana-medium.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2m-hiragana-regular.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2m-hiragana-thin.ttf',

				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2p-hiragana-black.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2p-hiragana-bold.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2p-hiragana-heavy.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2p-hiragana-light.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2p-hiragana-medium.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2p-hiragana-regular.ttf',
				dirname( __FILE__ ) . '/mplus-TESTFLIGHT-058/mplus-2p-hiragana-thin.ttf',
		);

		/* Directory temporary keeping CAPTCHA images and corresponding text files */
		$this->tmp_dir = dirname( __FILE__ ) . '/images';

		/* Array of CAPTCHA image size. Width and height */
		$this->img_size = array( 24, 24 );

		/* Background color of CAPTCHA image. RGB color 0-255 */
		$this->bg = array( 255, 255, 255 );

		/* Foreground (character) color of CAPTCHA image. RGB color 0-255 */
		$this->fg = array( 0, 0, 0 );

		/* Coordinates for a text in an image. I don't know the meaning. Just adjust. */
		$this->base = array( 2, 18 );

		/* Font size */
		$this->font_size = 14;

		/* Width of a character */
		$this->font_char_width = 15;

		/* Image type. 'png', 'gif' or 'jpeg' */
		$this->img_type = 'png';

		/* Mode of temporary image files */
		$this->file_mode = 0644;

		/* Mode of temporary answer text files */
		$this->answer_file_mode = 0640;
	}

	/**
	 * Generate and return a random word.
	 *
	 * @return string Random word with $chars characters x $char_length length
	 */
	public function generate_random_word() {
		$word = '';

		for ( $i = 0; $i < $this->char_length; $i++ ) {
			$pos = mt_rand( 0, mb_strlen( $this->chars, 'UTF-8') - 1 );
			$char = mb_substr($this->chars, $pos, 1);
			$word .= $char;
		}

		return $word;
	}

	/**
	 * Generate CAPTCHA image and corresponding answer file.
	 *
	 * @param string $prefix File prefix used for both files
	 * @param string $word Random word generated by generate_random_word()
	 * @return string|bool The file name of the CAPTCHA image. Return false if temp directory is not available.
	 */
	public function generate_image( $prefix, $word ) {

		$dir = $this->tmp_dir . '/';
		$filename = null;

		$im = imagecreatetruecolor(
			$this->img_size[0],
			$this->img_size[1]
		);

		if ( $im ) {
			$bg = imagecolorallocate( $im, $this->bg[0], $this->bg[1], $this->bg[2] );
			$fg = imagecolorallocate( $im, $this->fg[0], $this->fg[1], $this->fg[2] );

			imagefill( $im, 0, 0, $bg );

			$x = $this->base[0] + mt_rand( -2, 2 );

			for ( $i = 0; $i < mb_strlen( $word, 'UTF-8' ); $i++ ) {
				$font = $this->fonts[array_rand( $this->fonts )];
				$font = $this->normalize_path( $font );

				imagettftext( $im, $this->font_size, mt_rand( -12, 12 ), $x,
					$this->base[1] + mt_rand( -2, 2 ), $fg, $font, mb_substr($word, $i, 1) );
				$x += $this->font_char_width;
			}

			switch ( $this->img_type ) {
				case 'jpeg':
					$filename = $prefix;
					$actual_name = $prefix;
					$i = 1;
					do {
						$actual_name = $filename . substr('0000' . $i, -5);
						$i++;
					} while(file_exists($dir . $actual_name . '.jpeg'));
					$name = $actual_name . '.jpeg';
					$file = $this->normalize_path( $dir . $name );
					imagejpeg( $im, $file );
					break;
				case 'gif':
					$filename = $prefix;
					$actual_name = $prefix;
					$i = 1;
					do {
						$actual_name = $filename . substr('0000' . $i, -5);
						$i++;
					}while(file_exists($dir . $actual_name . '.gif'));
					$name = $actual_name . '.gif';
					$file = $this->normalize_path( $dir . $name );
					imagegif( $im, $file );
					break;
				case 'png':
				default:
					$filename = $prefix;
					$actual_name = $prefix;
					$i = 1;
					do {
						$actual_name = $filename . substr('0000' . $i, -5);
						$i++;
					} while(file_exists($dir . $actual_name . '.png'));
					$name = $actual_name . '.png';
					$file = $this->normalize_path( $dir . $name );
					imagepng( $im, $file );
			}

			imagedestroy( $im );
			@chmod( $file, $this->file_mode );
		}

		// $this->generate_answer_file( $prefix, $word );

		return $filename;
	}

	/**
	 * Generate answer file corresponding to CAPTCHA image.
	 *
	 * @param string $prefix File prefix used for answer file
	 * @param string $word Random word generated by generate_random_word()
	 */
	public function generate_answer_file( $prefix, $word ) {
		$dir = $this->tmp_dir . '/';
		$answer_file = $dir . $prefix . '.txt';
		$answer_file = $this->normalize_path( $answer_file );

		if ( $fh = fopen( $answer_file, 'w' ) ) {
			$word = strtoupper( $word );
			$salt = $this->randomPassword( 64 );
			$hash = hash_hmac( 'md5', $word, $salt );
			$code = $salt . '|' . $hash;
			fwrite( $fh, $code );
			fclose( $fh );
		}

		@chmod( $answer_file, $this->answer_file_mode );
	}

	function randomPassword($length) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); //remember to declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
    for ($i = 0; $i < $length; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
	}

	/**
	 * Check a response against the code kept in the temporary file.
	 *
	 * @param string $prefix File prefix used for both files
	 * @param string $response CAPTCHA response
	 * @return bool Return true if the two match, otherwise return false.
	 */
	public function check( $prefix, $response ) {
		if ( 0 == mb_strlen( $prefix, 'UTF-8' ) ) {
			return false;
		}

		$response = str_replace( array( " ", "\t" ), '', $response );
		$response = strtoupper( $response );

		$dir = $this->tmp_dir . '/';
		$filename = $prefix . '.txt';
		$file = $this->normalize_path( $dir . $filename );

		if ( is_readable( $file )
		and $code = file_get_contents( $file ) ) {
			$code = explode( '|', $code, 2 );
			$salt = $code[0];
			$hash = $code[1];

			if ( hash_hmac( 'md5', $response, $salt ) === $hash ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Remove temporary files with given prefix.
	 *
	 * @param string $prefix File prefix
	 */
	public function remove( $prefix ) {
		$dir = $this->tmp_dir . '/';
		$suffixes = array( '.jpeg', '.gif', '.png', '.php', '.txt' );

		foreach ( $suffixes as $suffix ) {
			$filename = $prefix . $suffix;
			$file = $this->normalize_path( $dir . $filename );

			if ( is_file( $file ) ) {
				@unlink( $file );
			}
		}
	}

	/**
	 * Clean up dead files older than given length of time.
	 *
	 * @param int $minutes Consider older files than this time as dead files
	 * @return int|bool The number of removed files. Return false if error occurred.
	 */
	public function cleanup( $minutes = 60, $max = 100 ) {
		$dir = $this->tmp_dir . '/';
		$dir = $this->normalize_path( $dir );

		if ( ! is_dir( $dir )
		or ! is_readable( $dir ) ) {
			return false;
		}

		$is_win = ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) );

		if ( ! ( $is_win ? win_is_writable( $dir ) : is_writable( $dir ) ) ) {
			return false;
		}

		$count = 0;

		if ( $handle = opendir( $dir ) ) {
			while ( false !== ( $filename = readdir( $handle ) ) ) {
				if ( ! preg_match( '/^[0-9]+\.(php|txt|png|gif|jpeg)$/', $filename ) ) {
					continue;
				}

				$file = $this->normalize_path( $dir . $filename );

				if ( ! file_exists( $file )
				or ! $stat = stat( $file ) ) {
					continue;
				}

				if ( ( $stat['mtime'] + $minutes * 60 ) < time() ) {
					if ( ! @unlink( $file ) ) {
						@chmod( $file, 0644 );
						@unlink( $file );
					}

					$count += 1;
				}

				if ( $max <= $count ) {
					break;
				}
			}

			closedir( $handle );
		}

		return $count;
	}

	/**
	 * Make a temporary directory and generate .htaccess file in it.
	 *
	 * @return bool True on successful create, false on failure.
	 */
	public function make_tmp_dir() {
		$dir = $this->tmp_dir . '/';
		$dir = $this->normalize_path( $dir );

		if ( ! wp_mkdir_p( $dir ) ) {
			return false;
		}

		$htaccess_file = $this->normalize_path( $dir . '.htaccess' );

		if ( file_exists( $htaccess_file ) ) {
			return true;
		}

		if ( $handle = fopen( $htaccess_file, 'w' ) ) {
			fwrite( $handle, 'Order deny,allow' . "\n" );
			fwrite( $handle, 'Deny from all' . "\n" );
			fwrite( $handle, '<Files ~ "^[0-9A-Za-z]+\\.(jpeg|gif|png)$">' . "\n" );
			fwrite( $handle, '    Allow from all' . "\n" );
			fwrite( $handle, '</Files>' . "\n" );
			fclose( $handle );
		}

		return true;
	}

	/**
	 * Normalize a filesystem path.
	 *
	 * This should be replaced by wp_normalize_path when the plugin's
	 * minimum requirement becomes WordPress 3.9 or higher.
	 *
	 * @param string $path Path to normalize.
	 * @return string Normalized path.
	 */
	private function normalize_path( $path ) {
		$path = str_replace( '\\', '/', $path );
		$path = preg_replace( '|/+|', '/', $path );
		return $path;
	}
}
