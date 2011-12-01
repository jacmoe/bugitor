<?php
/**
 * Contains the definition of the base Container class
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
 * Provides common functionality to all container objects, but should not be
 * instantiated itself.
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
abstract class VersionControl_Hg_Container_Abstract
{
    /**
     * Sets the path to the container
     *
     * Setting the path is overridden because each container implementation
     * will validate if the path contains a valid container of the same type
     * as itself
     *
     * @param string $path the path to the container
     *
     * @return null
     */
    abstract public function setPath($path = null);

    /**
     * Returns the path of a Mercurial repository as set by the user.
     *
     * It is not validated before being set as a class member. This allows
     * it to return null when it needs to and lets the programmer check if a
     * repository has been set or not. Exceptions would remove this control.
     *
     * @return string | null
     * @see $path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Prints the container's path.
     *
     * May be expanded to provide other useful data about the repository as
     * a string.
     *
     * @return string
     */
    public function __toString()
    {
        /* necessary because __toString() MUST return a string, but if
         * getPath() is called on a now-permissible null repository path,
         * we will get a PHP error. */
        $path = ( $this->getPath() ) ? $this->getPath() : "";

        return $path;
    }
}
