<?php
/*
    *Lionnel nawej kayembe
    *Gestion des api
*/
namespace Rtgroup\PayrollApi;

use Rtgroup\Dbconnect\Dbconfig;
use Rtgroup\Dbconnect\Dbconnect;
use Rtgroup\HttpRouter\HttpRequest;
use Rtgroup\HttpRouter\DataLoader;



class Api
{
    use DataLoader;

    public function __construct()
    {
        $dbConfig=new Dbconfig("localhost","root","","milleniumpayroll");
        $this->dbconnect=new Dbconnect($dbConfig); 
     //   $classtest= new tests();

    }
    public function captured($url, $httpRequest)
    {
        $this->httpRequest=$httpRequest;
        
        //new tests(); $classTest->bonjour()
       

        switch($url)
        {
            case "api/listAgent":       $this->listAgent(); break;
            case "api/viewDeviceStatus": $this->viewDeviceStatus(); break;
            case "api/rapportHeureSupp": $this->rapportHeureSupp(); break;
            

        }
    }
    public function listAgent(){
        $getData=$this->urlTraitement("http://127.0.0.1/personnel/api/employees/","");
        $this->loadData("reponse",array("status"=>"success","data"=>$getData));

    }
    public function viewDeviceStatus(){
        $getData=$this->urlTraitement("http://127.0.0.1/base/dashboard/offline_device/?state=2&areas=","");
        $this->loadData("reponse",array("status"=>"success","data"=>$getData));
    }
    public function rapportHeureSupp(){
        $getData=$this->urlTraitement("http://127.0.0.1/base/dashboard/offline_device/?state=2&areas=","");
        $this->loadData("reponse",array("status"=>"success","data"=>$getData));
    }
    public function userStatut(){
        $getData=$this->urlTraitement("http://127.0.0.1/personnel/api/employees/","");
        $this->loadData("reponse",array("status"=>"success","data"=>$getData));
    }
    public function carteTempsTotal(){
       /* $getData=$this->urlTraitement("http://127.0.0.1/personnel/api/employees/","");
        $this->loadData("reponse",array("status"=>"success","data"=>$getData));*/
    }
    
    public function createAgent(){

        if(!$this->httpRequest->isPost())
        {
            throw new Exception("La requet doit etre en post.",404);
        }
        HttpRequest::checkRequiredData("emp_code",true);
        HttpRequest::checkRequiredData("department",true);
      //  HttpRequest::checkRequiredData("area",true);
        HttpRequest::checkRequiredData("first_name",true);
        HttpRequest::checkRequiredData("last_name",true);
        HttpRequest::checkRequiredData("nickname",true);
        HttpRequest::checkRequiredData("gender",true);
        HttpRequest::checkRequiredData("mobile",true);
        HttpRequest::checkRequiredData("contact_tel",true);
        HttpRequest::checkRequiredData("office_tel",true);
        HttpRequest::checkRequiredData("birthday",true);
        HttpRequest::checkRequiredData("national",true);
        HttpRequest::checkRequiredData("address",true);
        HttpRequest::checkRequiredData("email",true);
        HttpRequest::checkRequiredData("enroll_sn",true);

        $postData=$this->urlTraitement("http://127.0.0.1/personnel/api/employees/",$_POST);
        if($postData){

            $data["emp_code"]   =$_POST['emp_code'];
            $data["department"] =$_POST[''];
          //  $data["area"]
            $data["first_name"] =$_POST[''];
            $data["last_name"]  =$_POST[''];
            $data["nickname"]   =$_POST[''];
            $data["gender"]     =$_POST[''];
            $data["mobile"]     =$_POST[''];
            $data["contact_tel"]=$_POST[''];
            $data["office_tel"] =$_POST[''];
            $data["birthday"]   =$_POST[''];
            $data["national"]   =$_POST[''];
            $data["address"]    =$_POST[''];
            $data["email"]      =$_POST[''];
            $data["enroll_sn"]  =$_POST[''];

            $idAgent=$this->dbconnect->insert($data);

            $this->dbconnect->where("agent_id","=",$idAgent);
            $agentData=$this->dbconnect->select();
            $agentData=$agentData[0];
    
            $this->loadData("reponse",array("status"=>"success","data"=>$agentData));
        }

    }

    public function urlTraitement($url,$_POST){
        /*
        * traitement des urls
        */
        if($_POST){
            $options=array(
                'http' => array(
                'method'  => 'POST',
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'content' => http_build_query($_POST)
                ),
            );
            $context = stream_context_create($options);
            $resultat= file_get_contents($url,false,$context);
            $resultat= json_decode(($resultat));
        }else{
            //si les données en poste n'existe pas
            $dataUrl = file_get_contents($url);
            $resultat= json_decode($dataUrl, true);
        }
        return $resultat;    

    }
    

}