<?php
/**
 * Contains definition of the Repository class
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Repository
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */

/**
 * Composes a revision object for use in commands
 *
 * Rewrite some commands to accept a VersionControl_Hg_Container_Repository_Revision
 *
 * Usage:
 * <code>
 * $hg = new VersionControl_Hg('/path/to/repo');
 * $revision = $hg->get()->revision('22')->run();
 * $revision = $hg->revision('22')->run();
 * But, do what?
 *
 * $revision = $hg->revision('22');
 * $log = $hg->log($revision)->run();
 * Which is equivelent to
 * $log = $hg->log()->revision('22')->run();
 * or
 * $revision = $hg->revision('3bcf453face889')->get()->run();
 * </code>
 *
 * PHP version 5
 *
 * @category   VersionControl
 * @package    Hg
 * @subpackage Repository
 * @author     Michael Gatto <mgatto@lisantra.com>
 * @copyright  2011 Lisantra Technologies, LLC
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link       http://pear.php.net/package/VersionControl_Hg
 */
class VersionControl_Hg_Container_Repository_Revision
{
    /**
     * Revision identifier is an integer
     * @todo rename as NUMERICAL_ID
     */
    const IDENTIFIER_NUMBER = 'number';

    /**
     * Revision identifier is a hash
     * @todo rename as HASH_ID
     */
    const IDENTIFIER_HASH = 'hash';

    /**
     * The revision id
     *
     * @var string
     */
    protected $revision = null;

    /**
     * Repository constructor which currently does nothing.
     *
     * @param string $revision is the revision id
     *
     * @see $revision
     *
     * @return void
     */
    public  function __construct($revision)
    {
        $type = $this->findType($revision);

        $this->setRevision($revision, $type);
    }

    /**
     * Return the revisions' identifier in an array with the key being the
     * identifier's type.
     *
     * @return mixed
     */
    protected function getRevision()
    {
        return $this->revision;
    }

    /**
     * Set the revision as well as its identifier
     *
     * @param string $revision the revision id from Mercurial
     * @param string $type     is one of: 'hash' or 'integer'
     *
     * @return null
     */
    protected function setRevision($revision, $type)
    {
        $this->revision = array($type => $revision);
    }

    /**
     * Validate that the identifier actually matches its type
     *
     * @param string $id is the revision's id.
     *
     * @return boolean
     */
    protected function findType($id)
    {
        switch (true) {
            case ( is_int($id) ):
                $type = self::IDENTIFIER_NUMBER;
                break;
            case ( preg_match('/^[0-9a-f]{40}$/', $id) ):
                $type = self::IDENTIFIER_HASH;
                break;
            default:
                throw new Exception($message, $code, $previous);
                break;
        }

        return $type;
    }

    /**
     * Dumps the revision of whichever identifier type
     *
     * @return string
     */
    public function __toString()
    {
        $revision = $this->getRevision();

        return ( is_null($revision) ) ? "" : "{$revision}" ;
    }

}
