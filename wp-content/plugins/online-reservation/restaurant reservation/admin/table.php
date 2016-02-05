<?php
/*
	TABLE OF CONTENTS ( TABLE TAB )
	=======================================
	1.	TABLE SIZE
	2.	ONE ( SIZE ) TOTAL TABLE
	3.	ONE ( SIZE ) MAXIMUM PERSON PER TABLE 
	4.	MANY ( SIZE ) TYPE OF TABLE 
	5.	TYPE OF TABLE
	6. 	DISPLAY TYPE OF TABLE
*/
?>

	<?php //= 1.	TABLE SIZE  ?>
    <h2 class="head-parent no-margin-top icon-table-size">
		<?php _e('Table Size',PLUGIN_NAME); ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <label for="table_size"><?php _e('Table Size',PLUGIN_NAME) ?></label>
        <select name="resto_all_setting[table_size]" id="table_size">
            <option value="one" <?php selected( $options['table_size'], 'one' ); ?>><?php _e('one',PLUGIN_NAME); ?></option>
            <option value="many" <?php selected( $options['table_size'], 'many' ); ?>><?php _e('many',PLUGIN_NAME); ?></option>
        </select>
        <br class="float-none height_20px" />
	</div>
	
    <h2 class="head-parent icon-one-size">
		<?php _e('One Size',PLUGIN_NAME); ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <br />
        <?php //= 2.	ONE ( SIZE ) TOTAL TABLE ?>
        <label for="table_size"><?php _e('Total Table',PLUGIN_NAME) ?></label>
        <input type="text" id="one_total_table" name="resto_all_setting[one_total_table]" value="<?php echo $options['one_total_table'] ?>" />
        <br class="height_10px" />
        <?php //= 3.	ONE ( SIZE ) MAXIMUM PERSON PER TABLE  ?>
        <label for="table_size"><?php _e('Max Person Per Table',PLUGIN_NAME) ?></label>
        <input type="text" id="one_max_person_per_table" name="resto_all_setting[one_max_person_per_table]" 
        	value="<?php echo $options['one_max_person_per_table'] ?>" />
       	<br class="float-none height_20px" />
    </div>
    
    
    <h2 class="head-parent icon-many-size">
		<?php _e('Many Size',PLUGIN_NAME); ?>
   		<i class="fa fa-caret-down"></i>
    	<i class="fa fa-caret-up"></i>   
    </h2>
    <div class="head-parent-content">
        <br />
        <?php //= 4.	MANY ( SIZE ) TYPE OF TABLE  ?>
        <label for="table_size"><?php _e('Type of table',PLUGIN_NAME) ?></label>
        <div class="common-container">
            <textarea class="no-margin email-medium-field" id="many_type_of_table" name="resto_all_setting[many_type_of_table]" rows="5" cols="31"><?php echo $options['many_type_of_table'] ?></textarea>
            <p class="no-margin"><strong><?php _e('Format : ',PLUGIN_NAME); ?></strong><?php _e('tablename( maximum person per table )',PLUGIN_NAME); ?></p>
            <p class="no-margin"><strong><?php _e('Examples',PLUGIN_NAME); ?></strong></p>
            <span><?php _e('smaller(4)',PLUGIN_NAME); ?> , </span>	
            <span><?php _e('medium(8)',PLUGIN_NAME); ?> , </span>
            <span><?php _e('larger(12)',PLUGIN_NAME); ?></span>
            <input type="button" id="display_table_field_button" value="Display and Refresh Field" />
        </div>
       
    	<div style="clear: both;"></div>
    
    	<div id="many_table_members">
            <?php
            if( $options['many_type_of_table'] != '' ){
                $many_table_type = explode(',',str_replace(' ','',$options['many_type_of_table']) );
                global $many_table_id;
                if( $many_table_type != '' ){
                    foreach( $many_table_type as $val){
                        $table_type = preg_match('/(.+)(\()([0-9]+)(\))/', $val, $match_table);
    
                        if( count($match_table) > 0 ){
                            $id = $match_table['1'] . '_table'; 
                            ?>
                            <label for="early_bookings" ><?php echo __('Total',PLUGIN_NAME) . ' ' . $match_table['1'] . ' ' .__('table',PLUGIN_NAME);  ?></label>
                            <input type="text" id="<?php echo $match_table['1'] . '_table'?>" name="resto_all_setting[<?php echo $match_table['1'] . __('_table',PLUGIN_NAME); ?>]" value="<?php echo $options[$id] ?>" />
                            
                            <p class="float-none"></p>
                            <?php 	               
                        }
                                        
                    } // foreach( $many_table_type as $val){
                                    
                } // if( $many_table_type != '' ){
                                
            } // if( $options['many_type_of_table'] != '' ){
            ?>
            <div style="clear: both;"></div>
        </div>
 	</div>      