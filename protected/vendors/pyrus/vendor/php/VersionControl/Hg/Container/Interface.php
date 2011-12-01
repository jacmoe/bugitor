<?php
/**
 * Contains an interface definition
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
 * Interface for Hg containers, such as repositories, working copies and bundles.
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
interface VersionControl_Hg_Container_Interface
{
    /**
     * Create a container
     *
     * @param string $path is the path to the container on the filesystem
     *
     * @return string
     */
    public function create($path);

    /**
     * Delete a container
     *
     * @return bool
     */
    public function delete();
}
