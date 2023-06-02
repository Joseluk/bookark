<?php

namespace App\Entity;

class Book
{
    private string $titulo;
    private string $autor;
    private string $editorial;
    private string $isbn;
    private string $imagen;
    private array $providers;

    /**
     * @param string $titulo
     * @param string $autor
     * @param string $editorial
     * @param string $isbn
     * @param string $imagen
     * @param array|null $providers
     */
    public function __construct(string $titulo, string $autor, string $editorial, string $isbn, string $imagen, array $providers = null)
    {
        $this->titulo = $titulo;
        $this->autor = $autor;
        $this->editorial = $editorial;
        $this->isbn = $isbn;
        $this->imagen = $imagen;
        $this->providers = $providers;
    }

    /**
     * @return string
     */
    public function getTitulo(): string
    {
        return $this->titulo;
    }

    /**
     * @param string $titulo
     * @return Book
     */
    public function setTitulo(string $titulo): Book
    {
        $this->titulo = $titulo;
        return $this;
    }

    /**
     * @return string
     */
    public function getAutor(): string
    {
        return $this->autor;
    }

    /**
     * @param string $autor
     * @return Book
     */
    public function setAutor(string $autor): Book
    {
        $this->autor = $autor;
        return $this;
    }

    /**
     * @return string
     */
    public function getEditorial(): string
    {
        return $this->editorial;
    }

    /**
     * @param string $editorial
     * @return Book
     */
    public function setEditorial(string $editorial): Book
    {
        $this->editorial = $editorial;
        return $this;
    }

    /**
     * @return string
     */
    public function getIsbn(): string
    {
        return $this->isbn;
    }

    /**
     * @param string $isbn
     * @return Book
     */
    public function setIsbn(string $isbn): Book
    {
        $this->isbn = $isbn;
        return $this;
    }

    /**
     * @return string
     */
    public function getImagen(): string
    {
        return $this->imagen;
    }

    /**
     * @param string $imagen
     * @return Book
     */
    public function setImagen(string $imagen): Book
    {
        $this->imagen = $imagen;
        return $this;
    }

    /**
     * @return array
     */
    public function getProviders(): array
    {
        return $this->providers;
    }

    /**
     * @param array $providers
     * @return Book
     */
    public function setProviders(array $providers): Book
    {
        $this->providers = $providers;
        return $this;
    }




}
