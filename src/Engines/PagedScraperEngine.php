<?php

namespace Vulcan\Scraper\Engines;

class PagedScraperEngine extends ScraperEngine
{
    /** @var int */
    protected $page = 1;

    /** @var  int */
    protected $totalPages;

    public function fetch($query = [])
    {
        if ($this->totalPages && $this->page > $this->totalPages) {
            return false;
        }

        $query = array_merge($query, [
            'page' => $this->getPage()
        ]);

        parent::fetch($query);

        if (!$this->totalPages) {
            $this->setTotalPages();
        }

        $this->page++;

        return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param $pageNumber
     */
    public function setPage($pageNumber)
    {
        $this->page = $pageNumber;
    }

    public function setTotalPages()
    {
        throw new \RuntimeException('You must implement a method that determines how many pages there are');
    }
}