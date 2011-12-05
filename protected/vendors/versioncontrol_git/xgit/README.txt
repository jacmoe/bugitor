$Id: README.txt,v 1.2 2008/01/27 23:00:53 boombatower Exp $

xgit-* scripts
--------------

This directory contains a set of scripts that can be used via the
hooks provided by Git to enable access control for commit and tag
operations, and to record log messages and other information into the
Drupal database for proper integration with the Version Control API.

These scripts are required if you enable the
"Use external script to insert data" option on the respective
repository edit page, and should be copied to the $GIT_DIR/hooks directory.
Create a directory called 'xgit' inside hooks and place the scripts in it.
Remember to make these files executable (with chmod).

Any bug reports or feature requests concerning the xgit-* scripts or
the Git backend in general should be submitted to the Git backend issue queue:
http://drupal.org/project/issues/versioncontrol_git.

If you know that the functionality is (or should be) provided by the
Version Control API (and not by the Git backend), please submit an issue there:
http://drupal.org/project/issues/versioncontrol.


Each script, and the $GIT_DIR/hooks hook required for its use, are described
below:

--------------------
xgit-config.php
--------------------

  A configuration file that all of the other scripts depend on. The file is
  heavily commented with instructions on each possible configuration setting,
  and also contains a few minor bits of shared code that is used by all other
  scripts. There are a few required settings at the top which must be
  customized to your site before anything else will work.


--------------------
xcvs-loginfo.php
--------------------

  A script to insert all GIT log messages into your Drupal database
  by passing it to the Version Control API. (This is the post-commit hook.)
  To enable this, you must add this line to your $GIT_DIR/hooks/post-update file:

#!/bin/sh
[path_to_.git]/hooks/xgit/xgit-loginfo.php [path_to_.git]/hooks/xgit/xgit-config.php


AUTHOR
------
Jimmy Berry ("boombatower", http://drupal.org/user/214218)
