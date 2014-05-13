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
	VALUES ('Lua Nova: Revista de Cultura e Política', 'http://www.scielo.br/scielo.php?script=sci_issues&pid=0102-6445&lng=pt&nrm=iso');
INSERT INTO revistas (rev_nome, rev_url) 
	VALUES ('Dados', 'http://www.scielo.br/scielo.php?script=sci_issues&pid=0011-5258&lng=pt&nrm=iso');
	
CREATE TABLE pid_revistas (
	pidrev_id			INT(11)			NOT NULL AUTO_INCREMENT,
	rev_id				INT(11)	NOT NULL,
	pid					VARCHAR(100) NOT NULL,
	PRIMARY KEY (pidrev_id)
);
