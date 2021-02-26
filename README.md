# Scraping tipos de cambio SBS Perú

Tuve la necesidad de contar con los tipos de cambio diario para realizar la conversión de Dolara a Soles; entonces me puse a googlear si hay algún servicio web disponible para poder realizar este caso, pero no tuve éxito.

Me puse a indagar sobre scraping y sé que es un término que en realidad engloba cosas mucho más avanzadas, pero para mi caso me sirvió realizarlo con PHP.

Sin más; pongo a si disposición mi humilde investigación y desarrollo para que les pueda ayudar en casos similares o como crean conveniente.


## Implementación

1.- Incluir la clase Tipodecambio.php

```php
include_once dirname(__FILE__) . '/Tipodecambio.php';
```

2.- Inicializar la clase Tipodecambio

```php
$tipoCambio = new Tipodecambio();
```

3.1.- Consultar todos los tipos de cambio; retorna un array
```php
$allCambios = $tipoCambio->getAll();
```

3.2- Consultar un tipo de cambio como por ejemplo el tipo de cambio del dolar; retorna un array
```php
$cambioDolar = $tipoCambio->getCambio('dolar-de-na');
```
Key | Descripción
--- | ---
dolar-de-na | Dólar de N.A.
dolar-australiano | Dólar Australiano
libra-esterlina | Libra Esterlin
yen-japones | Yen Japonés
peso-mexicano | Peso Mexicano
franco-suizo | Franco Suizo
euro | Euro

> Nota: `Los valores de la tabla anterior pueden cambiar` porque depende de la actualización del la URL de tipos de cambio de la SBS.

URL de donde se extraen los tipos de cambio: [COTIZACIÓN DE OFERTA Y DEMANDA TIPO DE CAMBIO PROMEDIO PONDERADO - SBS](https://www.sbs.gob.pe/app/pp/sistip_portal/paginas/publicacion/tipocambiopromedio.aspx).