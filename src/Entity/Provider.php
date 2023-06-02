<?php

namespace App\Entity;

class Provider
{
    private string $nombre;
    private ?string $precio;
    private ?string $buyUrl;

    /**
     * @param string $nombre
     * @param string|null $precio
     * @param string|null $buyUrl
     */
    public function __construct(string $nombre, string $precio = null, string $buyUrl = null) // En caso de null no tenemos información de ese Provider
    {
        $this->nombre = $nombre;
        $this->precio = $precio;
        $this->buyUrl = $buyUrl;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     * @return Provider
     */
    public function setNombre(string $nombre): Provider
    {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrecio(): string
    {
        return $this->precio;
    }

    /**
     * @param string $precio
     * @return Provider
     */
    public function setPrecio(string $precio): Provider
    {
        $this->precio = $precio;
        return $this;
    }

    /**
     * @return string
     */
    public function getBuyUrl(): string
    {
        return $this->buyUrl;
    }

    /**
     * @param string $buyUrl
     * @return Provider
     */
    public function setBuyUrl(string $buyUrl): Provider
    {
        $this->buyUrl = $buyUrl;
        return $this;
    }




}
