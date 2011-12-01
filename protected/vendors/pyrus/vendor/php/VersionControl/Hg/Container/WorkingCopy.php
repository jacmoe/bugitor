<?php
/**
 * Contains definition of the WorkingCopy class
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
require_once 'WorkingCopy/Exception.php';

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
class VersionControl_Hg_Container_WorkingCopy
    extends VersionControl_Hg_Container_Abstract
        implements VersionControl_Hg_Container_Interface
{
    /**
     * Path to a working copy
     *
     * @var string
     */
    protected $path;

    /**
     * Working Copy constructor
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
     * Create a working copy
     *
     * This is usally done by calling `hg update` on a directory which
     * has a .hg repository, but no files, possibly because someone called
     * `hg update null`.
     *
     * @param string $path is the path to the working copy on the filesystem
     *
     * @return VersionControl_Container_WorkingCopy is an instance of a
     *                                              working copy.
     */
    public function create($path)
    {

    }

    /**
     * Delete the working copy
     *
     * Acts on an instantiated object representing a working copy, rather than
     * a file name.
     *
     * @return bool
     */
    public function delete()
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

    }
}
