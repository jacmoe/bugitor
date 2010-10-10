/**
* Database schema required by CDbAuthManager.
*/

drop table if exists AuthAssignment;
drop table if exists AuthItemChild;
drop table if exists AuthItem;

create table AuthItem
(
   name varchar(64) not null,
   type integer not null,
   description text,
   bizrule text,
   data text,
   primary key (name)
) type=InnoDB, character set utf8;

create table AuthItemChild
(
   parent varchar(64) not null,
   child varchar(64) not null,
   primary key (parent,child),
   foreign key (parent) references AuthItem (name) on delete cascade on update cascade,
   foreign key (child) references AuthItem (name) on delete cascade on update cascade
) type=InnoDB, character set utf8;

create table AuthAssignment
(
   itemname varchar(64) not null,
   userid varchar(64) not null,
   bizrule text,
   data text,
   primary key (itemname,userid),
   foreign key (itemname) references AuthItem (name) on delete cascade on update cascade
) type=InnoDB, character set utf8;

/**
* Queries to insert necessary roles and assignments for the Rights module.
* If you wish to use a different super user name than 'Admin' change it before running these queries.
* If you wish to assign the super user role to any other user do so.
*
* @author Christoffer Niska
* @copyright Copyright &copy; 2008 Christoffer Niska
* @since 0.5
*/

insert into AuthItem (name,type,data) values ('Admin',2,'N;');
insert into AuthItem (name,type,data) values ('Guest',2,'N;');
insert into AuthAssignment (itemname,userid,data) values ('Admin',1,'N;');

/**
* Schema for AuthItemWeight
* Used for sorting of AuthItem in Rights backend.
* @since 0.9.6
*/

create table AuthItemWeight
(
	itemname varchar(64) not null,
	type integer not null,
	weight integer not null,
	primary key (itemname),
	foreign key (itemname) references AuthItem (name) on delete cascade on update cascade
) type=InnoDB, character set utf8;

/**
* Schema for the User table.
* @since 0.9.6
*/

create table User
(
   id integer not null auto_increment,
   username varchar(128) not null,
   password varchar(128) not null,
   primary key (id)
) type=InnoDB, character set utf8;