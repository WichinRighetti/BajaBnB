CREATE DATABASE sites;
use sites;

create table Users(
Id int primary key auto_increment,
Name varchar(30) not null,
LastName varchar(30) not null,
Phone varchar (12) not null unique,
Email varchar(50) not null unique,
Password varchar(100) not null,
Status bit default 1
);

create table Sites(
Id int primary key auto_increment,
Description varchar(200),
Address varchar(100) not null,
Image varchar(100),
Price float not null,
Latitude float not null,
Longitude float not null,
UserId int not null,
Status bit default 1,

constraint FK_SiteUser foreign key (UserId) references Users(Id)
);