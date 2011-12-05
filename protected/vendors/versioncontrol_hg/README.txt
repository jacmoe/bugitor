VERSION CONTROL: MERCURIAL
--------------------------

Author: Edward Z. Yang (ezyang, http://drupal.org/user/211688)

Mercurial is a distributed revision control system written in Python.
This module implements Mercurial's API for the Version Control API.

STATUS
------

The following items have been completed:

- Core Mercurial PHP wrapper functions
- Install/uninstall hooks
- Database schema
- Module information hook
- Log to database adapter
- Works with commit log
- Parents are now *really* recorded and reconstituted properly
- Branches are logged
- Refactoring to process one log entry at a time globally
- Tags logging / Tag tracking

TODO EXTRA!
-----------

These todo items aren't strictly necessary to complete the GHOP task
but are things I'd like to do some time.

- Documentation on which order one should implement things for a
  versioncontrol backend (based on this experience)
- Formalize any redundancies, determine which ones should be kept for
  performance and which ones should be scrapped in favor of JOINS.
- Make log parsing use low memory
- Figure out how to import a richer hg repository for testing

TODO FOR CRAZY PEOPLE
---------------------

Enough said.

- Implement xhg hook scripts
- Develop a repository administration interface for creating new
  repositories for new projects, etc.

DESIGN DECISIONS
----------------

The versioncontrol module is reasonably RCS agnostic, but its documentation
is greatly lacking in terms of some of the most important implementation
details and idioms; the fact that CVS is the only reference implementation
available complicates things greatly.

We chose to retain the basic setup of CVS with regards to inserting log
data in the database, and then spitting it out for further processing
(facilities for this are explicitly provided using auto-commit). However,
we redesigned many of the tables (and dropped several) to ensure a cleaner
map to Mercurial's features. The most important thing to remember is that
INSTANTIATING THE IN-MEMORY STRUCTURE FROM THE DATABASE SHOULD TAKE AS
LITTLE CODE AS POSSIBLE. Thus, all data is saved in the form that
versioncontrol demands, and then allowances are made for Mercurial, and
extra fields added for full retention. The parallel, at times competing,
goal is: DUPLICATE INFORMATION AS LITTLE AS POSSIBLE (except when
necessary for performance). Thus, we omitted many fields when they
could be appropriately determined from a foreign key association.

I have carefully reviewed every bit of code from CVS that influenced
design decisions here, and diverged whenever differences between the
two RCS's were great enough that it was merited. One major change that
I'd like to see merged back to CVS is the use of a PHP function wrapper
backend to hide the tangly mess of command line calls, and perhaps
allow PHP to use the native function library.

TAGS
----

Tags in Mercurial are handled in an interesting manner: the .hgtags
file contains any of the recognized tags in a repository at any point
in time. Thus, changes to it must be manually detected and translated
into tag operations. We have chosen to only report adds or deletes; it
is possible that the same revision have multiple tags, so there are
ambiguous cases if we try to figure out renames.

Updating the "current" tag state is done by truncating the tags table
for that repository, and then repopulating it with the contents of
.hgtags.

XHG - Commit Scripts
--------------------

If you want versioncontrol_hg to be instantly updated after you push
changes to the master server, you can set up a changegroup hook in
hgrc to run Drupal's cron:

[hooks]
changegroup = php /path/to/drupal/cron.php

We have decided specifically not to offer our own post-commit script,
as running cron.php directly is simpler. If, for whatever reason, you
don't want cron run whenever a changegroup is pushed to Mercurial, you
can make a quick post-commit script:

<?php
include_once '/path/to/drupal/includes/bootstrap.inc';
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
versioncontrol_hg_cron();
?>

KNOWN ISSUES
------------

Major issues:

- What's up with versioncontrol_hg_get_current_item_tag()?

Minor issues:

- 'file_copies' as per the Mercurial output doesn't ever seem to be
  triggered; the implementation for this case is accordingly patchy,
  especially use of the 'modified' flag. More research is necessary in
  this respect. For now, this should be harmless enough.

Possible upstream issues:

- There is a lot of functionality that feels like it would be better
  placed in versioncontrol itself; namely [versioncontrol_vcs]_get_directory_item(),
  [versioncontrol_vcs]_get_commit_branches, and large portions of
  [versioncontrol_vcs]_commit (which should have a good deal of the
  commit action tomfoolery auto-updated by versioncontrol.)

- There's a lot of docblock duplication, which worries me. We ought to be
  able to say hook_[hookname] and defer the documenting to versioncontrol
  itself.

- Commit log should link to our previous revisions, i.e. (modified: <a href="...">234dab3...</a>)

- Deleted files can have source items too, but what values are appropriate?
  The last changeset on the file before it was deleted? The changeset
  of the deletion of the file in another branch? We currently do both,
  although the latter is slightly iffy, but certainly useful info. Commit
  log currently truncates source items to the first item, which is the original,
  so there are no problems.

- In fact, commit log truncates all source items to one entry. Scandalous!

- Using SHA-1 hashes for revision is really ugly; maybe we should use the
  non-portable revision numbers? (Ideally, compact nodeids would be
  used, but we need a way to calculate them on the fly due to the
  risk of collisions.) This would be an extension to commit log.

- Using email as account name results in commit log displaying the
  email to the world. It is unknown if, when we give versioncontrol
  the ability to lookup uids based on emails, these emails will be
  suppressed from public view.

- versioncontrol_hg_ensure_branch() automatically creates a branch if
  it doesn't exist; it might be helpful (esp. for VCSs that use repository
  wide branches) if there was a hook attached to it.

STRUCTURE
---------

hg/            Generic code for interfacing with Mercurial via command line
  templates/   These are our custom templates to minimize necessary log parsing
tests/         SimpleTest unit tests for hg/
  db/          A multitude of useful *.sql and *.dbquery files for testing
               and resetting the database.
