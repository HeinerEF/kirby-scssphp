<?php // site\plugins\heineref_scssphp\snippets\scss.php

/**
 * SCSS Snippet
 * @author    Bart van de Biezen <bart@bartvandebiezen.com>
 * @co-author HeinerEF
 * @link      https://github.com/HeinerEF/kirby-scssphp
 * @return    CSS and HTML
 * @version   2.0.1
 * @update    2025-10-18 by HeinerEF (some minor changes)
 * @update    2025-09-27 by HeinerEF (use 'scssphp library' v >= 2.0)
 * @update    2025-09-26 by HeinerEF (use 'composer' to install ScssPhp)
 * @update    2025-09-13 by HeinerEF (use 'composer' to update ScssPhp)
 */

use ScssPhp\ScssPhp\Compiler;
use ScssPhp\ScssPhp\Version;

$scssphpVersion = Version::VERSION;

$version ='"SCSSPHP plugin" (v2.0.1) and "ScssPhp" (v'.$scssphpVersion.')';

if(!isset($scss)) $scss = ''; // no "scss" template given

$IsDeveloper = FALSE; // default => CSS is minified
$Echo4Developer = FALSE; // default
$EchoSourceMap = FALSE; // default
$Echo4DeveloperExendend = FALSE; // default
$AlwaysShowScss = FALSE; // or: ($_SERVER['REMOTE_ADDR'] === '127.0.0.1'); // User is local
if($user = $kirby->user()) {
  $IsDeveloper = (in_array($user->role()->id(), option('HeinerEF.scssphp.scssDeveloperRoles', array('admin')))); // only these roles use the NOT minified CSS.
};

# $Echo4Developer = $IsDeveloper;         // activate this line only for debugging of 'site/snippets/scss.php'.
# $EchoSourceMap = $IsDeveloper;          // activate this line only for debugging of SourceMap to file.
# $Echo4DeveloperExendend = $IsDeveloper; // activate this ONLY FOR THE DEVELOPMENT of this file !!!

// Returns a system root = new attempt by HeinerEF
$root = kirby()->root();

if(isset($scss) AND !($scss === '')) {
  $template = $scss; // "template" given as snippet-option, e.g. "snippet('scss', array('scss' => 'demo'));" for file 'demo.scss'
  if($Echo4Developer) {
    echo '<!-- scss file "' . $template . '.scss" given via snippet array entry "scss" -->' . "\n  ";
  };
};
if(!isset($template)) {
  $template = $page->template();
  if($Echo4Developer) {
    echo '<!-- scss file "' . $template . '.scss" selected by snippet "scss" for template = "' . $template . '.php" -->' . "\n  ";
  };
};

// Set file paths. Used for checking and updating CSS file for current template.
$SCSS            = $root . '/assets/scss/' . $template . '.scss';
$minCSS          = $root . '/assets/css/'  . $template . '.min.css';
$devCSS          = $root . '/assets/css/'  . $template . '.dev.css';
$minCSSKirbyPath = 'assets/css/' . $template . '.min.css';
$devCSSKirbyPath = 'assets/css/' . $template . '.dev.css';

// Set default SCSS if there is no SCSS for current template. If template is default, skip check.
if(($template <> 'default') AND !file_exists($SCSS)) {
  $SCSS            = $root . '/assets/scss/default.scss';
  $minCSS          = $root . '/assets/css/default.min.css';
  $devCSS          = $root . '/assets/css/default.dev.css';
  $minCSSKirbyPath = 'assets/css/default.min.css';
  $devCSSKirbyPath = 'assets/css/default.dev.css';
  if($Echo4Developer) {
    echo '<!-- file "/assets/scss/' . $template . '.scss" NOT found => use "default.scss"! Ok! -->' . "\n  ";
  };
} else {
  if($Echo4Developer) {
    echo '<!-- file "/assets/scss/' . $template . '.scss" found => use "' . $template . '.scss", NOT "default.scss" -->' . "\n  ";
  };
};

// If the CSS file doesn't exist create it so we can write to it
if (!file_exists($devCSS)) {
  if (!file_exists($root . '/assets/css/')) {
    mkdir($root . '/assets/css/', 0755, true);
  }
  touch($minCSS,  mktime(0, 0, 0, date("m"), date("d"),  date("Y")-10));
  touch($devCSS,  mktime(0, 0, 0, date("m"), date("d"),  date("Y")-10));
};

if(!file_exists($SCSS)) {
  die($version . ': ERROR: File "' . $SCSS . '" not found! => run aborted');
};

// Get file modification time. Used for checking if update is required.
$SCSSFileTime = filemtime($SCSS);
if(file_exists($minCSS)) {
  $CSSFileTime  = filemtime($minCSS);
} else {
  $CSSFileTime  = 1; // => update!
};

// For when the plugin should check if partials are changed. If any partial is newer than the main SCSS file, the main SCSS file will be 'touched'.
// This will trigger the compiler later on, on this server and also an another environment when synced.
if(option('HeinerEF.scssphp.scssNestedCheck') AND file_exists($minCSS)) { // css is build automatically, if the css file does not exists. Then drop this check.
  $touched = false; // default
  $SCSSDirectory = $root . '/assets/scss/';
  $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($SCSSDirectory, FilesystemIterator::SKIP_DOTS));
  foreach ($files as $file) {
    if(pathinfo($file, PATHINFO_EXTENSION) == "scss") {
      if((filemtime($file) > $SCSSFileTime) AND (   (strlen(pathinfo($file, PATHINFO_DIRNAME)) > strlen(pathinfo($SCSS, PATHINFO_DIRNAME)))
                                                  OR (pathinfo($file, PATHINFO_FILENAME)[0] == '_')
                                                 )) {
        if($Echo4Developer) {
          echo '<!-- touch is needed!     "' . substr ($file, strlen (pathinfo($SCSS, PATHINFO_DIRNAME))) . '": age difference = ' . (filemtime($file) - $SCSSFileTime) . " ! -->\n  ";
        };
        touch ($SCSS);
        clearstatcache();
        $touched = true;
        break;
      } else {
        if($Echo4Developer) {
          echo '<!-- touch is NOT needed! "' . substr ($file, strlen ($root)) . '": age difference = ' . (filemtime($file) - $SCSSFileTime) . r( ((strlen(pathinfo($file, PATHINFO_DIRNAME)) > strlen(pathinfo($SCSS, PATHINFO_DIRNAME))) OR (pathinfo($file, PATHINFO_FILENAME)[0] == '_')), '', ', ignored by reason of the path and filename') . " -->\n  ";
        };
      };
    } else {
        if($Echo4DeveloperExendend) {
          echo '<!-- Info: "' . substr ($file, strlen ($root)) . ' is NOT "*.scss" !' . " -->\n  ";
        };

    };
  };
  if(!$touched) {
    $SitePluginsDirectory   = $root . '/site/plugins/';   // since Kirby 2.3.0+: https://getkirby.com/docs/developer-guide/plugins/assets
    $AssetsPluginsDirectory = $root . '/assets/plugins/'; // 'Customized plugin assets' have to be in: /assets/plugins/{{ pluginName }}
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($SitePluginsDirectory, FilesystemIterator::SKIP_DOTS));
    if($Echo4Developer) {
      echo '<!-- 2nd part, now we search in "/site/plugins/": -->' . "\n  ";
      echo '<!-- Info: "' . $root . '" Length = ' . 0*strlen (pathinfo($root, PATHINFO_DIRNAME)) . ' -->' . "\n  ";
    };
    foreach ($files as $file) {
      if(pathinfo($file, PATHINFO_EXTENSION) == "scss") {
        $file2 = $AssetsPluginsDirectory . substr ($file, strlen ($SitePluginsDirectory)); // need not exist, we test this
        if(file_exists($file2)) {
          if($Echo4Developer) {
            echo '<!-- customized scss file exists => original file "' . substr ($file, strlen ($root)) . '" is ignored!' . " -->\n  ";
          };
          $file = $file2;
        };
        if(filemtime($file) > filemtime($SCSS)) {
          if($Echo4Developer) {
            echo '<!-- touch is needed!     "' . substr ($file, strlen ($root)) . '": age difference = ' . (filemtime($file) - $SCSSFileTime) . " ! -->\n  ";
          };
          $touched = true;
          touch ($SCSS);
          clearstatcache();
          break;
        } else {
          if($Echo4Developer) {
            echo '<!-- touch is NOT needed! "' . substr ($file, strlen ($root)) . '": age difference = ' . (filemtime($file) - $SCSSFileTime)  . " -->\n  ";
          };
        };
      } else {
          if($Echo4DeveloperExendend) {
            echo '<!-- Info: "' . substr ($file, strlen ($root)) . '" is NOT "*.scss" !' . " -->\n  ";
          };

      };
    };
  };
};

// Get current file modification times. Used for checking if update is required and as version number for caching.
$SCSSFileTime = filemtime($SCSS);

// Update CSS when needed.
if((!file_exists($minCSS)) or ($SCSSFileTime > $CSSFileTime) or ($IsDeveloper AND option('HeinerEF.scssphp.scssDevelopment', false))) {

  e (($IsDeveloper AND option('HeinerEF.scssphp.scssDevelopment')), '<!-- CSS is build EVERY time ($IsDeveloper AND ("scssDevelopment" == TRUE)) !!!) -->' . "\n  ");
  if($Echo4Developer) {
    echo '<!-- update of CSS has to be done: age difference = ' . ($SCSSFileTime - $CSSFileTime) . " > 0! -->\n  ";
  }

  // time stamp
  $stamp  = '/* Last update ' . date("Y-m-d H:i:s P") . ' by ' . $version . ' */' . "\n";
  $stamp2 = '/* Last update ' . date("Y-m-d H:i:s P") . ' by scssphp */' . "\n";

  $parser = new Compiler();

  // Setting compression provided by library.
  $parser->setOutputStyle(\ScssPhp\ScssPhp\OutputStyle::EXPANDED);   // for easier debugging of the CSS, if CSS is NOT minified.

  // Setting relative @import paths.
  $importPath = $root . '/assets/scss';
  $parser->addImportPath($importPath);

  $sourceMapWriteTo  = $devCSS . '.map';            // absolute path to a file to write the map to
  $sourceMapBasepath = str_replace('\\', '/', $root) . '/assets/scss'; // difference between file & url locations; removed from ALL source files in .map
  $sourceMapURL      = basename($sourceMapWriteTo); // has the same path as the ccs-file
  $sourceMapFilename = basename($devCSS);           // url location of .css file // an optional name of the generated code that this source map is associated with.
  $sourceRoot        = '../scss/';                  // basename

  $parser->setSourceMap(Compiler::SOURCE_MAP_FILE); // = output .map file

  $parser->setSourceMapOptions(array (
      'sourceMapBasepath' => $sourceMapBasepath,   // base path for filename normalization
      'sourceMapURL'      => $sourceMapURL,        // url of the map
      'sourceMapFilename' => $sourceMapFilename,   // an optional name of the generated code that this source map is associated with.
      'sourceRoot'        => $sourceRoot,          // an optional source root, useful for relocating source files on a server or removing repeated values
                                                   // in the 'sources' entry. This value is prepended to the individual entries in the 'source' field.
  ));

  if($EchoSourceMap) {
    echo '<!-- sourceMapWriteTo  = sourceMapBasepath + sourceMapURL' . " -->\n  ";
    echo '<!-- sourceMapWriteTo  = "' . $sourceMapWriteTo  . '" // absolute path to a file to write the map to"' . " -->\n  ";
    echo '<!-- sourceMapBasepath = "' . $sourceMapBasepath . '" // base path for filename normalization, removed from ALL source files in .map' . " -->\n  ";
    echo '<!-- sourceMapURL      = "' . $sourceMapURL . '" // url of the map' . " -->\n  ";
    echo '<!-- sourceMapFilename = "' . $sourceMapFilename . '"     // an optional name of the generated code that this source map is associated with. // url location of .css file"' . " -->\n  ";
    echo '<!-- sourceRoot        = "' . $sourceRoot        . '" // an optional source root, useful for relocating source files on a server or removing' . "\n  "
       . '                         repeated values in the "sources" entry. This value is prepended by the browser to the individual entries in the "source" field.' . " -->\n  ";
  };

  $result = $parser->compileFile($SCSS);

  file_put_contents($sourceMapWriteTo, $result->getSourceMap());

  // Compile content in buffer.
  $buffer = $result->getCss();

  // Update NOT minified CSS file.
  file_put_contents($devCSS, $stamp . $buffer);

  $minifypath = realpath(__DIR__ . '/../minify.php');

  // Minify the CSS even further.
  require_once $minifypath;
  $buffer = minifyCSS($buffer);

  // Update minified CSS file.
  file_put_contents($minCSS, $stamp2 . $buffer);

  // Get current file modification time. Used as version number for caching.
  $CSSFileTime  = filemtime($minCSS);

  if($Echo4Developer) {
    echo '<!-- Update of CSS has been done:  age difference = ' . ($SCSSFileTime - $CSSFileTime) . " < 0! -->\n  ";
  };

  if($IsDeveloper AND !(stripos ($buffer, '@import') === FALSE)) {
    echo "\n  "
     .   '<!-- HINT:    Not less than one line like "@import " is found in the compiled css file and loaded using "@import " at EVERY runtime!' . "\n"
     . '       Pointer: Rename all included css files to "*.scss", may be without any other change in these files!' . "\n"
     . '                Another possibility is, that that files are missing!' . "\n"
     . '                You can find the import lines in line 2 (and may be the directly following lines) of "' . substr ($devCSS, strlen ($root)). '"!'
     . " -->\n\n  ";
  };
} else {
  if($Echo4Developer) {
    echo '<!-- NO update of CSS "' . substr ($devCSS, strlen ($root)) . '" needed! -->' . "\n  ";
  }
}

if(!isset($media)) $media = ''; // no "media" given as snippet-option, e.g. "snippet('scss', array('scss' => 'print.scss', 'media' => 'print'));" for file 'print.scss' with medium 'print'

?>
<link rel="stylesheet" href="<?php echo url(r(($IsDeveloper OR $AlwaysShowScss), $devCSSKirbyPath, $minCSSKirbyPath)); ?>?version=<?php echo $CSSFileTime; ?>" type="text/css"<?php e(($media <> ''), ' media="' . $media . '"'); ?>><?php e(($IsDeveloper), '<!-- css-version = ' . date("Y-m-d H:i:s P", $CSSFileTime) . ' by ' . $version . ' -->'); echo  "\n"; ?>
