<?php
/**
 * Tipodecambio
 * Realiza scraping a una URL de la SBS
 * para extraer los tipos de cambios disponibles en una tabla HTML.
 *
 * @author Juan Julio Sandoval Layza <juanjus98@gmail.com>
 * @version 1.0
 */
class Tipodecambio
{
    private $sbs_url = 'https://www.sbs.gob.pe/app/pp/sistip_portal/paginas/publicacion/tipocambiopromedio.aspx';
    private $tipos_cambio = array();

    function __construct() {
       // Almacenamos el resultado del scraping a la URL $sbs_url
       $this->tipos_cambio = $this->setTiposCambio();
    }

    /**
     * Traer el contenido HTML de la URL
     *
     * @return string
     */
    function scrapingUrl()
    {
        $curl = curl_init($this->sbs_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $result = curl_exec($curl);
        if(curl_errno($curl)){
            echo 'Scraper error: ' . curl_error($curl);
            exit;
        }
        curl_close($curl);
        return $result;
    }

    /**
     * Extraer el HTML de la tabla que contiene los tipos de cambio.
     *
     * @return array
     */
    function getTable()
    {
        $htmlString = $this->scrapingUrl();
        $aDataTableDetailHTML = array();

        // Extraemos la tabla Tipo de Cambio
        $regexTable = '/<table class="rgMasterTable" border="0" id="ctl00_cphContent_rgTipoCambio_ctl00" width="100%" style="table-layout:auto;empty-cells:show;">(.*?)<\/table>/s';
        if ( preg_match($regexTable, $htmlString, $table) ){
            $tableStr = $table[0];

            $DOM = new DOMDocument;
            $DOM->loadHTML($tableStr);

            $header = $DOM->getElementsByTagName('th');
            $detail = $DOM->getElementsByTagName('td');

            foreach($header as $nodeHeader)
            {
                $aDataTableHeaderHTML[] = trim($nodeHeader->textContent);
            }

            $i = 0;
            $j = 0;
            foreach($detail as $sNodeDetail)
            {
                $aDataTableDetailHTML[$j][] = trim($sNodeDetail->textContent);
                $i = $i + 1;
                $j = $i % count($aDataTableHeaderHTML) == 0 ? $j + 1 : $j;
            }
        }
        return $aDataTableDetailHTML;
    }

    /**
     * Ordenamos los datos extraídos del HTML de la tabla
     * en un array que podamos consultar rápidamente
     *
     * @return array
     */
    function setTiposCambio()
    {
        $arrTable = $this->getTable();
        $result = array();

        foreach ($arrTable as $key => $value) {
            $descripcion = utf8_decode($value[0]);
            $compra = $value[1];
            $venta = $value[2];

            //Crear key
            $arrKey = $this->setKey($descripcion);
            $result[$arrKey] = array(
                'descripcion' => $descripcion,
                'compra' => $compra,
                'venta' => $venta
            );
        }
        return $result;
    }

    /**
     * Retorna todos los tipos de cambio en un array.
     *
     * @return array
     */
    function getAll(){
        return $this->tipos_cambio;
    }

    /**
     * Retorna el tipo de cambio de una moneda en específico.
     *
     * @param string $key
     * @return array
     */
    function getCambio($key){
        $arrTiposCambio = $this->tipos_cambio;
        return $arrTiposCambio[$key];
    }

    /**
     * Retorna una llave generada a partir de un texto.
     *
     * @param string $string
     * @return string
     */
    function setKey($string) {
        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "¨", "º", "-", "~",
                 "#", "@", "|", "!", "\"",
                 "·", "$", "%", "&", "/",
                 "(", ")", "?", "'", "¡",
                 "¿", "[", "^", "`", "]",
                 "+", "}", "{", "¨", "´",
                 ">", "< ", ";", ",", ":",
                 "."),
            '',
            $string
        );

        $string = str_replace(' ','-',$string);

        return strtolower($string);
    }
}