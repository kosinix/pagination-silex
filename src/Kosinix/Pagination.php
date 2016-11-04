<?php

namespace Kosinix;

/*
 * Helper for Paginator used in Silex. Used in views.
 */
class Pagination
{
    /**
     * @var \Kosinix\Paginator $paginator Holds instance of paginator object
     */
    protected $paginator;

    /**
     * @var \Symfony\Component\Routing\Generator\UrlGenerator $url_generator
     */
    protected $url_generator;

    /**
     * @var string The URL binding ID
     */
    protected $bindings;

    /**
     * @var string Sort by in SQL
     */
    protected $sort_by;

    /**
     * @var string Sorting in SQL
     */
    protected $sorting;

    /**
     * Pagination constructor.
     *
     * @param \Kosinix\Paginator $paginator
     * @param $url_generator
     * @param $bindings
     * @param $sort_by
     * @param $sorting
     */
    public function __construct($paginator, $url_generator, $bindings, $sort_by, $sorting)
    {
        $this->paginator = $paginator;
        $this->url_generator = $url_generator;
        $this->bindings = $bindings;
        $this->sort_by    = $sort_by;
        $this->sorting   = $sorting;//($sorting==='asc') ? 'desc' : 'asc'; // Fix this bug when clicking next and pre continously and desc asc toggle states implicitly

        $this->pages = array();


    }

    /**
     * @return string
     */
    public function firstPageUrl(){
        return $this->url_generator->generate($this->bindings, array(
            'page'    => $this->paginator->getFirstPage(),
            'sort_by'  => $this->sort_by,
            'sorting' => $this->sorting
        ));
    }

    /**
     * @return string
     */
    public function lastPageUrl(){
        return $this->url_generator->generate($this->bindings, array(
            'page'    => $this->paginator->getLastPage(),
            'sort_by'  => $this->sort_by,
            'sorting' => $this->sorting
        ));
    }

    /**
     * @return string
     */
    public function previousPageUrl(){
        return $this->url_generator->generate($this->bindings, array(
            'page'    => $this->paginator->getCurrentPage()-1,
            'sort_by'  => $this->sort_by,
            'sorting' => $this->sorting
        ));
    }

    /**
     * @return string
     */
    public function nextPageUrl(){
        return $this->url_generator->generate($this->bindings, array(
            'page'    => $this->paginator->getCurrentPage()+1,
            'sort_by'  => $this->sort_by,
            'sorting' => $this->sorting
        ));
    }


    /**
     * Generate URL from current page.
     *
     * @param int $page
     *
     * @return string
     */
    public function pageUrl($page)
    {
        return $this->url_generator->generate($this->bindings, array(
            'page'    => $page,
            'sort_by'  => $this->sort_by,
            'sorting' => $this->sorting
        ));
    }

    /**
     * Generate URL for sorting by column names.
     *
     * @param $sort_by
     *
     * @return string
     */
    public function sortingUrl($sort_by)
    {

        return $this->url_generator->generate($this->bindings, array(
            'page'    => $this->paginator->getCurrentPage(),
            'sort_by'  => $sort_by,
            'sorting' => $this->getSortingToggled()
        ));
    }

    /*
     * Should we show pagination?
     */
    public function isNeeded()
    {
        if ($this->paginator->getLastPage() > 1) {
            return true;
        }

        return false;
    }

    /*
     * Should we show previous page button?
     */
    public function isPreviousPage()
    {
        if ($this->paginator->getCurrentPage() - 1 >= 1) {
            return true;
        }

        return false;
    }

    /*
     * Should we show next page button?
     */
    public function isNextPage()
    {
        if ($this->paginator->getCurrentPage() + 1 <= $this->paginator->getLastPage()) {
            return true;
        }

        return false;
    }

    /**
     * Toggled state of current sorting.
     *
     * @return string
     */
    public function getSortingToggled()
    {
        return ($this->sorting === 'asc') ? 'desc' : 'asc';
    }

    /**
     * @return Paginator
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

    /**
     * @return \Symfony\Component\Routing\Generator\UrlGenerator
     */
    public function getUrlGenerator()
    {
        return $this->url_generator;
    }

    /**
     * @return string
     */
    public function getBindings()
    {
        return $this->bindings;
    }

    /**
     * @return string
     */
    public function getSortBy()
    {
        return $this->sort_by;
    }

    /**
     * @return string
     */
    public function getSorting()
    {
        return $this->sorting;
    }

}