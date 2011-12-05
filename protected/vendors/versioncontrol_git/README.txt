$Id: README.txt,v 1.3 2009/10/16 19:48:22 marvil07 Exp $

Git backend for Version Control API -
Provides Git commit information as a pluggable backend.


SHORT DESCRIPTION
-----------------
This module provides an implementation of the Version Control API that makes
it possible to use the Git version control system. It can retrieve commit
information by parsing commit logs.

For the API documentation, have a look at the module file or run doxygen/phpdoc
on it to get a fancier version of the docs.

Any bug reports or feature requests concerning the Git backend in general
should be submitted to the Git backend issue queue:
http://drupal.org/project/issues/versioncontrol_git.

If you know that the functionality is (or should be) provided by the
Version Control API (and not by the Git backend), please submit an issue there:
http://drupal.org/project/issues/versioncontrol.

INSTALL
-------
We need at least versioncontrol api version 6.x-1.0-rc1.
If you've problems with the module, please check that your server is running
git 1.6.2.2 or later. We don't check against earlier versions of git.

AUTHOR
------
Jimmy Berry ("boombatower", http://drupal.org/user/214218) (orginal version
for Drupal 5)
Cornelius Riemenschneider ("CorniI", http://drupal.org/user/136353)

ROADMAP
------
We need to provide git hooks for updating and later for access control.
Also some cli scripts would be very nice for updating without drupal cron
system.
Rewrite versioncontrol_git_update_repository_callback().

CREDITS
-------
A good amount of code in Version Control / Project Node Integration was taken
from the CVS backend module on drupal.org, its author (Jakob Petsovits, among others)
deserve a lot of credits and may also hold copyright for parts of this module.
