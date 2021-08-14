# c:\xampp\mysql\bin\mysql -uedunova -pedunova <D:\pp22\polaznik20.edunova.hr\skripta1.sql
drop database if exists covid19;
create database covid19 character set utf8mb4;
use covid19;

alter database erinije_COVID19 default character set utf8mb4;


create table operater(
    sifra int not null primary key auto_increment,
    email varchar(50) not null,
    lozinka char(60) not null,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    uloga varchar(10) not null
);
# lozinka je i
insert into operater values(null,'bradoljuti@outlook.com',
'$2y$10$6ZPMQsSKUNkeDL8elh.cluD4eQtCfMrb2rzrGPNq88vomgqMGbHQq',
'Luka','Marsic','admin');
# lozinka je k
insert into operater values(null,'admin@outlook.com',
'$2y$10$sgpLyFe/CDE1s8fclSngU.TCScIDRBh1uUZOhRXpZByhChZ8I0yGe',
'Admin','Edunova','oper');

create table narudzbe(
    sifra int not null primary key auto_increment,
    naziv varchar(60) not null,
    trajanje int not null,
    cijena decimal(18,2),
    potvrda boolean
);

create table ordinacija(
    sifra int not null primary key auto_increment,
    naziv varchar(20) not null,
    narudzbe int not null, # FK
    doktor int, #FK
    datumpocetka datetime,
    brojradnika int
);

create table osoba(
    sifra int not null primary key auto_increment,
    ime varchar(50) not null,
    prezime varchar(50) not null,
    oib char(11),
    email varchar(50) not null
);
create table doktor(
    sifra int not null primary key auto_increment,
    osoba int not null, #FK
    iban varchar(50)
);
create table radnik(
    sifra int not null primary key auto_increment,
    osoba int not null, #FK
    brojugovora varchar(20)
);

create table osoblje(
    ordinacija int not null, #FK
    radnik int not null #FK
);

alter table ordinacija add foreign key (narudzbe) references narudzbe (sifra);
alter table ordinacija add foreign key (doktor) references doktor(sifra);

alter table doktor add foreign key (osoba) references osoba(sifra);
alter table radnik add foreign key (osoba) references osoba(sifra);

alter table osoblje add foreign key (ordinacija) references ordinacija(sifra);
alter table osoblje add foreign key (radnik) references radnik(sifra);

#1
insert into narudzbe (sifra,naziv,trajanje,cijena,potvrda)
values (null,'narudzbe',10,null,true);

insert into narudzbe (sifra,naziv,trajanje,cijena,potvrda)
values (null,'izolacija',30,null,true);

#2
insert into osoba(sifra,ime,prezime,oib,email)
values (null,'Dario','Nakic',null,'dario.nakic@covid19.hr'),
       (null,'Zeljko','Reiner',null,'zeljko.reinerp@covid19.hr'),
       (null,'Milan','Kujundzic',null,'milan.kujundzic@covid19.hr'),
       (null,'Andrej','Plenkovic',null,'andrej.plenkovic@covid19.hr'),
       (null,'Vili','Beros',null,'vili.beros@covid19.hr'),
       (null,'Alemka','Markotic',null,'alemka.markotic@covid19.hr'),
       (null,'Krunoslav','Capak',null,'krunoslav.capak@covid19.hr');
      
#3

insert into doktor (sifra,osoba,iban)
values (null,6,null),(null,7,null);

#4

insert into ordinacija (sifra,naziv,narudzbe,doktor,datumpocetka,brojradnika)
values (null,'Beros company',1,1,'2021-06-06 14:00:00',5);

insert into ordinacija (sifra,naziv,narudzbe,doktor,datumpocetka,brojradnika)
values (null,'Capak company',2,2,'2021-06-06 13:00:00',5);

#5

insert into radnik (sifra,osoba,brojugovora)
values (null,1,null),(null,2,null),(null,3,null),(null,4,null),(null,5,null);

#6

insert into osoblje (ordinacija,radnik)
values (1,1),(1,2),(1,3),(1,4),(1,5);

update ordinacija set doktor=1 where sifra=1;
