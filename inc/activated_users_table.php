<?php
class Activated_Users extends WP_List_Table {
     /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items(){
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $action = $this->current_action();

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

        $search = ( isset( $_REQUEST['s'] ) ) ? $_REQUEST['s'] : false;
        if(!empty($search)){
            global $wpdb;
            
            try {
                $search = sanitize_text_field( $search );
                $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lic_activations WHERE UserName LIKE '%$search%'", ARRAY_A);
                if(!is_wp_error( $wpdb )){
                    $this->items = $data;
                }
            } catch (\Throwable $th) {
                //throw $th;
            }

        }else{
            $this->items = $data;
        }
        
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns(){
        $columns = array(
            'cb'            => '<input type="checkbox" />',
            'ID'            => 'ID',
            'Orderno'       => 'Order No',
            'Mtid'          => 'Mtid',
            'Userid'        => 'User ID',
            'UserName'      => 'User Name',
            'Prodcode'      => 'Product Code',
            'Status'        => 'Status',
            'Comment'       => 'Comment',
            'Editable'      => 'Editable',
            'Expirytime'    => 'Expirytime',
            'Modifydate'    => 'Modifydate'
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
        return array(
            'ID'     => array('ID', true),
            'Orderno'     => array('Orderno', true),
            'Mtid'     => array('Mtid', true),
            'Userid'     => array('Userid', true),
            'UserName'     => array('UserName', true)
        );
    }


    /**
     * Get the table data
     *
     * @return Array
     */
    private function table_data(){
        $data = array();

        global $wpdb;

        $data = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}lic_activations ORDER BY ID ASC", ARRAY_A);
        
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
    public function column_default( $item, $column_name ){
        switch( $column_name ) {
            case 'ID':
            case 'Orderno':
            case 'Mtid':
            case 'Userid':
            case 'UserName':
            case 'Prodcode':
            case 'Status':
            case 'Comment':
            case 'Editable':
            case 'Expirytime':
            case 'Modifydate':
                return $item[ $column_name ];
            default:
                return print_r( $item, true ) ;
        }
    }

    function get_bulk_actions() {
        $actions = array(
          'products_disable'    => 'Disable Lifetime License',
          'subscription_disable'    => 'Disable Subscription License',
          'enable_products_'    => 'Enable Lifetime License',
          'enable_subscription_'    => 'Enable Subscription License'
        );
        return $actions;
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="orders[]" value="%s" />', $item['Orderno']
        );    
    }

    // All form actions
    function current_action(){
        global $wpdb;

        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'products_disable'){
            if(isset($_REQUEST['orders']) && !empty($_REQUEST['orders'])){
                $orders_data = $_REQUEST['orders'];
                if(is_array($orders_data)){
                    foreach($orders_data as $data){
                        
                        $order_id = $data;
                        
                        $order = wc_get_order( $order_id );
                        if($order !== false){
                            $items = $order->get_items();
                        
                            foreach ( $items as $item ) {
                                $product_id = $item->get_product_id();
                                
                                if(WC_Product_Factory::get_product_type($product_id) !== 'subscription'){
                                    $wpdb->update($wpdb->prefix.'lic_activations', array(
                                        'Editable' => 0
                                    ),array( 'Orderno' => $order_id ),array('%d'),array('%d'));
                                }
                            }
                        }
                    }
                }
            }else{
                print_r("No order selected.");
            }
        }

        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'subscription_disable'){
            if(isset($_REQUEST['orders']) && !empty($_REQUEST['orders'])){
                $orders_data = $_REQUEST['orders'];
                if(is_array($orders_data)){
                    foreach($orders_data as $data){
                        
                        $order_id = $data;
                        
                        $order = wc_get_order( $order_id );
                        if($order !== false){
                            $items = $order->get_items();
                        
                            foreach ( $items as $item ) {
                                $product_id = $item->get_product_id();
                            
                                if(WC_Product_Factory::get_product_type($product_id) === 'subscription'){
                                    $wpdb->update($wpdb->prefix.'lic_activations', array(
                                        'Editable' => 0
                                    ),array( 'Orderno' => $order_id ),array('%d'),array('%d'));
                                }
                            }
                        }
                    }
                }
            }else{
                print_r("No orders selected.");
            }
            
        }

        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'enable_products_'){
            if(isset($_REQUEST['orders']) && !empty($_REQUEST['orders'])){
                $orders_data = $_REQUEST['orders'];
                if(is_array($orders_data)){
                    foreach($orders_data as $data){
                        
                        $order_id = $data;
                        
                        $order = wc_get_order( $order_id );
                        if($order !== false){
                            $items = $order->get_items();
                        
                            foreach ( $items as $item ) {
                                $product_id = $item->get_product_id();
                            
                                if(WC_Product_Factory::get_product_type($product_id) !== 'subscription'){
                                    $wpdb->update($wpdb->prefix.'lic_activations', array(
                                        'Editable' => 1
                                    ),array( 'Orderno' => $order_id ),array('%d'),array('%d'));
                                }
                            }
                        }
                    }
                }
            }else{
                print_r("No orders selected.");
            }
            
        }

        if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'enable_subscription_'){
            if(isset($_REQUEST['orders']) && !empty($_REQUEST['orders'])){
                $orders_data = $_REQUEST['orders'];
                if(is_array($orders_data)){
                    foreach($orders_data as $data){
                        
                        $order_id = $data;
                        
                        $order = wc_get_order( $order_id );
                        if($order !== false){
                            $items = $order->get_items();
                        
                            foreach ( $items as $item ) {
                                $product_id = $item->get_product_id();
                            
                                if(WC_Product_Factory::get_product_type($product_id) === 'subscription'){
                                    $wpdb->update($wpdb->prefix.'lic_activations', array(
                                        'Editable' => 1
                                    ),array( 'Orderno' => $order_id ),array('%d'),array('%d'));
                                }
                            }
                        }
                    }
                }
            }else{
                print_r("No order selected.");
            }
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
        $orderby = 'ID';
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


        $result = strcmp( $a[$orderby], $b[$orderby] );

        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }

} //class