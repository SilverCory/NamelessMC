<?php 
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */
 
require_once 'inc/pages.php'; // Load an array containing the pages that exist
require_once 'inc/functions/array_handling.php'; // Custom array functions


/*
 *  Get the directory the user is trying to access
 */ 
$directory = $_SERVER['REQUEST_URI'];
$directories = explode("/", $directory);

/*
 *  Install file check
 */ 

if(file_exists("pages/install.php") && strtolower($directories[1]) !== "install" && strtolower($directories[1]) !== "admin"){
	header("Location: /install");
	die();
}

/*
 *  Define some variables..
 */
$lim = count($directories);
$n = 0;
$exists = false;

/*
 *  Does the directory the user is trying to access exist as a page?
 */

if(strtolower($directories[1]) !== "install"){
	if($lim == 2){
		if(in_array($directories[1], $pages)){ // Is it an element?
			$exists = true;
		} else {
			if(array_key_exists($directories[1], $pages)){ // Is it the key of a subarray?
				$exists = true;
				$key = true;
			}
		}
	} else if($lim > 2){
		if(in_array($directories[1], $pages)){ // Is it an element?
			$exists = true; 
		} else {
			if(array_key_exists($directories[1], $pages)){ // Is it the key of a subarray?
				$exists = true;
				$key = true;
			}
		}
		if($exists === true && !empty($directories[2]) && $directories[1] !== "profile"){ 
			if(in_array($directories[2], $pages[$directories[1]])){ 
				$exists = true;
			} else {
				if(array_key_exists($directories[2], $pages[$directories[1]])){
					$exists = true;
					$key = true;
				} else {
					$exists = false;
				}
			}
		}
		if(isset($directories[2][0]) &&  $directories[2][0] === "?"){ // Get parameters, eg ?action=create
			$exists = true;
			$params = true;
		}
	} else { // Get parameters, eg ?action=create
		if(!empty($directories[3])){
			$params = $directories[3];
		}
	}
}

/*
 *  If the page does not exist, display the 404 error
 */

if(strtolower($directories[1]) !== "install"){
	if($exists !== true){
		require("404.php");
		die();
	}
}

/*
 *  If it does, initialise the page load, and display the page
 */ 

$page = $directories[1];
$path = "";

/*
 *  Include init.php
 */

require_once 'inc/init.php';

if(strtolower($directories[1]) !== "install"){
	/*
	 *  Include the page itself
	 */
 
	if($lim == 2 || empty($directories[2])){
		if(!isset($key)){
			if(!empty($directories[1])){
				require 'pages/' . $directories[1] . '.php';
			} else {
				require 'pages/index.php';
			}
		} else {
			require 'pages/' . $directories[1] . '/index.php';
		}
	} else {
		if(!isset($key)){
			require 'pages/' . $directories[1] . '.php';
		} else {
			if(!isset($params)){
				require 'pages/' . $directories[1] . '/' . $directories[2] . '.php';
			} else {
				require 'pages/' . $directories[1] . '/index.php';
			}
		}
	}
} else {
	require('pages/install.php');
}

?>