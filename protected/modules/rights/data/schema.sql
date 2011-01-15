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
* Schema required by Rights.
* Stores Rights specific data about authorization items.
* Replaces the old AuthItemWeight-table.
* @since 1.1.0
*/

create table Rights
(
	itemname varchar(64) not null,
	type integer not null,
	weight integer not null,
	primary key (itemname),
	foreign key (itemname) references AuthItem (name) on delete cascade on update cascade
) type=InnoDB, character set utf8;

/**
* Schema for the User table.
* Not necessary to create if already exists.
* @since 0.9.6
*/

create table User
(
   id integer not null auto_increment,
   username varchar(128) not null,
   password varchar(128) not null,
   primary key (id)
) type=InnoDB, character set utf8;