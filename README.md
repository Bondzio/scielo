# scielo

[INICIO]

Instruções de utilização do sistema de raspagem do Scielo:

BANCO DE DADOS:
    Limpar o banco de dados: clean_database.sql

REVISTAS: 
    + Inserir as revistas ao banco de dados: insert_revistas.php
    + Baixar as revistas: download_revistas.php
    + Parsear as revistas baixadas: parse_revistas.php

EDIÇÕES: 
    + Baixar as edições: download_revistas_edicoes.php
    + Parsear as edições baixadas: parse_revistas_edicoes.php

ARTIGOS:
    + Baixar os artigos (XML): download_artigos.php
    + Baixar os artigos (HTML): download_artigos_html.php
    
    + Verificar se baixou todos os artigos: verificar_arquivos.php

    + Parsear os artigos: parse_artigos.php
        + Parsear bibliografia: parse_bibliografia.php
        + Parsear Palavras-chave: parse_palavras_chave.php
        + Parsear Resumos (abstract): parse_resumos.php

GERAR BASE:

    + Gerar base para conferência: generate_csv.php

[FIM]
