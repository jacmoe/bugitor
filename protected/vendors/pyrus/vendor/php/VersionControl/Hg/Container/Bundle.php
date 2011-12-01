<?php
/**
 * Contains definition of the Bundle class
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Container
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */

/**
 * Provides the abstraction for containers
 */
require_once 'Abstract.php';

/**
 * Provides the container interface
 */
require_once 'Interface.php';

/**
 * Provides the container exception
 */
require_once 'Repository/Exception.php';

/**
 * A bundle of Mercurial revisions/changesets
 *
 * This class is used by the 'bundle' command and represents the actual bundle
 * created. If its already created, then the command will return an instance
 * of this class.
 *
 * @TODO should it be a singleton, too?? - No, because we can operate on many
 * bundles at once!
 * @TODO what semantics shall we use for getting an existing bundle on disk?
 * @TODO what exactly would we want to do with a bundle after getting it?
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Container
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_Container_Bundle
    extends VersionControl_Hg_Container_Abstract
        implements VersionControl_Hg_Container_Interface
{
    /**
     * Path to a local Mercurial repository
     *
     * @var string
     */
    protected $path;

    /**
     * The collection/range of revisions used in this bundle
     *
     * @var mixed
     */
    protected $revisions;

    /**
     * The type of compression: none, bzip2 (default), gzip.
     *
     * @var string
     */
    protected $compression_type;

    /**
     * Compression types currently implemented in Mercurial for bundles
     *
     * @var mixed
     */
    protected $valid_compression_types = array(
        'none', 'gzip', 'bzip2'
    );

    /**
     * Bundle constructor
     *
     * @param object $hg   is the root object and
     *                     will always be an instance of VersionControl_Hg
     * @param string $path is the full path to the user defined executable
     *
     * @return void
     */
    public function __construct(VersionControl_Hg $hg, $path)
    {

    }

    /**
     * Create a bundle of revisions
     *
     * @param string $path is the path to the bundle on the filesystem
     *
     * @return VersionControl_Container_Bundle is an instance of a bundle.
     */
    public function create($path)
    {

    }

    /**
     * Delete the bundle
     *
     * Acts on an instantiated object representing a bundle, rather than
     * a file name.
     *
     * @return bool
     */
    public function delete()
    {

    }

    /**
     * Add a revision to the bundle
     *
     * @param VersionControl_Hg_Container_Bundle_Revision $revision is the
     *                                                              revision to
     *                                                              add to the
     *                                                              bundle.
     *
     * @return VersionControl_Hg_Container_Bundle for fluent API
     */
    public function addRevision($revision)
    {

    }

    /**
     * Remove a single revision from the bundle
     *
     * @param string $revision the revision identifier used
     *
     * @return boolean
     */
    public function removeRevision($revision)
    {

    }

    /**
     * Set the revisions en masse which are contained in this bundle
     *
     * @param unknown_type $revisions is the collection/range of revisions
     *                                the bundle is instantiated with.
     *
     * @return null
     */
    protected function setRevisions($revisions)
    {

    }

    /**
     * Sets the path of a changegroup bundle after validating it as an actual
     * bundle
     *
     * @param string $path is the path to the hg executable
     *
     * @see self::$path
     *
     * @return VersionControl_Hg to enable method chaining
     */
    public function setPath($path = null)
    {
        /* not passing in a path is OK, especially since the programmer may
         * want to call create() */
        if ( is_null($path) ) {
            return;
        }

        if (is_array($path)) {
            $path = $path[0];
        }

        //is it even a real path?
        if ( ! realpath($path)) {
            throw new VersionControl_Hg_Container_Bundle_Exception(
                VersionControl_Hg_Container_Bundle_Exception::DOES_NOT_EXIST
            );
        }

        /*
         * Let's not guess that the user wants to create a repo if none exists;
         * Throw and exception and let them decide what to do next.
         * Maybe they just gave the wrong path.
         *
         * Line breaks are transmitted to CLI apps; concat the strings to
         * ignore them in output.
         */
        if ( ! $this->isBundle($path)) {
            throw new VersionControl_Hg_Container_Bundle_Exception(
                VersionControl_Hg_Container_Bundle_Exception::NO_REPOSITORY
            );
        }

        $this->path = $path;

        return $this; //for chainable methods.
    }

    /**
    * Checks if $this is in fact a valid
    *
    * @param string $path is the full repository path.
    *
    * @return boolean
    */
    protected function isBundle($path)
    {
        $is_bundle = false;

        //DIRECTORY_SEPARATOR
        $bundle = $path;

        /* What more can we do? Verify compression integrity?? */
        if (is_file($bundle) ) {
            $is_bundle = true;
        }

        return $is_bundle;
    }

    /**
     * Mutator for the compression type
     *
     * @param string $type is the type of compression used to make the bundle
     *
     * @throws VersionControl_Hg_Container_Bundle_Exception
     *
     * @return null
     */
    protected function setCompressionType($type)
    {
        if (! in_array($type, $this->valid_compression_types) ) {
            throw new VersionControl_Hg_Container_Bundle_Exception(
                VersionControl_Hg_Container_Bundle_Exception::INVALID_TYPE
            );
        }

        $this->compression_type = $type;
    }

    /**
     * Accessor for the compression type
     *
     * @see
     *
     * @return string
     */
    protected function getCompressionType()
    {
        return $this->compression_type;
    }
}
