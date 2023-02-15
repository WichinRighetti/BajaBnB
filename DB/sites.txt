#Create DB
CREATE DATABASE sites1;
use sites1;

#Create tables
create table State(
id_state int primary key auto_increment,
stateName varchar(20) not null,
active bit default 1
);

create table City(
id_city int primary key auto_increment,
cityName varchar(20) not null,
id_state int not null,
active bit default 1
);

create table PropertyType(
id_propertyType int primary key auto_increment,
propertyType varchar(20) not null,
active bit default 1
);

create table UserType(
id_userType int primary key auto_increment,
userType varchar(20) not null,
active bit default 1
);

create table User(
id_user int primary key auto_increment,
name varchar(20) not null,
lastName varchar(20) not null,
phone varchar(10),
email varchar(30) not null,
id_userType int not null,
password varchar(10) not null,
active bit default 1
);

create table Property(
id_property int primary key auto_increment,
propertyName varchar(50),
propertyDescription varchar(100) not null,
id_propertyType int not null,
id_city int not null,
id_user int not null,
longitude float not null,
latitude float not null,
price float not null,
active bit default 1
);

#Changed reservation to Reservation in DB diagram
create table Reservation(
id_reservation int primary key auto_increment,
id_user int not null,
id_property int not null,
startDate date not null,
endDate date not null
);

create table PropertyImages(
id_propertyImage int primary key auto_increment,
id_property int not null,
url varchar(100) not null,
active bit default 1
);

#Fill tables
Insert Into State(stateName) Values ('Baja California');
Select * from State;

Insert Into City(cityName, id_state) Values ('Tijuana', 1);
Insert Into City(cityName, id_state) Values ('Ensenada', 1);
Insert Into City(cityName, id_state) Values ('Mexicali', 1);
Insert Into City(cityName, id_state) Values ('Tecate', 1);
Insert Into City(cityName, id_state) Values ('Playas de Rosarito', 1);
Insert Into City(cityName, id_state) Values ('San Quintin', 1);
Select * from City;

Insert Into PropertyType(propertyType) Values ('Casa');
Insert Into PropertyType(propertyType) Values ('Departamento');
Insert Into PropertyType(propertyType) Values ('Casa rodante');
Select * from PropertyType;

Insert Into UserType(userType) Values ('Arrendatario');
Insert Into UserType(userType) Values ('Huesped');
Select * from UserType;

Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Arrendatario', 'Uno', '0446641234', 'correo1@gmail.com', 1, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Arrendatario', 'Dos', '0446641235', 'correo2@gmail.com', 1, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Arrendatario', 'Tres', '0446641236', 'correo3@gmail.com', 1, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Huesped', 'Uno', '0446641237', 'correo4@gmail.com', 2, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Husped', 'Dos', '0446641238', 'correo5@gmail.com', 2, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Huesped', 'Tres', '0446641239', 'correo6@gmail.com', 2, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Huesped', 'Cuatro', '0446641230', 'correo7@gmail.com', 2, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Huesped', 'Cinco', '0446641241', 'correo8@gmail.com', 2, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Huesped', 'Seis', '0446641242', 'correo9@gmail.com', 2, '123abc');
Insert Into User(name, lastName, phone, email, id_userType, password) Values 
('Huesped', 'Siete', '0446641243', 'correo10@gmail.com', 2, '123abc');
Select * from User;

Insert Into Property(propertyName, propertyDescription, id_propertyType, id_city, id_user, longitude, latitude, price) Values 
('Propiedad 1', 'Esta es la propiedad 1', 1, 1, 1, 32.4964153, -116.9548891, 1000);
Insert Into Property(propertyName, propertyDescription, id_propertyType, id_city, id_user, longitude, latitude, price) Values 
('Propiedad 2', 'Esta es la propiedad 2', 1, 1, 1, 32.507241, -116.9317875, 1500);
Insert Into Property(propertyName, propertyDescription, id_propertyType, id_city, id_user, longitude, latitude, price) Values 
('Propiedad 3', 'Esta es la propiedad 3', 1, 1, 1, 32.5008455, -116.9152797, 1000);
Insert Into Property(propertyName, propertyDescription, id_propertyType, id_city, id_user, longitude, latitude, price) Values 
('Propiedad 4', 'Esta es la propiedad 4', 1, 1, 1, 32.4687682, -116.962683, 900);
Select * from Property;

Insert Into PropertyImages(id_property, url) Values 
(1, 'Imagen1');
Insert Into PropertyImages(id_property, url) Values 
(2, 'Imagen2');
Insert Into PropertyImages(id_property, url) Values 
(3, 'Imagen3');
Insert Into PropertyImages(id_property, url) Values 
(4, 'Imagen4');
Insert Into PropertyImages(id_property, url) Values 
(5, 'Imagen5');
Select * from PropertyImages;