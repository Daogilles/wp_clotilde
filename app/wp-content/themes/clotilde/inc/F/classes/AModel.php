<?php

namespace F;


abstract class AModel {

    /**
     * The table name to use with the object's requests
     * @var string
     */
    protected $_table;
    /**
     * The mainfield name to use with get, delete ...
     * @var string
     */
    public $mainfield;
    /**
     * The val of the $mainfield
     * @var int
     */
    public $mainfieldval;


    public function __construct($mainfieldval = 0, $load=true, $table='',$mainfield='') {
        //Configure DB info to enable AModel methods
        $this->_table = DB_PREFIX.$table;
        $this->mainfield = $mainfield;
        $this->mainfieldval = $mainfieldval;

        if(!is_array($mainfieldval) && !empty($mainfield)) {
            $fieldVar = $this->mainfieldWithoutTable();
            $this->{$fieldVar} = $mainfieldval;
        }

        if($load && !empty($mainfieldval)){
            $this->get();
        }
        return $this;
    }

    public function get($sql=''){
        if(!empty($this->_table) && !empty($this->mainfield)){
            if(empty($sql)){ $sql = 'SELECT * FROM '.$this->_table.' WHERE '.$this->getRowIdentifier().' LIMIT 0,1'; }
            $result = DB::getInstance()->fetch($sql);
            if (!empty($result)) {
                foreach ($result as $i => $dbr) {
                    $this->mapFromDb($dbr);
                }
            }
        }
        return $this;
    }

    /**
     * Build an identifier based on row primary key, and primary key val
     * @return string $identifier, row identifier
     */
    public function getRowIdentifier(){
        $identifier = $this->mainfield.' = '.$this->mainfieldval;
        return $identifier;
    }

    /**
     * Populate an object with an array of data
     * Works only with the entries that are present in the array.
     * If no array key for an object key, it doesn't override the object key
     *
     * Example:
     * <code>
     * $data = array('field1'=>'value1','field2'=>'value2');
     * $myObject = new \F\AModel();
     * $myObject->populate($data);
     * </code>
     *
     * @param array $data, the values to map to the object
     * @return \F\AModel
     */
    public function populate($data){
        if(!empty($data)){ foreach($data as $key=>$val){
            $this->$key = $val;
        }}
        return $this;
    }

    /**
     * Map a set of data from the database, to the object
     * Example:
     * <code>
     * //See method get
     * $sql = 'SELECT * FROM '.$this->_table.' WHERE '.$this->getRowIdentifier().' LIMIT 0,1';
     * $result = reset(DB::getInstance()->query($sql,\PDO::FETCH_ASSOC));
     * $this->mapFromDb($result);
     * </code>
     * @see \F\AModel::get();
     * @param array $data, the set of data returned from the database
     * @param array $dbfields, the $dbfields can be passed directly to avoid a call to $this->getFields
     * @return \F\AModel
     */
    public function mapFromDb($data,$dbfields=array()){
        if(empty($dbfields)){ $dbfields = $this->getFields(); }
        if(!empty($dbfields)){ foreach($dbfields as $of=>$dbf){
            $this->$of = ($dbf->type=='int') ? (int)$data[$dbf->field] : $data[$dbf->field];
        }}
        if(!empty($data[$this->mainfieldWithoutTable()])){ $this->mainfieldval = $data[$this->mainfieldWithoutTable()]; }
        return $this;
    }

    /**
     * Get the database data from an object
     *
     * Example:
     * <code>
     * $data = $myObject->mapToDb();
     * </code>
     * @see \F\AModel::insert();
     * @return array $data, the array of data ready to interact with the database
     */
    public function mapToDb(){
        $dbfields = $this->getFields();
        $data = array();
        //for each db field in the model, we build an array of data to send to the database
        if(!empty($dbfields)){ foreach($dbfields as $of=>$dbf){
            //echo '<br />'.$of.'=>'.$this->$of.' / '; var_dump($dbf->required);
            if(!empty($dbf->required) && $dbf->required=='true' && empty($this->$of)){
                //If the field is required and we don't have a value => do nothing
            } else {
                //The value is allowed to be overidden
                $data[$dbf->field] = $this->$of;
            }
        }}
        //Extra check main field, to avoid to pass a '0' to the auto increment fields
        if(!is_array($this->mainfieldWithoutTable()) && array_key_exists($this->mainfieldWithoutTable(), $data) && empty($data[$this->mainfieldWithoutTable()])){
            unset($data[$this->mainfieldWithoutTable()]);
        }
        return $data;
    }

    /**
     * Saves an object to the database (insert, update)
     *
     * Example:
     * <code>
     * //Simple call
     * $result = $myObject->save();
     *
     * //Full example
     * $data = array('field1'=>'value1','field2'=>'value2');
     * $myObject = new\F\AModel();
     * $result = $myObject->populate($data)->save();
     * </code>
     *
     * @return AModel $this
     */
    public function save(){
        if(!empty($this->_table) && !empty($this->mainfield)){

            //Before save event?
            //Like Serialize attributes that are arrays or objects
            $this->beforeSave();

            //Save in db
            $tmpMainFieldVal = $this->mainfieldval;
            if(empty($tmpMainFieldVal) || (is_array($this->mainfieldval) && empty($this->mainfieldval)) ){    //Creation
                $this->insert();
            } else {    //Update
                $this->update();
            }


            //After save event?
            $this->afterSave();

            return $this;
        }
    }

    /**
     * Inserts an AModel into the database
     *
     * Example:
     * <code>
     * //Simple call
     * $result = $myObject->insert();
     *
     * //Full example
     * $data = array('field1'=>'value1','field2'=>'value2');
     * $myObject = new\F\AModel();
     * $result = $myObject->populate($data)->insert();
     * </code>
     *
     * @return boolean
     */
    public function insert(){
        $res = DB::getInstance()->insert($this->_table,$this->mapToDb());
        if($res) {
            $id = DB::getInstance()->lastInsertId();
            $this->{$this->mainfield} = $id;
            $this->mainfieldval = $id;
        }
        return $res;
    }

    /**
    * Treatments to process before saving the object
    * For example, making sure attributes that are arrays or objects are serialized
    * @todo Dispatch a beforeSave event
    * @return \F\AModel
    */
    public function beforeSave(){
        $class_vars = $this->getAttributes(); //get all attributes
        if(!empty($class_vars)){ foreach($class_vars as $attr=>$val){
            $testval = $this->$attr;
            if(is_array($testval)){ //Check if object or array but doesn't inherit from ACollection
                $this->$attr = serialize($this->$attr);
            }
        }}
        return $this;
    }

    /**
     * Treatments to process after saving the object
     * For example, uploading medias... Nothing by default, will need to be overidden
     */
    public function afterSave(){

    }

    /**
     * Updates an AModel into the database
     *
     * Example:
     * <code>
     * //Simple call
     * $result = $myObject->update();
     *
     * //Full example
     * $data = array('field1'=>'value1','field2'=>'value2');
     * $myObject = new\F\AModel();
     * $result = $myObject->populate($data)->update();
     * </code>
     *
     * @return DBresult
     */
    public function update(){
        if(!empty($this->_table) && !empty($this->mainfield) && !empty($this->mainfieldval)){
            $res = DB::getInstance()->update($this->_table,$this->mapToDb(),$this->getRowIdentifier());
            return $res;
        }
    }

    /**
     * Return fields association
     *
     * Example:
     * <code>
     * $myObject = new\F\AModel(5);
     * $fields = $myObject->getFields();
     * </code>
     * @param string $annotationString, the annotation name use to get the infos
     * @return array $dbfields, the database fields association as array of DbField
     */
    public static function getFields($annotationString='dbFieldInfo'){
        $calledClass = get_called_class();
        $mz = AttributeMutualizer::getInstance();
        if(!$dbfields = $mz->getMutualized($calledClass,$annotationString)){
            $dbfields=array();

            //Get Class attributes annotations
            $c = new \ReflectionClass($calledClass);
            $annotation = new \F\AnnotationsParser($c);
            $dbfieldsarray = $annotation->parse($annotationString);

            //No annotations have been found
            if(empty($dbfieldsarray) && $annotationString === 'dbFieldInfo'){
                //Direct mapping between the object attribute name and the db field name
                $class_vars = get_class_vars($calledClass);
                foreach ($class_vars as $name => $value) {
                    $dbfieldsarray[$name] = array('field'=>$name,'type'=>'varchar','required'=>false);
                }
                $parent_vars = get_class_vars(__NAMESPACE__.'\AModel');
                if(!empty($parent_vars)){ foreach($parent_vars as $pname=>$pval){
                    if(!empty($dbfieldsarray[$pname])) unset($dbfieldsarray[$pname]);
                }}
            }

            //Conversion from array to DbField Object
            if(!empty($dbfieldsarray)){ foreach($dbfieldsarray as $i=>$dbf){
                $dbfo = new DbField();
                $dbfo->obj_attr = $i;
                foreach(array('field', 'libelle', 'type', 'required', 'fieldset') as $type) {
                    if(!empty($dbf[$type])) {
                        $dbfo->{$type} = $dbf[$type];
                    }
                }
                $dbfields[$i] = $dbfo;
            }}

            $mz->setMutualized($calledClass,$annotationString,$dbfields);
        }
        return $dbfields;
    }

    /**
     * Extract table name from mainfield
     * @return string
     */
    public function mainfieldWithoutTable() {
        $field = $this->mainfield;
        if(is_array($field)) return $field;
        $field = explode('.', $field);
        return end($field);
    }

    /**
     * Get Class attributes
     * @return array
     */
    public function getAttributes(){
        //Get object attributes
        return array();
        $class_vars = parent::getAttributes();

        //Exclude parent attributes so we compare only the objet's attributes and values
        $parent_vars = array_keys(get_class_vars(__NAMESPACE__.'\AModel'));
        if(!empty($parent_vars)){ foreach($parent_vars as $pname){
            if(array_key_exists($pname,$class_vars)) unset($class_vars[$pname]);
        }}
        return $class_vars;
    }

    public function jsonify() {
        $json = array();
        $fields  = $this->getFields();
        foreach($fields as $key => $field) {
            $json[$key] = $this->{$key};
        }
        return $json;
    }
} 