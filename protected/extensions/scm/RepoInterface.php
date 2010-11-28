<?php

class SCM
{
    /**
     * Returns an instance of the correct scm backend object.
     *
     * @param Project
     * @return Object
     */
    public static function get($project)
    {
        // Get scm type from project conf ; defaults to hg
        // We will need to cache the factory
        $scm = 'hg';
        //$scm = $project->getConf()->getVal('scm', 'git');
        $scms = Yii::app()->config->get('allowed_scm');
        return call_user_func(array($scms[$scm], 'factory'), $project);
    }
    
    /**
     * Return the size of the repository in bytes.
     *
     * @return int Size in byte, -1 if the size cannot be evaluated.
     */
    public function getRepositorySize()
    {
        return -1;
    }

    /** Returns an array keyed by possible branch names.
    * The data associated with the branches is implementation
    * defined.
    * If the SCM does not have a concept of first-class branch
    * objects, this function returns null */
    public function getBranches() {}

    /** Returns an array keyed by possible tag names.
    * The data associated with the tags is implementation
    * defined.
    * If the SCM does not have a concept of first-class tag
    * objects, this function returns null */
    public function getTags() {}

    /** Enumerates the files/dirs that are present in the specified
    * location of the repository that match the specified revision,
    * branch or tag information.  If no revision, branch or tag is
    * specified, then the appropriate default is assumed.
    *
    * The second and third parameters are optional; the second
    * parameter is one of 'rev', 'branch', or 'tag', and if specifed
    * the third parameter must be the corresponding revision, branch
    * or tag identifier.
    *
    * The return value is an array of SCMFile objects present
    * at that location/revision of the repository.
    */
    public function readdir($path, $object = null, $ident = null) {}

    /** Queries information on a specific file in the repository.
    *
    * Parameters are as for readdir() above.
    *
    * This function returns a single SCMFile for the location
    * in question.
    */
    public function file($path, $object = null, $ident = null) {}

    /** Queries history for a particular location in the repo.
    *
    * Parameters are as for readdir() above, except that path can be
    * left unspecified to query the history for the entire repo.
    *
    * The limit parameter limits the number of entries returned; it it is
    * a number, it specifies the number of events, otherwise it is assumed
    * to be a date in the past; only events since that date will be returned.
    *
    * Returns an array of SCMEvent objects.
    */
    public function history($path, $limit = null, $object = null, $ident = null){}

    /** Obtain the diff text representing a change to a file.
    *
    * You may optionally provide one or two revisions as context.
    *
    * If no revisions are passed in, then the change associated
    * with the location will be assumed.
    *
    * If one revision is passed, then the change associated with
    * that event will be assumed.
    *
    * If two revisions are passed, then the difference between
    * the two events will be assumed.
    */
    public function diff($path, $from = null, $to = null) {}

    /** Determine the next and previous revisions for a given
    * changeset.
    *
    * Returns an array: the 0th element is an array of prior revisions,
    * and the 1st element is an array of successor revisions.
    *
    * There will usually be one prior and one successor revision for a
    * given change, but some SCMs will return multiples in the case of
    * merges.
    */
    public function getRelatedChanges($revision) {}

    public function resolveRevision($rev, $object, $ident) {
        if ($rev !== null) {
            return $rev;
        }
        if ($object === null) {
            return null;
        }
        switch ($object) {
        case 'rev':
            $rev = $ident;
            break;
        case 'branch':
            $branches = $this->getBranches();
            $rev = isset($branches[$ident]) ? $branches[$ident] : null;
            break;
        case 'tag':
            $tags = $this->getTags();
            $rev = isset($tags[$ident]) ? $tags[$ident] : null;
            break;
        }
        if ($rev === null) {
            throw new Exception(
            "don't know which revision to use ($rev,$object,$ident)");
        }
        return $rev;
    }
}


class SCMEvent {
  /** Revision or changeset identifier for this particular file */
  public $rev;

  /** commit message associated with this revision */
  public $changelog;

  /** who committed this revision */
  public $changeby;

  /** when this revision was committed */
  public $ctime;

  /** files affected in this event; may be null, but otherwise
   * will be an array of SCMFileEvent */
  public $files;
}

class SCMFileEvent {
  /** Name of affected file */
  public $name;
  /** Change status indicator */
  public $status;

  /** when used in a string context, just return the filename.
   * This simplifies explicit object vs. string interpretation
   * throughout the SCM layer */
  function __toString() {
    return $this->name;
  }
}

class SCMAnnotation {
  /** Revision of changeset identifier for when line was changed */
  public $rev;

  /** who made the change */
  public $changeby;

  /** the content from that line of the file.
   * This is null unless $include_line_content was set to true when annotate()
   * was called */
  public $line;
}

abstract class SCMFile {
  /** reference to the associated SCM object */
  public $repo;

  /** full path to file, with a leading slash (which represents
   * the root of its respective repo */
  public $name;

  /** if true, this file represents a directory */
  public $is_dir = false;

  /** revision */
  public $rev;

  function __construct(SCM $repo, $name, $rev, $is_dir = false)
  {
    $this->repo = $repo;
    $this->name = $name;
    $this->rev = $rev;
    $this->is_dir = $is_dir;
  }

  /** Returns a SCMEvent corresponding to this revision of
   * the file */
  abstract public function getChangeEvent();

  /** Returns a stream representing the contents of the file at
   * this revision */
  abstract public function cat();

  /** Returns an array of SCMAnnotation objects that correspond to
   * each line of file content, annotating when the line was last
   * changed.  The array is keyed by line number, 1-based. */
  abstract public function annotate($include_line_content = false);
}
