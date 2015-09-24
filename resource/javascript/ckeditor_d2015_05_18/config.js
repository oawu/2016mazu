/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';
// p[*]{*}(*);span[*]{*}(*);img(left,center,right)[!src,alt,width,height]
	config.allowedContent = true;

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced;image:Link;link:upload;';
	config.DefaultLinkTarget = '_self' ;
};
