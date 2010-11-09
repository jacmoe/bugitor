set DB=dfdemo
set MYSQL=\usr\local\mysql5\bin\mysql

echo DROP DATABASE IF EXISTS `%DB%` | %MYSQL% -u root  -v
echo CREATE DATABASE `%DB%` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci | %MYSQL% -u root -v
%MYSQL% -u root %DB% -v < dump/local.sql 2> error.tmp 
%MYSQL% -u root %DB% -v < service/update_guest_id.sql 2>> error.tmp 
call copy error.tmp con
@del  error.tmp
@echo Finish.
@pause
