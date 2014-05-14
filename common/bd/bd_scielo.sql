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
	art_url			VARCHAR(100),
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