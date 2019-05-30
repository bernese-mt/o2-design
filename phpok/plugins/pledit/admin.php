<?php
/**
 * 批量处理<后台应用>
 * @package phpok\plugins
 * @作者 phpok.com
 * @版本 5.0.135
 * @授权 http://www.phpok.com/lgpl.html PHPOK开源授权协议：GNU Lesser General Public License
 * @时间 2018年12月21日 15时16分
**/
class admin_pledit extends phpok_plugin
{
	public $me;
	public function __construct()
	{
		parent::plugin();
		$this->me = $this->_info();
	}

	public function html_list_action_foot()
	{
		$rs = $this->tpl->val('rs');
		if(!$rs['module']){
			return false;
		}
		$m_rs = $this->model('module')->get_one($rs['module']);
		if(!$m_rs || $m_rs['mtype']){
			return false;
		}
		$fields = array();
		$fields['title'] = $rs['alias_title'] ? $rs['alias_title'] : P_Lang('主题');
		$fields['dateline'] = P_Lang('发布时间');
		$fields['hits'] = P_Lang('查看次数');
		if($rs['is_seo']){
			$fields['seo_title'] = P_Lang('SEO标题');
			$fields['seo_keywords'] = P_Lang('SEO关键字');
			$fields['seo_desc'] = P_Lang('SEO描述');
		}
		if($rs['is_userid']){
			$fields['user_id'] = P_Lang('会员ID');
		}
		if($rs['is_biz']){
			$fields['price'] = P_Lang('价格');
		}
		if($rs['is_tag']){
			$fields['tag'] = P_Lang('标签');
		}
		$mlist = $this->model('module')->fields_all($rs['module']);
		if($mlist){
			foreach($mlist as $key=>$value){
				$fields[$value['identifier']] = $value['title'];
			}
		}
		if(!$fields || count($fields)<1){
			return false;
		}
		$this->assign('fields',$fields);
		$this->_show('admin_pledit_ok5.html');
	}

	public function edit()
	{
		$pid = $this->get('pid','int');
		if(!$pid){
			$this->error('未指定项目ID');
		}
		$ids = $this->get('ids');
		if(!$ids){
			$this->error('未指定要批量修改的ID');
		}
		$rslist = array();
		$idlist = explode(',',$ids);
		foreach($idlist as $key=>$value){
			$value = intval($value);
			if($value){
				$rslist[$value] = array();
			}else{
				unset($idlist[$key]);
			}
		}
		$ids = implode(",",$idlist);
		if(!$rslist || count($rslist)<1){
			$this->error('异常的批量修改主题，请联系开发人员');
		}
		$field = $this->get('field');
		if(!$field){
			$this->error('未指定要批量编辑的字段');
		}
		$project = $this->model('project')->get_one($pid);
		if(!$project){
			$this->error('项目不存在');
		}
		if(!$project['module']){
			$this->error('项目未绑定模块');
		}
		$mlist = $this->model('module')->fields_all($project['module'],'identifier');
		$showtitle = true;
		if($mlist){
			$extlist = array_keys($mlist);
			if(in_array($field,$extlist)){
				$m_rs = $this->model('module')->get_one($project['module']);
				if($m_rs['mtype']){
					$showtitle = false;
					$sql = "SELECT id,".$field." FROM ".$this->db->prefix.$project['module']." WHERE id IN(".$ids.")";
					$vlist = $this->db->get_all($sql);
					if($vlist){
						foreach($vlist as $key=>$value){
							$rslist[$value['id']] = array('content'=>$value[$field]);
						}
					}
				}else{
					$sql = "SELECT id,".$field." FROM ".$this->db->prefix."list_".$project['module']." WHERE id IN(".$ids.")";
					$vlist = $this->db->get_all($sql);
					if($vlist){
						foreach($vlist as $key=>$value){
							$rslist[$value['id']] = array('content'=>$value[$field]);
						}
					}
				}
				//读取字段
				$minfo = $mlist[$field];
				foreach($rslist as $key=>$value){
					$tmpinfo = $minfo;
					$tmpinfo['identifier'] = $minfo['identifier'].'_'.$key;
					$tmpinfo['content'] = $value['content'];
					$this->lib('form')->appid('admin');
					if($tmpinfo['form_type'] == 'editor'){
						$this->lib('form')->appid('www');
					}
					$rslist[$key] = $this->lib('form')->format($tmpinfo);
				}
			}
		}
		if(in_array($field,array('title','hits','seo_title','seo_keywords','seo_desc','dateline','tag'))){
			if($field == 'title'){
				$showtitle = false;
			}
			$sql = "SELECT id,".$field." FROM ".$this->db->prefix."list WHERE id IN(".$ids.")";
			$vlist = $this->db->get_all($sql);
			if($vlist){
				foreach($vlist as $key=>$value){
					$rslist[$value['id']] = array('content'=>$value[$field]);
				}
			}
			$minfo = array('form_type'=>'text');
			if($field == 'dateline'){
				$minfo['format'] = 'time';
				$minfo['form_btn'] = 'datetime';
				$minfo['form_style'] = 'width:200px;';
			}
			foreach($rslist as $key=>$value){
				$tmpinfo = $minfo;
				$tmpinfo['identifier'] = $field.'_'.$key;
				$tmpinfo['content'] = $value['content'];
				
				$rslist[$key] = $this->lib('form')->format($tmpinfo);
			}
		}
		if($field == 'price'){
			$sql = "SELECT id,price FROM ".$this->db->prefix."list_biz WHERE id IN(".$ids.")";
			$vlist = $this->db->get_all($sql);
			if($vlist){
				foreach($vlist as $key=>$value){
					$rslist[$value['id']] = array('content'=>$value[$field]);
				}
			}
			$minfo = array('form_type'=>'text');
			foreach($rslist as $key=>$value){
				$tmpinfo = $minfo;
				$tmpinfo['identifier'] = $field.'_'.$key;
				$tmpinfo['content'] = $value['content'];
				$rslist[$key] = $this->lib('form')->format($tmpinfo);
			}
		}
		if($field == 'user_id'){
			$sql = "SELECT id,user_id FROM ".$this->db->prefix."list WHERE id IN(".$ids.")";
			$vlist = $this->db->get_all($sql);
			if($vlist){
				foreach($vlist as $key=>$value){
					$rslist[$value['id']] = array('content'=>$value[$field]);
				}
			}
			$minfo = array('form_type'=>'user');
			foreach($rslist as $key=>$value){
				$tmpinfo = $minfo;
				$tmpinfo['identifier'] = $field.'_'.$key;
				$tmpinfo['content'] = $value['content'];
				$rslist[$key] = $this->lib('form')->format($tmpinfo);
			}
		}
		$this->assign('rslist',$rslist);
		$this->assign('ids',$ids);
		$this->assign('pid',$pid);
		$this->assign('field',$field);
		if($showtitle){
			$sql = "SELECT id,title FROM ".$this->db->prefix."list WHERE id IN(".$ids.")";
			$tmplist = $this->db->get_all($sql,'id');
			$this->assign('tlist',$tmplist);
		}
		$this->_view('admin_pledit.html');
	}

	public function save()
	{
		$pid = $this->get('pid','int');
		if(!$pid){
			$this->error('未指定项目ID');
		}
		$ids = $this->get('ids');
		if(!$ids){
			$this->error('未指定要批量修改的ID');
		}
		$rslist = array();
		$idlist = explode(',',$ids);
		foreach($idlist as $key=>$value){
			$value = intval($value);
			if($value){
				$rslist[$value] = array();
			}else{
				unset($idlist[$key]);
			}
		}
		$ids = implode(",",$idlist);
		if(!$rslist || count($rslist)<1){
			$this->error('异常的批量修改主题，请联系开发人员');
		}
		$field = $this->get('field');
		if(!$field){
			$this->error('未指定要批量编辑的字段');
		}
		$project = $this->model('project')->get_one($pid);
		if(!$project){
			$this->error('项目不存在');
		}
		if(!$project['module']){
			$this->error('项目未绑定模块');
		}
		$mlist = $this->model('module')->fields_all($project['module'],'identifier');
		if($mlist){
			$extlist = array_keys($mlist);
			if(in_array($field,$extlist)){
				$m_rs = $this->model('module')->get_one($project['module']);
				$minfo = $mlist[$field];
				foreach($rslist as $key=>$value){
					$tmpinfo = $minfo;
					$tmpinfo['identifier'] = $minfo['identifier'].'_'.$key;
					$content = $this->lib('form')->get($tmpinfo);
					//更新内容
					if($m_rs['mtype']){
						$sql = "UPDATE ".$this->db->prefix.$project['module']." SET ".$field."='".$content."' WHERE id='".$key."'";
					}else{
						$sql = "UPDATE ".$this->db->prefix."list_".$project['module']." SET ".$field."='".$content."' WHERE id='".$key."'";
					}
					$this->db->query($sql);
				}
			}
		}
		if(in_array($field,array('title','seo_title','seo_keywords','seo_desc'))){
			foreach($rslist as $key=>$value){
				$content = $this->get($field."_".$key);
				$sql = "UPDATE ".$this->db->prefix."list SET ".$field."='".$content."' WHERE id='".$key."'";
				$this->db->query($sql);
			}
		}
		if($field == 'dateline'){
			foreach($rslist as $key=>$value){
				$content = $this->get($field."_".$key,'time');
				$sql = "UPDATE ".$this->db->prefix."list SET ".$field."='".$content."' WHERE id='".$key."'";
				$this->db->query($sql);
			}
		}
		if($field == 'user_id' || $field == 'hits'){
			foreach($rslist as $key=>$value){
				$content = $this->get($field."_".$key,'int');
				$sql = "UPDATE ".$this->db->prefix."list SET ".$field."='".$content."' WHERE id='".$key."'";
				$this->db->query($sql);
			}
		}
		if($field == 'price'){
			foreach($rslist as $key=>$value){
				$content = $this->get($field."_".$key,'float');
				$sql = "UPDATE ".$this->db->prefix."list_biz SET ".$field."='".$content."' WHERE id='".$key."'";
				$this->db->query($sql);
			}
		}
		if($field == 'tag'){
			foreach($rslist as $key=>$value){
				$content = $this->get($field."_".$key);
				$sql = "UPDATE ".$this->db->prefix."list SET ".$field."='".$content."' WHERE id='".$key."'";
				$this->db->query($sql);
				$this->model('tag')->update_tag($content,$key);
			}
		}
		$this->success();
	}
}