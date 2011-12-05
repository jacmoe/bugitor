$Id: README.txt,v 1.1 2007/12/01 21:13:33 jpetso Exp $

xcvs-* scripts
--------------

This directory contains a set of scripts that can be used via the
hooks provided by CVS to enable access control for commit and tag
operations, and to record log messages and other information into the
Drupal database for proper integration with the Version Control API.

These scripts are required if you enable the
"Use external script to insert data" option on the respective
repository edit page, and should be copied to the CVSROOT directory.
Remember to make these files executable (with chmod).

In order to have these scripts working correctly, you need to have
the "new info message format" for this repository enabled.
If you haven't yet enabled this setting, you can do so by adding
this line into your CVSROOT/config file:

UseNewInfoFmtStrings=yes


Originally written by Kjartan Mannes (http://drupal.org/user/2).
Major re-write to include access control and other changes
by Derek Wright (http://drupal.org/user/46549).
Another re-write for Version Control API integration
by Jakob Petsovits (http://drupal.org/user/56020).

Any bug reports or feature requests concerning the xcvs-* scripts or
the CVS backend in general should be submitted to the CVS backend issue queue:
http://drupal.org/project/issues/versioncontrol_cvs

If you know that the functionality is (or should be) provided by the
Version Control API (and not by the CVS backend), please submit an issue there:
http://drupal.org/project/issues/versioncontrol


Each script, and the CVSROOT hook required for its use, are described
below:

--------------------
xcvs-config.php
--------------------

  A configuration file that all of the other scripts depend on. The file is
  heavily commented with instructions on each possible configuration setting,
  and also contains a few minor bits of shared code that is used by all other
  scripts. There are a few required settings at the top which must be
  customized to your site before anything else will work.


--------------------
xcvs-commitinfo.php
--------------------

  A script to enforce access control for CVS commit operations and
  to prepare data for xcvs-loginfo.php. (This is the pre-commit hook.)
  To enable it, you must add this line to your CVSROOT/commitinfo file:

ALL $CVSROOT/CVSROOT/xcvs-commitinfo.php $CVSROOT/CVSROOT/xcvs-config.php $USER /%p %{s}


--------------------
xcvs-loginfo.php
--------------------

  A script to insert all CVS log messages into your Drupal database
  by passing it to the Version Control API. (This is the post-commit hook.)
  To enable this, you must add this line to your CVSROOT/loginfo file:

ALL $CVSROOT/CVSROOT/xcvs-loginfo.php $CVSROOT/CVSROOT/xcvs-config.php $USER %p %{sVv}


--------------------
xcvs-taginfo.php
--------------------

  A script to enforce access control for CVS tag and branch operations and
  to prepare data for xcvs-posttag.php. (This is the pre-tag hook.)
  To enable this, you must add this line to your CVSROOT/taginfo file:

ALL $CVSROOT/CVSROOT/xcvs-taginfo.php $CVSROOT/CVSROOT/xcvs-config.php $USER %t %b %o %p %{sTVv}


--------------------
xcvs-posttag.php
--------------------

  A script to insert all CVS tag and branch operations into your
  Drupal database by passing it to the Version Control API.
  (Given a a rare moment of intuitively named CVS hook scripts,
  you can instantly recognize this as post-tag hook.)
  To enable this, you must add this line to your CVSROOT/posttag file:

ALL $CVSROOT/CVSROOT/xcvs-posttag.php $CVSROOT/CVSROOT/xcvs-config.php $USER %t %b %o %p %{sTVv}

