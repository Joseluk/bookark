<?php

namespace App\Utils;

class BookFormater
{
    /**
     * Elimina los guiones de un ISBN.
     *
     * @param string $isbn
     * @return string
     */
    public function formatISBN(string $isbn): string
    {
        //return str_replace('-', '', $isbn);
        return $isbn;
    }

    /**
     * Reemplaza puntos por comas
     * Añade un símbolo € al final del precio si no está ya presente.
     * Si nos viene a null de la fuente de datos (no existe) mostraremos un guion.
     * Si el precio es 0.00 significa que el provider no lo tiene en stock (pero existe)
     *
     * @param string|null $precio
     * @return string
     */
    public function formatPrecio(string $precio = null): string
    {
        if ($precio === "0.00") {
            $precio = "Sin stock";
        } else if ( $precio !== null) {
            // Reemplazamos el punto por una coma
            $precio = str_replace(".", ",", $precio);

            // Si no hay un '€' en la cadena, lo añadimos al final
            if (strpos($precio, '€') === false) {
                $precio .= '€';
            }
        }

        return $precio ?? '-';
    }


    /**
     * Si la imagen no está disponible para el provider la reemplazamos por nuestra imagen de fallback (no-disponible.png)
     * Se pueden agregar más mapeos al array en el futuro
     *
     * @param string $imageUrl
     * @return string
     */
    public function formatImagen(string $imageUrl): string
    {
        $mappings = [
            '/img/img-no-disponible.gif' => '/assets/images/no-disponible.png',
        ];

        foreach($mappings as $search => $replace) {
            $imageUrl = str_replace($search, $replace, $imageUrl);
        }

        return $imageUrl;
    }

}
