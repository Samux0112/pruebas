<?php
class ContingenciasModel extends Query{
    public function __construct() {
        parent::__construct();
    }
    public function getDte($id)
    {
        $sql = "SELECT dte FROM dtes WHERE id = $id";
        return $this->select($sql);
    }
	
	    public function getDteSujeto($id)
    {
        $sql = "SELECT dte FROM dtesSujetoExcluido WHERE id = $id";
        return $this->select($sql);
    }
	
	 public function updateContingencia($idVenta)
    {
        $sql = "UPDATE contingencias SET enviada = ? WHERE id = ?";
        $array = array('true', $idVenta);
        return $this->save($sql, $array);
    }
	
		 public function updateDte($dte, $id)
    {
        $sql = "UPDATE dtes SET dte = ? WHERE id = ?";
        $array = array($dte, $id);
        return $this->save($sql, $array);
    }
	
	public function updateContingenciaSujeto($idVenta)
    {
        $sql = "UPDATE contingenciasSujeto SET enviada = ? WHERE id = ?";
        $array = array('true', $idVenta);
        return $this->save($sql, $array);
    }
	
		 public function updateDteSujeto($dte, $id)
    {
        $sql = "UPDATE dtesSujetoExcluido SET dte = ? WHERE id = ?";
        $array = array($dte, $id);
        return $this->save($sql, $array);
    }
	
}


?>