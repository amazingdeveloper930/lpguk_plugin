<?php


if ( ! class_exists( 'WP_List_Table' ) ) {
    
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class Location_Menu_Table extends WP_List_Table
{
     /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();
        usort( $data, array( &$this, 'sort_data' ) );

        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $data = array_slice($data,(($currentPage-1)*$perPage),$perPage);

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
            'id'          => 'No',
            'county'      => 'County',
            'domain'      => 'Domain',
            'postcode'    => 'PostCode',
            'price'       => 'Price',
            'action'      => 'Action'
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        return array('county' => array('county', false), 'id' => array('id', true));
    }

    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data()
    {
        $data = array();
        $page = $_REQUEST['page'];
        
        global $wpdb;

        $table_name = $wpdb->prefix . 'header_info';
        $entries = $wpdb -> get_results("select * From $table_name where 1=1 order by domain");
  
        foreach($entries as $entry)
        {
            $edit_link =  sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>',$_REQUEST['page'], 'edit', $entry -> id);
            $delete_link =  sprintf('<a href="?page=%s&action=%s&id=%s">Delete</a>',$_REQUEST['page'],'delete', $entry -> id);
            $entry->action = $edit_link . ' | ' . $delete_link;
            $data []= $entry;
        }
        


        return $data;
    }

    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'county':
            case 'price':
            case 'domain':
            case 'postcode':
            case 'action':
                return $item -> $column_name;

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = $_GET['orderby'];
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = $_GET['order'];
        }

        if(is_numeric($a -> $orderby))
        {
            $result = intval($a -> $orderby) >= intval($b -> $orderby);
        }
        else{
                $result = strcmp( $a -> $orderby, $b -> $orderby );
        }

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
    
    function no_items() {
      _e( 'No books found, dude.' );
    }
}
