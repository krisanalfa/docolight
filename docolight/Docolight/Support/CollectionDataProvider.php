<?php

namespace Docolight\Support;

use Exception;
use CDataProvider;
use Docolight\Support\Fluent;
use Docolight\Support\Collection;

/**
 * Array helper.
 *
 * @author Krisan Alfa Timur <krisanalfa@docotel.co.id>
 */
class CollectionDataProvider extends CDataProvider
{
    /**
     * Collection file
     *
     * @var \Docolight\Support\Collection
     */
    protected $collection = null;

    /**
     * Free text field
     *
     * @var array
     */
    protected $freeTextField = array();

    /**
     * Class construction.
     *
     * @param \Docolight\Support\Collection $collection
     * @param array                         $freeTextField
     */
    public function __construct(Collection $collection, array $freeTextField = array())
    {
        // This contains a list of field that can perform such a free text search
        $this->freeTextField = $freeTextField;

        if (! $collection->first() instanceof Fluent) {
            throw new Exception("The items in Collection must be an instance of \Docolight\Support\Fluent.");
        }

        // Let's create a bare sort
        $sort = $this->getSort();

        // Set a list of attributes that available to be sorted
        $sort->attributes = array_keys($collection->first()->attributes());

        // Set the sort object
        $this->setSort($sort);

        // Is there any filter from request?
        $filter = array_filter(request(get_class($collection), array()));

        // Make a collection, filter it first if we got a filter request
        $this->collection = (! empty($filter)) ? $this->filterCollection($collection, $filter) : $collection;

        $sortRequest = request('sort');

        // This is it, a sort request
        if ($sortRequest !== null) {
            // Get the field name we want to sort
            $fieldToSort = head(explode('.', $sortRequest));

            // Determine whether request is a ascending sort or descending one
            $isDescending = (trimtolower(last(explode('.', $sortRequest)))  === 'desc');

            // Assign new collection instance
            $this->collection = $this->collection->sortBy($fieldToSort, SORT_REGULAR, $isDescending);
        }
    }

    /**
     * Get collection
     *
     * @return \Docolight\Support\Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Fetches the data from the persistent data storage.
     *
     * @return array list of data items
     */
    protected function fetchData()
    {
        // Get our pagination object
        $pagination = $this->getPagination();

        // Default pagination current page
        $currentPage = (int) request($pagination->pageVar, 1);

        // Return an offset array
        return $this->collection->forPage($currentPage, $pagination->getPageSize())->all();
    }

    /**
     * Calculates the total number of data items.
     *
     * @return int the total number of data items.
     */
    protected function calculateTotalItemCount()
    {
        // Count all
        $total = $this->collection->count();

        // Make a pagination
        $pagination = $this->getPagination();

        // Set total item in pagination
        $pagination->setItemCount($total);

        // Set a new pagination instance
        $this->setPagination($pagination);

        return $total;
    }

    /**
     * Fetches the data item keys from the persistent data storage.
     *
     * @return array list of data item keys.
     */
    protected function fetchKeys()
    {
        return ($this->collection->first() instanceof Fluent) ? array_keys($this->collection->first()->attributes()) : array();
    }

    /**
     * Filter collection by criteria
     *
     * @param \Docolight\Support\Collection $collection
     * @param array                         $filter
     *
     * @return \Docolight\Support\Collection
     */
    protected function filterCollection(Collection $collection, array $filter)
    {
        // Get free text field value
        $freeTextField = $this->freeTextField;

        // Perform filter using a callback, see \Docolight\Support\Collection::filter method
        $collection = $collection->filter(function ($item) use ($filter, $freeTextField) {
            // Checking items, it should be an instance of Fluent
            if (! $item instanceof Fluent) {
                throw new Exception("The items in Collection must be an instance of \Docolight\Support\Fluent.");
            }

            // We should separate between normal field and free text field
            $attributes = array_except($item->attributes(), $freeTextField);

            // We compare this filter with the attributes (attributes without freetext field value)
            $withoutFreeText = array_except($filter, $freeTextField);

            // We will compare this value to attributes if user performs free text search
            $freeTextCriteria = array_only($filter, $freeTextField);

            // The basic search, we compare the attributes that doesn't have ability to perform free text search
            $basicSearch = (empty($withoutFreeText)) ? true : !empty(array_intersect_assoc($withoutFreeText, $attributes));

            // Some variable to deterimine the result of free text search
            $freeTextSearchMode = false;
            $matchesCounter = 0;

            // Free text search performed
            if (!empty($freeTextCriteria)) {
                $freeTextSearchMode = true;

                // Loop for each filter, but only for the free text field
                foreach ($freeTextCriteria as $itemField => $expectation) {
                    // If the value is contains our expectation, let's add the matches counter
                    if (str_contains(mb_strtolower($item->{$itemField}), trimtolower($expectation))) {
                        $matchesCounter++;
                    }
                }
            }

            // Here we check, if free text search has been performed, the matches counter should be the same size with
            // free text criteria. Means, all criteria should be match in the item, we also check basic search to maintain
            // document validity.
            return ($freeTextSearchMode) ? (($matchesCounter === count($freeTextCriteria)) and $basicSearch) : $basicSearch;
        });

        // Set the filter to the collection, so it'll be shown in filter text field
        $collection->setFilters($filter);

        return $collection;
    }
}
