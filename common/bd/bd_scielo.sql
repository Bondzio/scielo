CREATE DATABASE scielo;
USE DATABASE scielo;

CREATE TABLE revistas (
	rev_id 	INT(11) 		NOT NULL AUTO_INCREMENT,
	rev_nome 	VARCHAR(100) 	NOT NULL,
	rev_url 	VARCHAR(100) 	 NOT NULL,
	PRIMARY KEY (rev_id)
);

INSERT INTO revistas (rev_nome, rev_url) 
	VALUES ('Revista Brasileira de Ciências Sociais', 'http://www.scielo.br/scielo.php?script=sci_issues&pid=0102-6909&lng=pt&nrm=iso');
INSERT INTO revistas (rev_nome, rev_url) 
	VALUES ('Estudos Avançados', 'http://www.scielo.br/scielo.php?script=sci_issues&pid=0103-4014&lng=pt&nrm=iso');
INSERT INTO revistas (rev_nome, rev_url) 
	VALUES ('Revista de Sociologia e Política', 'http://www.scielo.br/scielo.php?script=sci_issues&pid=0104-4478&lng=pt&nrm=iso');
INSERT INTO revistas (rev_nome, rev_url) 
	VALUES ('Lua Nova - Revista de Cultura e Política', 'http://www.scielo.br/scielo.php?script=sci_issues&pid=0102-6445&lng=pt&nrm=iso');
INSERT INTO revistas (rev_nome, rev_url) 
	VALUES ('Dados', 'http://www.scielo.br/scielo.php?script=sci_issues&pid=0011-5258&lng=pt&nrm=iso');

ALTER TABLE revistas ADD COLUMN rev_dt_download VARCHAR(10);
	
CREATE TABLE pid_revistas (
	pidrev_id			INT(11)			NOT NULL AUTO_INCREMENT,
	rev_id				INT(11)	NOT NULL,
	pid					VARCHAR(100) NOT NULL,
	PRIMARY KEY (pidrev_id)
);

-- ATUALIZAÇÕES: 13/05/2014

ALTER TABLE pid_revistas ADD UNIQUE (pid);
ALTER TABLE pid_revistas ADD CONSTRAINT fk_rev_id FOREIGN KEY ( rev_id ) REFERENCES revistas ( rev_id ) ;

ALTER TABLE pid_revistas ADD COLUMN pidrev_dt_download VARCHAR(10);

CREATE TABLE tipo_artigo (
	tart_id 	INT(11) 		NOT NULL,
	tart_nome 	VARCHAR(100) 	NOT NULL,
	PRIMARY KEY (tart_id)
);

INSERT INTO tipo_artigo (tart_id, tart_nome) VALUES (1, 'Journal Article');

CREATE TABLE artigos (
	art_id			INT(11)			NOT NULL AUTO_INCREMENT,
	art_num			VARCHAR(5) 		NOT NULL,
	art_titulo		VARCHAR(400),
	art_ano			VARCHAR(4),
	art_volume		VARCHAR(100),
	art_paginas		VARCHAR(100),
	art_url			VARCHAR(300),
	art_resumo_br	TEXT,
	art_resumo_fr	TEXT,
	art_resumo_en	TEXT,
	art_resumo_es	TEXT,
	
	pidrev_id		INT(11) 		NOT NULL,
	tart_id			INT(11),
	PRIMARY KEY (art_id)
);

ALTER TABLE artigos ADD CONSTRAINT fk_pidrev_id 	FOREIGN KEY ( pidrev_id ) 	REFERENCES pid_revistas ( pidrev_id ) ;
ALTER TABLE artigos ADD CONSTRAINT fk_tart_id 	FOREIGN KEY ( tart_id ) 	REFERENCES tipo_artigo 	( tart_id ) ;

ALTER TABLE artigos ADD UNIQUE INDEX unique_pid (art_num, pidrev_id);

ALTER TABLE artigos ADD COLUMN art_dt_download VARCHAR(10);

-- ATUALIZAÇÕES: 03-06-2014

ALTER TABLE artigos CHANGE art_resumo_br art_resumo_pt TEXT;

ALTER TABLE artigos DROP INDEX fk_tart_id;
ALTER TABLE artigos DROP tart_id;
ALTER TABLE artigos DROP art_paginas;
ALTER TABLE artigos ADD art_fpage VARCHAR(100);
ALTER TABLE artigos ADD art_lpage VARCHAR(100);
ALTER TABLE artigos ADD art_mes VARCHAR(100);
ALTER TABLE artigos ADD art_tipo VARCHAR(100);

DROP TABLE tipo_artigo;

CREATE TABLE autores (
	aut_id					INT(11)			NOT NULL AUTO_INCREMENT,
	aut_givennames			VARCHAR(300) 		NOT NULL,
	aut_surname				VARCHAR(300)		NOT NULL,
	aut_urllattes			VARCHAR(300),
	
	art_id					INT(11) 		NOT NULL,
	PRIMARY KEY (aut_id)
	);

ALTER TABLE autores ADD CONSTRAINT fk_art_aut_id 	FOREIGN KEY ( art_id ) 	REFERENCES artigos ( art_id ) ;

CREATE TABLE bibliografia (
	bib_id					INT(11)			NOT NULL AUTO_INCREMENT,
	bib_titulo				VARCHAR(400) 		NOT NULL,
	bib_revista				VARCHAR(400),		
	bib_volume				VARCHAR(100),
	bib_paginas				VARCHAR(100),
	bib_ano					VARCHAR(4),
	
	art_id					INT(11) 		NOT NULL,
	PRIMARY KEY (bib_id)
	);

ALTER TABLE autores ADD CONSTRAINT fk_art_bib_id 	FOREIGN KEY ( art_id ) 	REFERENCES artigos ( art_id ) ;

CREATE TABLE autores_bibliografia (
	abi_id					INT(11)			NOT NULL AUTO_INCREMENT,
	abi_givennames			VARCHAR(300) 		NOT NULL,
	abi_surname				VARCHAR(300)		NOT NULL,

	bib_id					INT(11) 		NOT NULL,
	PRIMARY KEY (abi_id)
	);

ALTER TABLE autores_bibliografia ADD CONSTRAINT fk_bib_id 	FOREIGN KEY ( bib_id ) 	REFERENCES bibliografia ( bib_id ) ;



