/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
};

CKEDITOR.replace( "editor", {
fullPage: true,
allowedContent: true,
width:"100%",
height:"450px"
});
CKEDITOR.config.protectedSource.push(/<\?[\s\S]*?\?>/g);
CKEDITOR.config.protectedSource.push(/<i[^>]*><\/i>/g);
CKEDITOR.config.protectedSource.push(/<a[^>]*>\s*<\/a>/g);
