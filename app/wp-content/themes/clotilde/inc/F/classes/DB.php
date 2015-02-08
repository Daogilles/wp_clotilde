<?php
namespace F;

class DB {

    /**
     * @var \wpdb
     */
    public $wpdb;
 
   /**
    * Instance de la classe SPDO
    *
    * @var \F\DB
    * @access private
    * @static
    */ 
    private static $instance = null;
  
    private function __construct() {
       global $wpdb;
       $this->wpdb = $wpdb;
    }

    /**
     *
     * @access public
     * @static
     * @param void
     * @return \F\DB $instance
     */
    public static function getInstance() {
        if (!self::$instance instanceof DB) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function insert($tablename, $data) {
        $fields = array();
        $values = array();
        foreach($data as $field => $value) {
            $fields[] = $field;
            if(empty($value) && $value !== '0') {
                $values[] = 'NULL';
            } else {
                if(is_array($value) || is_object($value)) {
                    $values[] = "'".addslashes(serialize($value))."'";
                } else {
                    $values[] = is_string($value) ? "'".addslashes($value)."'" : $value;
                }
            }
        }

        $sql = 'INSERT INTO '.$tablename.' ('.implode(', ', $fields).') VALUES ('.implode(', ', $values).')';
        $return = $this->exec($sql);
        if(!$return) {
            echo $sql;
        }
        return $return;
    }

    /**
     * Return a properly formed order by clause based on the fields and order table
     *
     * Example
     * <code>
     * $fields = array('myfield1','myfield2');
     * $order = array('ASC','DESC');
     * $dborder = DB::getInstance()->getOrder($fields, $order);
     * </code>
     *
     * @param array $fields, optional the fields used for ordering
     * @param array $order, optional the orders used for ordering, for example array('ASC','DESC')
     * @return string
     */
    public function getOrder($fields=array(),$order=array()){
        $orderBy='';


        if(!empty($fields) && !empty($order)){
            $orderBy = "\n ORDER BY ";
            foreach($fields as $key=>$fval){
                $orderByfrags[$fval] = $fval.' '.$order[$key];
            }
            $orderBy.= implode(',',$orderByfrags);
        }
        return $orderBy;
    }

    public function delete($tableName, $where) {
        $fields = array();
        foreach($where as $key => $value) {
            $fields[] = $key.' = '.$value;
        }
        return $this->exec('DELETE FROM '.DB_PREFIX.$tableName.' WHERE '.implode(' AND ', $fields));
    }

    /**
     * Limits the records to a certain range and papge
     *
     * Example
     * <code>
     * $dbpaged = DB::getInstance()->getPaged(1, 10);
     * </code>
     *
     * @param integer $page, optional the page num
     * @param integer $perPage, optional how much you want per page
     * @return string
     */
    public function getPaged($page=1,$perPage=0){
        $limit='';
        if(!empty($page)){
            $offset=($page-1)*$perPage;
            $limit.="\n LIMIT ".(int)$offset.','.(int)$perPage;
        }
        return $limit;
    }

    public function update($tablename, $data, $where) {
        $datas = array();
        foreach($data as $key => $value) {
            if(empty($value) && $value !== '0') {
                $value = 'NULL';
            } else {
                if(is_array($value) || is_object($value)) {
                    $value = "'".addslashes(serialize($value))."'";
                } else {
                    $value = is_string($value) ? "'".addslashes($value)."'" : $value;
                }
            }

            $datas[] = $key.' = '.$value;
        }


        if(empty($datas)) return;
        $sql = "UPDATE ".$tablename." SET ".implode(', ', $datas)." WHERE ".$where;
        $return = $this->exec($sql);
        //trace($sql);
        return $return;
    }

    public function query($query) {
        return $this->wpdb->query($query);
    }
    
    public function fetch($query) {
        return $this->wpdb->get_results($query, ARRAY_A);
    }
    
    public function exec($query) {
        return $this->wpdb->query($query);
    }

    public function lastInsertId() {
        return $this->wpdb->insert_id;
    }

}

/**
 * DbField class
 *
 */
class DbField {

    /**
     * DB field name
     * @var string
     */
    public $field;
    /**
     * DB field libelle, for translation
     * @var string
     */
    public $libelle;
    /**
     * DB field database type
     * @var string
     */
    public $type;
    /**
     * Is the field required
     * @var bool
     */
    public $required;
    /**
     * Object attribute
     * @var array
     */
    public $obj_attr;
    /**
     * fieldset name
     * @var string
     */
    public $fieldset;

}
