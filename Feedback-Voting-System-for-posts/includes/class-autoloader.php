<?php
/**
 * Autoloader Class File
 *
 * This file contains Autoloader class which can manage and handle using classes and
 * files by including them when they are needed.
 *
 */

namespace FeedbackSystem\Includes;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Autoloader
 *
 * Autoloader class can manage and handle using classes and files in whole of
 * your plugin by including them when they are needed.
 *
 */
class Autoloader {

	/**
	 * Autoloader constructor.
	 * This constructor calls spl_autoload_register method with autoload method
	 * inside Autoloader class.
	 *
	 * @access public
	 */
	public function __construct() {
		spl_autoload_register( array( $this, 'autoload' ) );
	}

	/**
	 * Method to handle Undefined Classes and include them when script is running.
	 *
	 * @param string $class_name The name of class that passed throw spl_auto_register.
	 */
	public function autoload( $class_name ) {
		// If the specified $class_name does not include our namespace, duck out.
		if ( false === strpos( $class_name, 'FeedbackSystem' ) ) {
			return;
		}

		// Split the class name into an array to read the namespace and class.
		$file_parts = explode( '\\', $class_name );

		// Do a reverse loop through $file_parts to build the path to the file.
		$namespace = '';
		$file_name = '';
		for ( $i = count( $file_parts ) - 1; $i > 0; $i -- ) {
			// Read the current component of the file part.
			$current = strtolower( $file_parts[ $i ] );
			$current = str_ireplace( '_', '-', $current );

			// If we're at the first entry, then we're at the filename.
			if ( count( $file_parts ) - 1 === $i ) {
				$file_name = "class-$current.php";
			} else {
				$namespace = '/' . $current . $namespace;
			}
		}

		// Now build a path to the file using mapping to the file location.
		$file_path  = trailingslashit( dirname( dirname( __FILE__ ) ) . $namespace );
		$file_path .= $file_name;

		// If the file exists in the specified path, then include it.
		if ( file_exists( $file_path ) ) {
			include_once $file_path;
		} else {
			wp_die(
				esc_html( "The file attempting to be loaded at $file_path does not exist." )
			);
		}
	}
}

new Autoloader();