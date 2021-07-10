 c:\xampp\mysql\bin\mysql -uedunova -pedunova < D:\pp22\polaznik20.edunova.hr\Stanko-website\skriptapp22.sql
drop database if exists edunovapp22;
create database edunovapp22 character set utf8mb4;
use edunovapp22;

na produkciji promjeniti charset jer je inicijalni krivo postavljen
alter database cesar_pp22 default character set utf8mb4;


create table operater(
    sifra int not null primary key auto_increment,
    email varchar(50) not null,
    lozinka char(60) not null,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    uloga varchar(10) not null
);
# lozinka je a
insert into operater values(null,'bradoljuti@outlook.com',
'$2y$10$Revp1k7DnQ0b1fClBuoZ8.O0w2RyIsXyTU51yqHz9mk7SLB/it9GO',
'Administrator','Edunova','admin');

# lozinka je o
insert into operater values(null,'oper@edunova.hr',
'$2y$10$yECpl/AKVYMutwEcMTJOZeUWwJ8kk7EtafwXdhfjYqs3UX2pEUTFu',
'Operater','Edunova','oper');

create table smjer(
    sifra int not null primary key auto_increment,
    naziv varchar(50) not null,
    trajanje int not null,
    cijena decimal(18,2),
    verificiran boolean
);

create table grupa(
    sifra int not null primary key auto_increment,
    naziv varchar(20) not null,
    smjer int not null, # FK
    predavac int, #FK
    datumpocetka datetime,
    brojpolaznika int
);

create table osoba(
    sifra int not null primary key auto_increment,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    oib char(11),
    email varchar(50) not null
);
create table predavac(
    sifra int not null primary key auto_increment,
    osoba int not null, #FK
    iban varchar(50)
);
create table polaznik(
    sifra int not null primary key auto_increment,
    osoba int not null, #FK
    brojugovora varchar(20)
);

create table clan(
    grupa int not null, #FK
    polaznik int not null #FK
);


alter table grupa add foreign key (smjer) references smjer (sifra);
alter table grupa add foreign key (predavac) references predavac(sifra);

alter table predavac add foreign key (osoba) references osoba(sifra);
alter table polaznik add foreign key (osoba) references osoba(sifra);

alter table clan add foreign key (grupa) references grupa(sifra);
alter table clan add foreign key (polaznik) references polaznik(sifra);



# najlošija
# 1
insert into smjer values (null,'PHP programiranje',130,5000.78,false);

# malo bolje
# 2
insert into smjer(naziv,trajanje) values ('Java programiranje',130);

# dobra praksa
# 3
insert into smjer(sifra,naziv,trajanje,cijena,verificiran)
values (null,'Serviser',100,null,true);


# 1
insert into grupa (sifra,naziv,predavac,smjer,datumpocetka,brojpolaznika)
values (null,'PP22',null,1,'2020-10-09',13);

# 2
insert into grupa(sifra,naziv,smjer,predavac,datumpocetka,brojpolaznika)
values (null,'JP23',2,null,'2020-10-19 17:00:00',13);


# 1
insert into osoba (sifra,ime,prezime,oib,email)
values (null,'Luka','Maršić',null,'lukamarsic@outlook.com');

# 2 - 16
insert into osoba (prezime, ime, email) values
('Mikić','Valentin','mikictino@gmail.com'),
('Amidžić','Marin','marin.amidzic@hotmail.com'),
('Lugarić','Daniela','dlugaric1504@gmail.com'),
('Bagarić','Zvonimir','zvonimirbagaric@gmail.com'),
('Knežević','Marin','marin.knezevic1et2@gmail.com'),
('Ivković','Dominik','dominik.ivkovic7@gmail.com'),
('Štajduhar','Borna','bornastajduhar1999@gmail.com'),
('Jeger','Tin','tin.jeger@gmail.com'),
('Dugeč','Dinko','dinko.dugec@gmail.com'),
('Šaravanja','Marko','marko.saravanja@hotmail.com'),
('Vulić','Domagoj','domagoj.vulic11@gmail.com'),
('Katalinić','Ivan','katalini.ivan321@gmail.com'),
('Puhovski','Ivan','ivan.puhovski@gmail.com'),
('Lalić','Ivana','ilalic110@gmail.com'),
('Pejić','Željko','zeljkopejic@yahoo.com');


# 1-16
insert into polaznik(osoba) values 
(1),(2),(3),(4),(5),(6),(7),(8),(9),(10),(11),(12),(13),(14),(15),(16);

insert into clan (grupa,polaznik) values
(1,1),(1,2),(1,3),(1,4),(1,5),(1,6),(1,7),(1,8),(1,9),(1,10),
(1,11),(1,12),(1,13),(1,14),(1,15),(1,16);

# 17
insert into osoba (sifra,ime,prezime,oib,email)
values (null,'Tomislav','Jakopec',null,'tjakopec@gmail.com');





# 18
insert into osoba (sifra,ime,prezime,oib,email)
values (null,'Bradoljuti',null,'shaki@gmail.com');

insert into polaznik20 (osoba) values (17),(18);

update grupa set polaznik20=1 where sifra=1;





