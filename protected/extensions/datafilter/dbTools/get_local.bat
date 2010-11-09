set DB=dfdemo
set MYSQLDUMP=\usr\local\mysql5\bin\mysqldump

%MYSQLDUMP% -u root -c %DB% > dump\local.sql