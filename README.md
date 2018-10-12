Zatvarachki BG - Can Sealers 
========================

Zatvarachki BG is a simple website that has products (can sealers and cans), receipts and 
    contact info.

Originally created in 2017 it is now being reworked to support two languages, some new features
and to put better code practices in practice.

The main features
--------------

Zatvarachki BG has those features:

  * Visitors can view products, switch between languages.

  * Users can register, ask questions which will notify admins through email.

  * Administrators can edit website info directly from the home page or add/edit products.

What has changed from the old website:

  * **Code** - The code is rewritten using services, binding models instead of 100 lines long
  methods
  
  * **Db calls** - Some information is now stored in files such as contact info and about us text
  in both languages to avoid unnecessary calls to the db for every request.
  Now files are being cached instead.

  * **Design and js** - Almost all views are reworked, sidebar is added for some of them.
  Moved from bootstrap 3 to bootstrap 4.1. Javascript files are segregated and imported correctly.
  onclick="" is replaced with event listeners.
  
  * **Twig** - Templates are more segregated and views are reused where needed.
 
Want to run the app?
---------------------
	
Steps: 
	
	* Git pull
	
	* run: php (7.1.9 at least) composer.phar install
	
	* set up proper smtp settings
	
	* doctrine:schema:update --force
	
	* When you open the website, the db will be completely empty. 
	
	* Register the first account and that will trigger the first run service, which will 
	        create the initial roles, categories, languages and such..
	
	* Ready to go!