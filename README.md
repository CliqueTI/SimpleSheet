# Simple Sheet

Pequeno, simples e descomplicado. Simple Sheet é um componente php que simplifica a leitura de arquivos Excel (XLSX)Planilhas ODS e Arquivos CSV. Este componente utiliza o **[OpenSpout](https://github.com/openspout/openspout)** como base.

###### Small, simple and uncomplicated. Simple Sheet is a php component that simplifies reading Excel (XLSX) files, ODS Spreadsheets and CSV files. This component uses **[OpenSpout](https://github.com/openspout/openspout)** as a base.

## Installation (Instalação)

Via Composer

```bash
"cliqueti/simplesheet": "1.0.*"
```

Or run (ou rode)

```bash
composer require cliqueti/simplesheet
```

## Read Sheet (Ler Planilha)

```php
<?php

use CliqueTI\SimpleSheet\SimpleSheet;

$sheet = SimpleSheet::read("file.ext");
```

### Main options (Principais opções)

```php
...

/* For headerless files (Para arquivos sem cabeçalho) */
$sheet->withoutHeaderRow();

/* Do not parse formulas (Não analisar formulas) */
$sheet->withoutParseFormulas();

/* By Tab Name (Por nome de aba) */
$sheet->sheetName("Tab Name");

/* By Tab Number (Por número da aba) */
$sheet->sheetNumber(1);

/* Returns Sheet Header, If Exists (Retorna o cabeçalho, se existir) */
$sheet->getHeaders();

/* Returns Sheet Rows. (Retorna as linhas) */
$sheet->getRows();
```

### Main XLSX Options (Principais opções XLSX)
```php
...

/* Default value is false. True will return unformatted dates */
$sheet->options()->SHOULD_FORMAT_DATES = true;
```

### Main CSV Options (Principais opções CSV)
```php
...
$sheet->options()->FIELD_DELIMITER  = '|';
$sheet->options()->FIELD_ENCLOSURE  = '@';
```

## Support

###### Security: If you discover any security related issues, please email paulo@cliqueti.com.br instead of using the issue tracker.

Se você descobrir algum problema relacionado à segurança, envie um e-mail para paulo@cliqueti.com.br em vez de usar o
rastreador de problemas.

Thank you

## Credits

- [Paulo Brandeburski](https://github.com/PauloBrand) (Developer)

## License

The MIT License (MIT).
