<?php
// version: 1
namespace za\zUsr;

// ザガタ。六 /////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////

class zUsr {
	/* Zagata.Authorization */
	private $za = null;
	public $n = '';

	/////////////////////////////// 
	// funcs
	public function req() {
		$vrs = $this->za->mm('vrs');
		
		$re = false; $rdr = false;
		if($vrs && isset($vrs[0]['logout'])) { 
			$this->ext();
			$this->za->hdr('Location: '.$vrs[0]['base'].(($vrs[0]['lng'])?$vrs[0]['lng']:'').'/'.$vrs[0]['url'],array('Status: 303 See Other'));
			exit();
		} else {
			$xl = false; $xp = false;
			if($vrs && isset($vrs[0]['login']) && isset($vrs[0]['xl']) && isset($vrs[0]['xp'])) {
				$xl = $vrs[0]['xl']; $xp = md5($vrs[0]['xp']); $rdr = true;
				$this->za->msg('dbg','zUsr', 'rdr '.((string) $rdr));
			} elseif($vrs && isset($vrs[0]['xl']) && isset($vrs[0]['xp'])) {
				$xl = $vrs[0]['xl']; $xp = $vrs[0]['xp'];
			} else {}
			
			if($xl && $xp) {
				$re = $this->md5($xl,$xp);
				if($re) {
					$this->sav($xl,$xp);
					if($rdr) {
						$this->za->hdr('Location: '.$vrs[0]['base'].(($vrs[0]['lng'])?$vrs[0]['lng']:'').'/'.$vrs[0]['url'],array('Status: 303 See Other'));
						exit();
					} else { }
				} else {
					$this->za->msg('err','zUsr','login error');
				}
			} else {}
		
			if($re && isset($re['adm'])) {
				$this->za->lo('zAdm',false,'adm');
			} else {} 
		}

		$abc = $this->za->mm(array('vrs','abc'));
		if($abc && in_array($abc[0],array('admin','wp-admin','redaktors','manage'))) {
			if($re) { $re = array_merge($re,array('ca'=>'usr','pl'=>'cnt')); }
			else { $re = array('ca'=>'usr','pl'=>'cnt'); }
			$this->za->mm(false,$re);
		} elseif($re) {
			$re = array_merge($re,array('ca'=>'usr'));
			$this->za->mm(false,$re);
		} else { }

		$this->za->ee('vis',array($this,'idp'));
	}

	public function idp() {
		$tmp = $this->za->mm('usr');
		if($tmp) {
			$tmp[0]['idp'] = $this->za->mm(array('vrs','cmn'));
			$this->za->mm('usr',$tmp[0]);
		} else {}
	}
	
	private function md5($xl,$xp) {
		$tmp = md5($xl.$xp);
		$vrs = $this->za->mm(array('vrs','usrmd5'));
		if($vrs&&isset($vrs[$tmp])) {
			return array_merge(array('xl'=>$xl),$vrs[$tmp]);
		} else {
			return false;
		}
	}

	private function sav($xl,$xp) {
		$_SESSION['xl'] = $xl; $_SESSION['xp'] = $xp; $_COOKIE['xl'] = $xl; $_COOKIE['xp'] = $xp;
		setcookie('xl', $xl, time()+7*24*3600, '/'); setcookie('xp', $xp, time()+7*24*3600, '/');
	}

	private function ext() {
		$this->za->msg('dbg','zUsr','logout');

		$_SESSION['xl'] = null; $_SESSION['xp'] = null; $_COOKIE['xl'] = null; $_COOKIE['xp'] = null;
		unset($_SESSION['xl'],$_SESSION['xp'],$_COOKIE['xl'],$_COOKIE['xp']);
		setcookie('xl', '', time()-3600, '/');  setcookie('xp', '', time()-3600, '/');
		session_unset();
	}

	/////////////////////////////// 
	public function tmplts() {
		$tmp = $this->za->mm('vis');
		if($tmp) {
			$tmp[0]['tmplts'][] = './zUsr/usr.xsl';
			$this->za->mm('vis',$tmp[0]);
		} else {}
	}
	
	/////////////////////////////// 
	// ini
	function __construct($za,$a=false,$n=false) {
		$this->za = $za;
		$this->n = (($n)?$n:'zUsr');
		// $this->za->msg('dbg','zUsr','i am '.$this->n.'(zUsr)');
		
		$tmp = $za->mm(array('vrs','sys'));
		$tmp['url']['login'] = 0; $tmp['url']['logout'] = 0;
		$za->mm(array('vrs','sys'),$tmp);

		$za->ee('req',array($this,'req')); // have to add zAdm
		$za->ee('vis',array($this,'tmplts'));
	}
}

////////////////////////////////////////////////////////////////

if(class_exists('\zlo')) {
	\zlo::da('zUsr');
} elseif(realpath(__FILE__) == realpath($_SERVER['DOCUMENT_ROOT'].$_SERVER['SCRIPT_NAME'])) {
	header("content-type: text/plain;charset=utf-8");
	exit('zUsr');
} else {}

?>