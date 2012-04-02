    ____              _ __
   / __ )__  ______ _(_) /_____  _____
  / __  / / / / __ `/ / __/ __ \/ ___/
 / /_/ / /_/ / /_/ / / /_/ /_/ / /
/_____/\__,_/\__, /_/\__/\____/_/
            /____/

A Yii powered issue tracker

http://www.yiiframework.com/
http://bitbucket.org/jacmoe/bugitor/
http://tracker.ogitor.org/projects/bugitor

Copyright (C) 2009 - 2012 Bugitor Team

Permission is hereby granted, free of charge, to any person
obtaining a copy of this software and associated documentation files
(the "Software"), to deal in the Software without restriction,
including without limitation the rights to use, copy, modify, merge,
publish, distribute, sublicense, and/or sell copies of the Software,
and to permit persons to whom the Software is furnished to do so,
subject to the following conditions:
The above copyright notice and this permission notice shall be included
in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM,
DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE
OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

# Linx
Bugitor is an issue tracker written in PHP using the Yii Framework (http://www.yiiframework.com/).
Home page: http://tracker.ogitor.org/projects/bugitor
Repository: https://bitbucket.org/jacmoe/bugitor

# INSTALLATION
1. Copy bugitor files to the desired folder (e.g. /bugitor)
2. Create bugitor MySQL database:
		CREATE DATABASE `bugitor` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
3. Run '/bugitor/installer' and follow installation instructions
4. After finishing installation you can login with 
		login:		admin
		password:	admin
5. Please not forget to change your login/password
6. Welcome to bugitor!

# Mercurial repository support
1. Bugitor currently requires that the 'hg' executable is present on the server on which it runs.
2. The user which runs php-cli should be the same user who runs the apache php process, otherwise you need to
   edit the hgrc for the server Mercurial installation and add the Apache user to the list of trusted users.
3. Bugitor creates a local clone of each repository in the 'repositories' directory.
4. After the repository has been created, you need to go to /authorUser/admin and edit the mappings between
   Bugitor users and Mercurial authors. After that you need to set the status field of the repository table to '2'
   (Will remove this part when it's been integrated into the user interface)
5. The 'handlerepositories' console command will examine the repository and import the changesets.
   Look in bugitor/protected/commands for a sample cron entry if you want to schedule the command.
6. The script fetches 50 entries each run, so you might want to run the 'handlerepositories' command manually
   if you are importing a larger, existing repository.
7. Later, support for the Bitbucket API will be implemented which will eliminate the need to keep a local
   repository clone.
8. Support for SVN and Git is planned (including Github API).

# Issue reply by Email
1. A sample postfix file is available in bugitor/protected/commands - which will pipe incoming emails to the 'FetchEmailCommand'
   script. It is designed to parse emails where the subject is of the form: "Re: [project_name - issue_type #issue_number] issue_subject"
   The email address of the sender identifies the user.
2. To make this work on a host, for example Dreamhost, you need to forward email to a shell account and place to postfix
   script at the root of the home directory (rename it .forward.postfix)
