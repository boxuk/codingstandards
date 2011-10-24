<?php
/**
 * Package file for creating PEAR packages. This file defines how the PEAR
 * package should be constructed.
 *
 * usage: php package.php VERSION
 *
 * @author    Box UK <opensource@boxuk.com>
 * @copyright Copyright (c) 2011, Box UK
 * @license   http://opensource.org/licenses/mit-license.php MIT License and http://www.gnu.org/licenses/gpl.ht$
 * @link      http://github.com/boxuk/describr
 * @since     1.0.3
 */

require_once( 'PEAR/PackageFileManager2.php' );
require_once( 'PEAR/PackageFileManager/File.php' );

@list( $IGNORE, $version, $channel ) = $_SERVER['argv'];

if ( !$version ) {
    echo "usage: php package.php VERSION\n";
    exit( 1 );
}
if( !$channel ) {
    $channel = 'pear.boxuk.net';
}
define( 'BOXUK_PEAR_CHANNEL', $channel );

$aFilesToIgnore = array();

$packagexml = new PEAR_PackageFileManager2;
$packagexml->addPackageDepWithChannel('package', 'Autoload', BOXUK_PEAR_CHANNEL, '1.0.1');

$packagexml->setOptions(array(
    'packagedirectory' => 'PHP',
    'baseinstalldir' => '/PHP/CodeSniffer/Standards/',
));

$packagexml->setPackage( 'codingstandards' );
$packagexml->setSummary( 'PHP Coding Standards' );
$packagexml->setDescription( 'Box UK\'s PHP coding standards, to be used with PHPCodesniffer' );
$packagexml->setChannel( BOXUK_PEAR_CHANNEL );
$packagexml->setAPIVersion( $version );
$packagexml->setReleaseVersion( $version );
$packagexml->setReleaseStability( 'stable' );
$packagexml->setAPIStability( 'stable' );
$packagexml->setNotes( "-" );
$packagexml->setPackageType( 'php' );

// prevent ruleset.xml from being put in the data dir status
$packagexml->addRole('xml', 'php');

$packagexml->setPhpDep( '5.3.0' );
$packagexml->setPearinstallerDep( '1.9.0' );
$packagexml->addMaintainer( 'lead', 'boxuk', 'boxuk', 'opensource@boxuk.com' );
$packagexml->setLicense( 'MIT License', 'http://www.opensource.org/licenses/mit-license.php' );
$packagexml->generateContents();
$packagexml->writePackageFile();
