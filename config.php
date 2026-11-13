<?php
//  Always redirect http to https or not. If this value is 1, the client site is always run on https. Otherwise, this value is 0.
define("XY_SITE_HTTPS_ALWAYS", 0);

//  Debug mode. 1 if it's enabled, 0 if it's disabled
define("XY_DEBUG", 1);

//  Max number of turn which xy client and system will play together 
//   xy client -- turn 0 (turn === false) --> system -- return 'turn-xx' (if necessary) --> xy client -- turn 'turn-xx' --> system -->.....
define("XY_MAX_NUMBER_OF_TURNS", 10);

// The xy client acts as proxy between client and xy system. Set to 0 (zero) if we want to let xy system process client requests directly
define("XY_PROXY", 1);

// When url path info is '/' or empty, ruote the url to default path info (if this value is set). 
// Default value is '/' or '', when set to '/' or '', the system use the original url path info.
//  This helps hiding the path info when you do not want user visit to XY home page
//  Ex: this value is '/xy-proxy/A1' and domain is 'echoi.org', when you visit https://echoi.org you will get same result as you visit https://echoi.org/index.php/xy-proxy/A1
//      or https://echoi.org/xy-proxy/A1 (when using url rewrite)
define("XY_DEFAULT_PATH_INFO", '');

// Prefix will be pre-appended to path info
// Default value is ''
// When url path info is '/' or empty, this value is ignored, the path info swith to XY_DEFAULT_PATH_INFO (if set)
//  Ex: this value is '/xy-proxy/A1', when you visit https://echoi.org/wsdl, the path info will be '/xy-proxy/A1/wsdl'
define("XY_PATH_INFO_PREFIX", '');

//  The site title. Leave blank if using system config
define("XY_SITE_TITLE", "Echoi Sample App");
//  The site icon. Leave blank if using system config
define("XY_SITE_ICON", "applications/Sample/themes/images/sample_icon.svg");
//  The site allows selecting text (set to 1) or not (set to 0)
define("XY_SITE_SELECTION", 1);
//  The site background image url. Leave blank if using system config. 'NONE' for empty background
//    url sample is "applications/[Application Name]/resources/img/background/site.jpg"
define("XY_SITE_BACKGROUND", "applications/Sample/themes/images/sample-bg.jpg");
//  The site background position ('left bottom', 'left center',...). Leave blank if using system config
define("XY_SITE_BACKGROUND_POSITION", "right bottom");
//  The site background size ('auto', 'cover'). Leave blank if using system config
define("XY_SITE_BACKGROUND_SIZE", "cover");
//  The first application run when site initialized
define("XY_SITE_FIRST_APPLICATION", "");
//  The first application's params run when site initialized
define("XY_SITE_FIRST_APPLICATION_PARAMS", "");
//  The default desktop for xy client
//   'virtual': Like windows desktop - running multiple windows
//   'widget': Like webpage desktop - single page
//   'modal': Overlay desktop, using to show system message, system tasks, ...
//   'auto': let system auto select desktop mode. if browser only support widget, the desktop set to 'widget', otherwise it's set to 'virtual'
define("XY_SITE_DESKTOP", "virtual");

//  The system uri information
define("XY_SYSTEM_DOMAIN", "echoi.org"); 
define("XY_SYSTEM_PATH", "");
define("XY_SYSTEM_HTTP_PORT", "");
define("XY_SYSTEM_HTTPS_PORT", "");

//For PHP on Windows IIS, if run as a Virtual Application of site (ex "Default Websites"), set this value.
// Ex: http://localhost/xydoms, this value will be "xydoms"
//Otherwise, set this value empty.
define("WIN_VIRTUAL_APPLICATION", "");

//  The system module will be loaded to xy client for using independently (not depends on the system)
//  You can load multiple modules using comma (,)
//      DB: Module for dataabse connection (mysql, sql server, oracle,...) 
//      CRYPTO: Module for encrypt
//      LOG: Module for logging
//      FILEDB: Module for save data to file
//      QRCODE: Module for reading QR Code
//      VIETQR: Module for generating vietQR code
//      PDF: Module for create pdf from html
//      WEBSOCKET: Module for websocket server
define("XY_SYSTEM_MODULES", "DB,CRYPTO,LOG,FILEDB,QRCODE,VIETQR,PDF,WEBSOCKET,EXPRESSIONLANGUAGE");

// Component for Javascript. Those components will be loaded to xy client
//      PONYFILL: for javascript polyfill
//      STREAMSAVER: for stream saver
define("XY_SYSTEM_JS_COMPONENTS", "PONYFILL,STREAMSAVER");

// Component for CSS. Those components will be loaded to xy client
define("XY_SYSTEM_CSS_COMPONENTS", "");

// Syn system files when xy is running. Set to 0 (zero) if keep local system files (without syn)
define("XY_SYSTEMFILES_SYN", 1);

// Ignore error when syning and keep local system files
define("XY_SYSTEMFILES_SYN_IGNORE_ERROR", 1);

// Where page (browser tab only, not multiple tabs or window) is reload, session time will be restore as when page is first load.
// This helps to keep running application instances, not create new application instance when reload
define("XY_SAVE_SESSION_TIME_WHEN_PAGE_RELOAD", 1);

// Setting XY_SAME_SESSIONTIME to 1 means XY Client use the same session time for all session lifetime.
// For XY_SAME_SESSIONTIME = 1, when user open multiple browser tabs, the application run-on tabs keep same application id,
// so application can get same processing for user but sometime same processing is not good, 
// user may be want to run same application name with different application id in different tabs.
// When running an application with INSTANT mode (run_by = 'INSTANT') at xy_start(), set XY_SAME_SESSIONTIME to 1 to keep same application id when user refreshs the tab.
define("XY_SAME_SESSIONTIME", 1);
?>