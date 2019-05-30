<?php
/**
 * [OECMS] (C)2012-2099 OEdev,Inc.
 * <E-Mail：phpcoo@qq.com>
 * Url www.oedev.net, www.phpcoo.com
 * Update 2013.02.05
*/
if(!defined('IN_OECMS')) {
	exit('Access Denied');
}

class pluginAModel extends X {

	
	public function activePlugin($plugin) {
		$ret = false;
	    
        if (true === XValid::isPlugin($plugin)){
            
    		parent::loadLib('option');
    		
    		$active_plugins = XOption::get('active_plugins');

			
			if (is_array($active_plugins) && in_array($plugin, $active_plugins)) {
				$ret = true;
			}
			else {
    			$active_plugins[] = $plugin;
    		    $active_plugins = @serialize($active_plugins);
    			XOption::updateOption('active_plugins', $active_plugins);
    		    $ret = true;
				
				$callback_file = BASE_ROOT. "./source/plugin/".$plugin."/".$plugin."_callback.php";
				if (true === $ret && file_exists($callback_file)) {
					require_once $callback_file;
					if (function_exists('callback_init')) {
						callback_init();
					}
				}
			}
		}
        return $ret;
	}

	
	public function inactivePlugin($plugin) {
        
		parent::loadLib('option');
		
		$active_plugins = XOption::get('active_plugins');
		
        if (is_array($active_plugins)) {
    		if (in_array($plugin, $active_plugins)){
    			$key = array_search($plugin, $active_plugins);
    			
    			unset($active_plugins[$key]);
    		} else {
    			return;
    		}
        }
		
		$active_plugins = @serialize($active_plugins);
		XOption::updateOption('active_plugins', $active_plugins);

		
		$callback_file = BASE_ROOT."./source/plugin/".$plugin."/".$plugin."_callback.php";
		if (file_exists($callback_file)) {
			require_once $callback_file;
			if (function_exists('callback_rm')) {
				callback_rm();
			}
		}
		return true;
	}

	
	public function getPlugins() {
		$oePlugins = array();
		$pluginFiles = array();
		$pluginPath = BASE_ROOT . '/source/plugin';
		$pluginDir = @dir($pluginPath);
		if ($pluginDir){
			while(($file = $pluginDir->read()) !== false){
				if (preg_match('|^\.+$|', $file)){
					continue;
				}
				if (is_dir($pluginPath . '/' . $file)){
					$pluginsSubDir = @dir($pluginPath . '/' . $file);
					if ($pluginsSubDir){
						while(($subFile = $pluginsSubDir->read()) !== false){
							if (preg_match('|^\.+$|', $subFile)){
								continue;
							}
							if ($subFile == $file.'.php'){
                                
                                
                                $pluginFiles[] = $file;
							}
						}
					}
				}
			}
		}
		if (!$pluginDir || !$pluginFiles){
			return $oePlugins;
		}
		sort($pluginFiles);
        
		
        parent::loadLib('option');
		$active_plugins = XOption::get('active_plugins');
		foreach($pluginFiles as $pluginFile){
            $pluginData = $this->getPluginData($pluginPath.'/'.$pluginFile.'/'.$pluginFile.'.php');
			if (empty($pluginData['name'])){
				continue;
			}
            else {         
        		
                if (is_array($active_plugins)) {
              		
          		    if (in_array($pluginFile, $active_plugins)) {
          		        $pluginData['active'] = 1;
            		}          
                }
            }
            $pluginData['pluginname'] = $pluginFile;
			$oePlugins[$pluginFile] = $pluginData;

            
		}
		return $oePlugins;
	}

	
	public function getPluginData($pluginFile) {
		$pluginData = implode('', file($pluginFile));
		preg_match("/Plugin Name:(.*)/i", $pluginData, $plugin_name);
		preg_match("/Version:(.*)/i", $pluginData, $version);
		preg_match("/Plugin URL:(.*)/i", $pluginData, $plugin_url);
		preg_match("/Description:(.*)/i", $pluginData, $description);
		preg_match("/For Version:(.*)/i", $pluginData, $for_version);
		preg_match("/Author:(.*)/i", $pluginData, $author_name);
		preg_match("/Author URL:(.*)/i", $pluginData, $author_url);
        preg_match("/Last Update:(.*)/i", $pluginData, $last_update);

		$plugin_name = isset($plugin_name[1]) ? trim($plugin_name[1]) : '';
		$version = isset($version[1]) ? trim($version[1]) : '';
		$description = isset($description[1]) ? trim($description[1]) : '';
		$plugin_url = isset($plugin_url[1]) ? trim($plugin_url[1]) : '';
		$author = isset($author_name[1]) ? trim($author_name[1]) : '';
		$for_version = isset($for_version[1]) ? trim($for_version[1]) : '';
		$author_url = isset($author_url[1]) ? trim($author_url[1]) : '';
        $last_update = isset($last_update[1]) ? trim($last_update[1]) : '';
        
		return array(
        'pluginname' => '', 
		'name' => $plugin_name, 
		'version' => $version,
		'description' => $description,
		'url' => $plugin_url,
		'author' => $author,
		'forversion' => $for_version,
		'authorurl' => $author_url,
        'lastupdate' => $last_update,
        'active' =>0, 
		);
	}

}
?>
