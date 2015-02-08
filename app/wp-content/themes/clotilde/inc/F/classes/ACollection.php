<?php

namespace F;

abstract class ACollection {
    /**
     * @var array
     */
    public $models = array();

    /**
     * @var string
     */
    protected $modelName = '';

    /**
     * @var string
     */
    public $tableName = '';

    /**
     * @param string $modelName
     * @param string $tableName
     */
    public function __construct($modelName, $tableName) {
        $this->modelName = $modelName;
        $this->tableName = DB_PREFIX.$tableName;
    }

    /**
     * Clone
     */
    public function __clone(){
        parent::__clone();
        if(!empty($this->models)){ foreach($this->models as $i=>$m){
            if(is_object($this->models[$i])){ $this->models[$i] = clone $this->models[$i]; }
        }}
    }


    public function save() {
        foreach($this->models as $model) {
            $model->save();
        }
    }

    /**
     * Lists elements of the table $this->tableName. It sends a query to the database and returns an array of corresponding elements.
     * The different parameters allow you to build a custom dynamic query to return different results based on your needs.
     *
     * Example
     * <code>
     * $collection = new F\ACollection('mytable','mymodel');
     * $filters = array(array('col'=>'object_adresse','cond'=>'=','val'=>'test adresse'));
     * $records = $collection->get(null,null,array('object_id'),array('desc'),$filters);
     * </code>
     *
     * @param int $page (optional), used for pagination. If you have paginated results, set here the page number you want to return. By default, set to page 1.
     * @param int $perpage (optional), used for pagination. If you have paginated results, set here how many results you want to return. By default, set to 0, so the limit is ignored it will return all results without limit.
     * @param array $fields (optional), used for ordering. The list of fields in this array will be used to create the ORDER BY condition of the query. It specifies the FIELDS on which we want to base the order. By default, it's ordered by diapo_id.
     * @param array $order (optional), used for ordering. The list of fields in this array will be used to create the ORDER BY condition of the query. It specifies the DIRECTION on which we want to base the order. By default, it's ordered by DESC. The number of elements in the $order array, must be the same than the $fields array
     * @param array $filters (optional), an array of filters to amend the query. Example of filter => see the NoewpDiaporama::getQuery method definition and comments
     * @param string $customQuery (optional), you can pass a custom sql query to override the default one
     * @return ACollection
     */
    public function fetch($page = 1, $perpage = 0, $fields = array(), $order = array(), $filters = array(),$customQuery='') {
        $dbRecords = $this->getDbRecords($page, $perpage, $fields, $order, $filters,$customQuery);
        $modelName = __NAMESPACE__.'\\data\\'.$this->modelName;
        if (!empty($dbRecords)) {
            foreach ($dbRecords as $i => $dbr) {
                $tmodel = new $modelName();

                if(is_array($tmodel->mainfield)) {
                    $index = '';
                    $sep = '';
                    foreach($tmodel->mainfield as $field) {
                        $index .= $sep.$dbr[$field];
                        $sep = '#';
                    }
                } else {
                    $index = $dbr[$tmodel->mainfieldWithoutTable()];
                }


                if(!$model = $this->get($index)){
                    $model = new $modelName();
                }
                $model->mapFromDb($dbr);
                $index = $model->mainfieldval>0 ? $model->mainfieldval : $i;
                //echo '<br />Adding model to '.get_called_class().' col at index '.$index;
                $this->add($model, $index);
            }
        }
        return $this;
    }

    /**
     * Build a dynamic query for the table $this->tableName
     *
     * Example
     * <code>
     * $collection = new F\ACollection('mytable','mymodel');
     * $filters = array(array('col'=>'object_adresse','cond'=>'=','val'=>'test adresse'));
     * $query = $collection->getQuery($filters);
     * </code>
     *
     * @param array $filters Array of filters to amend the query. Example of filter => $filter=array(1=>array("col"=>"diapo_id","cond"=>"=","val"=>1 )); is equivalent to WHERE diapo_id = 1
     * @param string $sql, you can specify the query to only benefit from the filters, useful for overiding the method in a child model.
     * @return string $sql, the computed query
     */
    public function getQuery($filters = array(),$sql='') {
        if(empty($sql)){
            $sql = "
                SELECT *
                FROM " . $this->tableName . "
                WHERE 1";
        }

        return $sql.$this->constructSQLFilters($filters);
    }

    /**
     * construct WHERE conditions for the given filters
     *
     * @param array $filters Array of filters to amend the query. Example of filter => $filter=array(1=>array("col"=>"diapo_id","cond"=>"=","val"=>1 )); is equivalent to WHERE diapo_id = 1
     * @return string $sql, the sql condition
     */
    protected function constructSQLFilters($filters) {
        //Easily add dynamic where filtering
        $sql = '';
        if (!empty($filters)) {
            foreach ($filters as $f) {
                $liant = !empty($f['liant']) ? $f['liant'] : 'AND';
                //Each $f entry is made of 3 field (col / cond / val)
                $val = $f["val"];
                $sql.=" $liant " . $f["col"] . " " . $f["cond"] . " ";
                if($f["cond"] == 'IN'){ $sql.= !empty($val) ? $val : ''; }
                else { $sql.= !empty($val) || $val === '0' ? "'" . $val . "'" : ''; }
            }
        }
        return $sql;
    }

    /**
     * Lists elements of the table $this->tableName. It sends a query to the database and returns an array of corresponding elements.
     * The different parameters allow you to build a custom dynamic query to return different results based on your needs.
     *
     * Example
     * <code>
     * $collection = new F\ACollection('mytable','mymodel');
     * $filters = array(array('col'=>'object_adresse','cond'=>'=','val'=>'test adresse'));
     * $records = $collection->getDbRecords(null,null,array('object_id'),array('desc'),$filters);
     * </code>
     *
     * @param int $page (optional), used for pagination. If you have paginated results, set here the page number you want to return. By default, set to page 1.
     * @param int $perpage (optional), used for pagination. If you have paginated results, set here how many results you want to return. By default, set to 0, so the limit is ignored it will return all results without limit.
     * @param array $fields (optional), used for ordering. The list of fields in this array will be used to create the ORDER BY condition of the query. It specifies the FIELDS on which we want to base the order. By default, it's ordered by diapo_id.
     * @param array $order (optional), used for ordering. The list of fields in this array will be used to create the ORDER BY condition of the query. It specifies the DIRECTION on which we want to base the order. By default, it's ordered by DESC. The number of elements in the $order array, must be the same than the $fields array
     * @param array $filters (optional), an array of filters to amend the query. Example of filter => see the NoewpDiaporama::getQuery method definition and comments
     * @param string $customQuery (optional), you can pass a custom sql query to override the default one
     * @return array $result, an array containing the records returned by the sql query
     */
    public function getDbRecords($page = 1, $perpage = 0, $fields = array(), $order = array(), $filters = array(),$customQuery='') {
        $db = DB::getInstance();
        $sql = $this->getQuery($filters,$customQuery);
        //Add order by
        if (!empty($fields) && !empty($order)) {
            $sql.=$db->getOrder($fields, $order);
        }
        //Limit / offset pagination
        if (!empty($perpage)) {
            $sql.=$db->getPaged($page, $perpage);
        }
        //trace($sql);
        $result = $db->fetch($sql);
        trace($result);
        return $result;
    }

    /**
     * Fetch Ids only
     * @param bool $idInIndex true to put ids in array index, so you can use isset($result[$id]) to check presence of an id (much faster)
     * @return array
     */
    public function fetchIds($idInIndex = false){
        $db = DB::getInstance();
        $testmodel = new $this->_model();
        $sql = "
            SELECT ".$testmodel->mainfield."
            FROM " . $this->tableName . "
            WHERE 1";
        $result = $db->fetch($sql);

        $resultFlat = array();
        foreach($result as $r) {
            $mainfield = $testmodel->mainfieldWithoutTable();
            if($idInIndex) $resultFlat[(string)$r[$mainfield]] = 1;
            else           $resultFlat[] = $r[$mainfield];
        }

        return $resultFlat;
    }
    
    /**
     * Add a model to the models
     *
     * Example
     * <code>
     * $this->add($model, $i);
     * </code>
     *
     * @see ACollection::get();
     * @param AModel $model
     * @param type $i, the index used to add the AModel in the collection
     * @return \F\ACollection
     */
    public function add($model, $i = null) {
        $models = $this->models;
        //If no index given
        if(empty($i)){
            //No index provided and no mainfieldval, we just push it to the collection
            array_push($models, $model);
        } else {
            if(is_array($i)){ $i=implode('#', $i); }
            //Insert at given index
            $models[$i] = $model;
        }
        $this->models = $models;
        return $this;
    }

    /**
     * Get Model from collection
     * @param mixed $i, the index at which we want to get the object
     * @return \F\AModel
     */
    public function get($i){
        if(is_array($i)){ $i=implode('#', $i); }
        $models = $this->models;
        if(isset($models[$i])){ return($models[$i]); }
        else{ return null; }
    }

    /**
     * Check if a collection contains a model in $i
     * @param type $i
     * @return type
     */
    public function contains($i){
        if(is_array($i)){ $i=implode('#', $i); }
        return !($this->get($i)===null);
    }

    /**
     * Remove model from collection at index $i;
     * @param type $i, the index at which we want to remove the object
     * @return \F\ACollection
     */
    public function remove($i){
        if(is_array($i)){ $i=implode('#', $i); }
        $models = $this->models;
        if(isset($models[$i])){ unset($models[$i]); }
        $this->models = $models;
        return $this;
    }


    /**
     * Extend a collection with a new one
     * If model is in new col only, add
     * If model is on both, update
     * You can pass several collections to this method
     *
     * Example
     * <code>
     * $this->extend($anothercol);
     * $this->extend($anothercol1 $anothercol2, ...);
     * </code>
     *
     * @return \F\ACollection
     */
    public function extend(){
        $toextends = func_get_args();
        if(!empty($toextends)){ foreach($toextends as $toextend){
            if(!empty($toextend)){ foreach($toextend->models as $i=>$toex){
                $this->add($toex, $i);
            }}
        }}
        return $this;
    }

    /**
     * Find a model from the collection where the model has an attribute called $field and this attribute has a value of $value
     *
     * Example
     * <code>
     * $langs = new LangCollection();
     * $langs->fetch();
     * $findlangresult = $langs->where('lang_code','fr);
     * </code>
     * @todo permettre differentes conditions etc.
     * @param string $field
     * @param mixed $value
     * @return array
     */
    public function where($field,$value){
        $result = array();
        if(!empty($this->models)){ foreach($this->models as $i=>$m){
            foreach($m as $attr=>$val){
                if($field==$attr && $value==$val){;
                    $result[]=array('index'=>$i,'model'=>$m);
                }
            }
        }}

        return $result;
    }

    /**
     * Find the first model from the collection matching $field = $value
     *
     * Example
     * <code>
     * $langs = new LangCollection();
     * $langs->fetch();
     * $findlangresult = $langs->findWhere('lang_code','fr);
     * </code>
     * @param string $field
     * @param mixed $value
     * @return array
     */
    public function findWhere($field, $value) {
        $result = $this->where($field, $value);
        if(!empty($result)) {
            return reset($result);
        } else {
            return null;
        }
    }

    public function jsonify() {
        $json = array();

        foreach($this->models as $model) {
            $json[] = $model->jsonify();
        }

        return $json;
    }
} 