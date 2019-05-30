<?php
defined('IN_MET') or exit('No permission');//所有文件都是已这句话开头，保证系统单入口。



load::sys_class('admin');//包含后台基类，“.class.php”可以省略。


class replace extends admin {//继承后台基类。类名称要与文件名一致
    public function __construct() {
        parent::__construct();//如果重写了初始化方法,一定要调用父类的初始化函数。
    }
    	
	public function moduledb($module){
		switch($module){
			case 1:
				$moduledb='column';
				break;
			case 2:
				$moduledb='news';
				break;
			case 3:
				$moduledb='product';
				break;
			case 4:
				$moduledb='download';
				break;
			case 5:
				$moduledb='img';
				break;
			case 6:
				$moduledb='job';
				break;
			case 100:
				$moduledb='product';
				break;
			case 101:
				$moduledb='img';
				break;
		}
		return $moduledb;
    }	
	

    public function domodify(){
		
		global $_M;
	
		$action=$_GET['action'];
		$class1=$_GET['class1'];
	    $class2=$_GET['class2'];
		$class3=$_GET['class3'];
		
		if($action=="modify"){  //获取替换的栏目ID
			$re='';
				if($class1==-2){
					$list[0]=array("SELECT id FROM {$_M['table']['column']} WHERE module='1' and lang='$_M[lang]'",'1');
					$list[1]=array("SELECT id FROM {$_M['table']['news']} WHERE lang='$_M[lang]'",'2');
					$list[2]=array("SELECT id FROM {$_M['table']['product']} WHERE lang='$_M[lang]'",'3');
					$list[3]=array("SELECT id FROM {$_M['table']['download']} WHERE lang='$_M[lang]'",'4');
					$list[4]=array("SELECT id FROM {$_M['table']['img']} WHERE lang='$_M[lang]'",'5');
					foreach($list as $listkey=>$listval){
						$rearray=DB::get_all($listval[0]);
						foreach($rearray as $key=>$val){
							$re.=$val['id'].'-'.$listval[1].'|';
						}
					}

				}
				else{
					$class=$class3?$class3:($class2?$class2:$class1);
					$remark=DB::get_one("SELECT * FROM {$_M['table']['column']} WHERE id='$class'");
					//$table=moduledb($remark['module']);
					$table=$this->moduledb($remark['module']);
					if($remark['module']!='1'){
						$resql="class1='$class1'";
						$resql.=$class2?" and class2='$class2'":"";
						$resql.=$class3?" and class3='$class3'":"";
						$renow=DB::get_all("SELECT * FROM {$_M['table'][$table]} WHERE $resql and (recycle='0' or recycle='-1')");
						$re='';
						foreach($renow as $key=>$val){
							$re.=$val['id'].'-'.$remark['module'].'|';
						}
					}else{
						if($remark[classtype]!=3){
							$classnext=DB::get_all("SELECT * FROM {$_M['table']['column']} WHERE bigclass='$remark[id]' and module=1");
							foreach($classnext as $key=>$val){
								$re.=$val['id'].'-'.'1|';
							}
							if($remark[classtype]==1){
								foreach($classnext as $key1=>$val1){
									$classnext2=DB::get_all("SELECT * FROM {$_M['table']['column']} WHERE bigclass='$val1[id]' and module=1");
									foreach($classnext2 as $key2=>$val2){
										$re.=$val2['id'].'-'.'1|';
									}
								}
							}
						}
						$re.=$class.'-'.'1|';
					}	
				}
			echo $re;
			die();
			}
			
			if($action=="modifyid"){
				
				$id=$_GET['id'];
		        $type=$_GET['type'];
		        $table=$_GET['table'];
		        $retext=$_GET['retext'];
		        $retexted=$_GET['retexted'];
			
				if($id){
					$num=$table;
					//$table=moduledb($table);
					$table=$this->moduledb($table);
					$renow=DB::get_one("SELECT * FROM {$_M['table'][$table]} WHERE id='$id'");
					$content[0]=$renow[content];
					$content[1]=$renow[content1];
					$content[2]=$renow[content2];
					$content[3]=$renow[content3];
					$content[4]=$renow[content4];
					$updateflag=0;
					foreach($content as $contentkey=>$contentval){
						if($contentval){
							$tmp1 = explode("<",$contentval);
							$i=0;
							$flag=0;
							foreach($tmp1 as $key=>$val){
								$tmp2=explode(">",$val);
								switch($type){
									case 1:
										if(strcasecmp(substr(trim($tmp2[0]),0,3),'img')==0){
											$tmp2[0]=str_ireplace($retexted,$retext,$tmp2[0]);
											$tmp1[$i]=implode(">",$tmp2);
											$flag=1;
										}
									break;
									case 2:
										if(strcasecmp(substr(trim($tmp2[0]),0,1),'a')==0){
											$tmp2[0]=str_ireplace($retexted,$retext,$tmp2[0]);
											$tmp1[$i]=implode(">",$tmp2);
											$flag=1;
										}
									break;
									case 3:
										if($tmp2[1])$tmp2[1]=str_ireplace($retexted,$retext,$tmp2[1]);
										$tmp1[$i]=implode(">",$tmp2);
										$flag=1;
									break;
								}
								$i++;
							}
							if($flag==1){
								$updateflag=1;
								$content[$contentkey]=implode("<",$tmp1);
							}
						}
					}
					if($updateflag){
						if($num==3||$num==5)$sql=",content1='$content[1]',content2='$content[2]',content3='$content[3]',content4='$content[4]'";
						$query="UPDATE {$_M['table'][$table]} SET content='$content[0]'$sql WHERE id='$id'";
						echo $query;
						DB::query($query);
						
					}
				}
			die();
			}	 
		}	
	
	
	
    public function doindex(){//定义自己的方法
        global $_M;//引入全局数组
		$query = "SELECT * FROM {$_M['table']['column']} WHERE (module=1 or module=2 or module=3 or module=4 or module=5) and lang='$_M[lang]'";
		$result = DB::query($query);
		while($list = DB::fetch_array($result)) {
		$clist[]=$list;
		if($list['classtype']==1||$list['releclass']){$clist1now[]=$list;}
		}
		$i=0;
		$listjs = "<script language = 'JavaScript'>\n";
		$listjs.= "var onecount;\n";
		$listjs.= "lev = new Array();\n";
		foreach($clist as $key=>$vallist){
			if($vallist['releclass']){
				$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','0','".$vallist[id]."','".$vallist[access]."');\n";
				$i=$i+1;
			}
			else{
					$listjs.= "lev[".$i."] = new Array('".$vallist[name]."','".$vallist[bigclass]."','".$vallist[id]."','".$vallist[access]."');\n";
					$i=$i+1;
			}
		}
		$listjs.= "onecount=".$i.";\n";
		$listjs.= "</script>";
		//////////////////
		$jiathis_lang1[$lang]="checked='checked'";
		$met_jiathis_ok1[$met_jiathis_ok]="checked='checked'";
		$met_tools_ok1[$met_tools_ok]="checked='checked'";
	 
			   
    require $this->template('own/index');//引入模板文件，必须global $_M
    }
}

?>

