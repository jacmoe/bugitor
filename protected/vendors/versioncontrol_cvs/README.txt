$Id: README.txt,v 1.4 2007/12/01 21:13:33 jpetso Exp $

CVS backend for Version Control API -
Provides CVS commit information and account management as a pluggable backend.


SHORT DESCRIPTION
-----------------
This module provides an implementation of the Version Control API that makes
it possible to use the CVS version control system. It can retrieve commit
information by parsing commit logs or by using the xcvs-* trigger scripts
that are called directly by CVS when a commit or tag operation is executed.

For the API documentation, have a look at the module file or run doxygen/phpdoc
on it to get a fancier version of the docs.

Any bug reports or feature requests concerning the xcvs-* scripts or
the CVS backend in general should be submitted to the CVS backend issue queue:
http://drupal.org/project/issues/versioncontrol_cvs

If you know that the functionality is (or should be) provided by the
Version Control API (and not by the CVS backend), please submit an issue there:
http://drupal.org/project/issues/versioncontrol


AUTHOR
------
Jakob Petsovits <jpetso at gmx DOT at>


CREDITS
-------
A good amount of code in Version Control / Project Node Integration was taken
from the CVS integration module on drupal.org, its authors (Kjartan Mannes and
Derek Wright, among others) deserve a lot of credits and may also hold
copyright for parts of this module.

This module was originally created as part of Google Summer of Code 2007,
so Google deserves some credits for making this possible. Thanks also
to Derek Wright (dww) and Andy Kirkham (AjK) for mentoring
the Summer of Code project.
