<?php

class Connectapi {

    public function cons($db_name) {
         $CI = & get_instance();
        $CI->db->db_select($db_name);
    }


}
