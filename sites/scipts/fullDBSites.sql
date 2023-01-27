CREATE DATABASE site;
use site;

CREATE TABLE Property(
    id_property int primary key auto_increment,
    propertyName varchar(255),
    propertyDescription varchar(200),
    id_propertyType not null,
    id_city int not null,
    id_user int not null,
    latitude float not null,
    longitude float not null,
    price float not null,
    status bit default 1
);

CREATE TABLE propertyType(
    id_propertyType int primary key auto_increment,
    propertyType varchar(255),
    status bit default 1
);

