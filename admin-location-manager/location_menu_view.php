<?php


$FIRST_DOMAIN = "derbyshirelpg.co.uk";


 function callAPI($url){

    $curl=curl_init();
		
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_TIMEOUT, 30);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		
	$response=curl_exec($curl);
	curl_close($curl);
	$response = json_decode($response, true);
    return $response;
}




global $wpdb;

if ( ! class_exists( 'Location_Menu_Table' ) ) {
	require_once( 'Location_Menu_Table.php' );
}


 $exampleListTable = new Location_Menu_Table();
 $exampleListTable->prepare_items();
 
 $table_name = $wpdb->prefix . 'header_info';

?>
    <div class="wrap">
        <div id="icon-users" class="icon32"></div>
        <h2>County Site Management</h2>
        <?php 
            $action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
            $page = $_REQUEST['page'];
            $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
            if($action == 'edit')
            {
                $entry = $wpdb -> get_results("select * From $table_name where id=$id");
                
                if(!isset($entry) || count($entry) == 0)
                {
                    echo "There is no such data.";
                }else
                {
                  $entry = $entry[0];

                  $update_link = "?page=" . $_REQUEST['page'] . "&action=update&id=" . $entry -> id; 
                ?>
                
            <form action="<?php echo $update_link;?>" method="POST">
             
                
                <table class="form-table" role="presentation">
                	<tbody>
                    <tr><th><label for="id">ID</label></th>
                        <td><input type="text" name="id" disabled="disabled" class="regular-text" value="<?php echo $entry -> id; ?>"></td></tr>
                    <tr>
                        <th><label for="county">County</label></th>
                        <td><input type="text" required value="<?php echo $entry -> county; ?>" id="county" name="county" class="regular-text"></td>
                    </tr>
                    <tr>
                        <th><label for="domain">Domain</label></th>
                        <td><input type="text" required value="<?php echo $entry -> domain; ?>" id="domain" name="domain" class="regular-text"></td>
                    </tr>
                   
                    <tr>
                        <th><label for="price">Price</label></th>
                        <td><input type="number" required value="<?php echo $entry -> price; ?>" id="price" name="price" min="1" max="10000" class="regular-text" step="0.01"></td>
                    </tr>
                	</tbody>
	            </table>
	            <input type="submit" value="Save" class="button button-primary"/>
            </form>
            
            
            
                
            <?php
                }
            }
            else if($action == 'delete')
            {
               $wpdb -> delete($table_name, array('id' => $_REQUEST['id']));
               $org_link = "?page=" . $_REQUEST['page'];
                wp_redirect($org_link);
                
                
            }
            else if($action == 'register')
            {
                

                $register_link = "?page=" . $_REQUEST['page'] . "&action=store";
            ?>
            <form method="POST" action="<?php echo $register_link;?>">
             
                
                <table class="form-table" role="presentation">
                	<tbody>
                    <tr>
                        <th><label for="county">County</label></th>
                        <td><input type="text" required  id="county" name="county" class="regular-text" placeholder="ex. London"></td>
                    </tr>
                    <tr>
                        <th><label for="domain">Domain</label></th>
                        <td><input type="text" required id="domain" name="domain" class="regular-text" placeholder="ex. londonlpg.com"></td>
                    </tr>
                    
                    <tr>
                        <th><label for="price">Price</label></th>
                        <td><input type="number" required id="price" name="price" min="1" max="10000" class="regular-text" placeholder="ex. 20" step="0.01"></td>
                    </tr>
                	</tbody>
	            </table>
	            <input type="submit" value="Register" class="button button-primary"/>
            </form>
            
            
            <?php
            }
            else if($action == "store")
            {
                $result = $wpdb->insert($table_name, array('county' => $_REQUEST['county'], 'domain' => $_REQUEST['domain'], 'price' => $_REQUEST['price'], 'postcode' => '') );
                $org_link = "?page=" . $_REQUEST['page'];
                
                if($result) {
                    $basic_url = "https://" . $FIRST_DOMAIN . "/wp-json/wp/v2/location/method=add/county=" . $_REQUEST['county'] . "/id=" . $wpdb -> insert_id . "/price=" . $_REQUEST['price'] . "/domain=" . $_REQUEST['domain'];
                    
                    callAPI($basic_url);
                }
                
                wp_redirect($org_link);
            }
            else if($action == 'update')
            {
                $result = $wpdb->update($table_name, array('county' => $_REQUEST['county'], 'domain' => $_REQUEST['domain'], 'price' => $_REQUEST['price'], 'postcode' => ''), array('id' => $_REQUEST['id']));
               $org_link = "?page=" . $_REQUEST['page'];
               
               if($result) {
                    $basic_url = "https://" . $FIRST_DOMAIN . "/wp-json/wp/v2/location/method=update/county=" . $_REQUEST['county'] . "/id=" . $_REQUEST['id'] . "/price=" . $_REQUEST['price'] . "/domain=" . $_REQUEST['domain'];
                    echo $basic_url;
                    callAPI($basic_url);
                }
                
                
                // wp_redirect($org_link);
            }
            else
            {
                $exampleListTable->display(); 
                echo "<br/>";    
                
                $register_link =  sprintf('<a href="?page=%s&action=%s" class="button button-primary">Register New County</a>',$_REQUEST['page'], 'register');
                echo $register_link;
            }
               
        
        ?>
    </div>
