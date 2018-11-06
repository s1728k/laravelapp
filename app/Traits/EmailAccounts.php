<?php

namespace App\Traits;

use App\VirtualDomain;
use App\VirtualUser;
use Illuminate\Http\Request;

trait EmailAccounts
{
	public function emailListView()
	{
		\Log::Info(request()->ip()." visited email list for app id ".$this->app_id);
		$domains = VirtualDomain::where(['app_id' => $this->app_id, 'verified' => 'done'])->get();
		$emails = VirtualUser::where('app_id', $this->app_id)->get();
		foreach ($emails as $key => $email) {
			$emails[$key]->domain = substr($emails[$key]->email, strpos($emails[$key]->email, '@'), 255);
			$emails[$key]->user = str_replace($emails[$key]->domain, '', $emails[$key]->email);
		}
		return view($this->theme.'.email.email_accounts')->with(['domains' => $domains, 'emails' => $emails]);
	}

	public function addNewUser(Request $request)
	{
		$domain = VirtualDomain::where([
			'app_id' => $this->app_id,
			'verified' => 'done',
			'id' => $request->domain_id,
		])->first();
		\Log::Info(request()->ip()." added email user for domain ".$domain->name." for app id ".$this->app_id);
		$email = $request->name.'@'.$domain->name;
		$request->validate([
			'domain_id' => ['required', function($attribute, $value, $fail)use($request, $domain){
				if(empty($domain)){
					$fail("Domain Error!");
				}
			}],
			'name' => ['required', 'string', 'max:255', function($attribute, $value, $fail)use($request, $email){
				$emailcheck = VirtualUser::where([
					'app_id' => $this->app_id, 
					'email' => $email,
				])->first();
				if(!empty($emailcheck)){
					$fail('User name is already taken');
				}
			}],
			'password' => 'required|string|min:6|confirmed'
		]);
		$ssha = $this->ssha($request->password);
		VirtualUser::create([
			'app_id' => $this->app_id,
			'domain_id' => $request->domain_id,
			'email' => $email,
			'password' => $ssha,
		]);
		$this->addEmailUserToPasswdFile($request->domain_id);
		return redirect()->route('c.email.list.view');
		// SELECT a.columname1 AS 1, a.columname1 AS 2 FROM tablename a
	}

	public function deleteEmailAccount(Request $request)
	{
		\Log::Info(request()->ip()." deleted email user for domain ".$record->domain_id." app id ".$this->app_id);
		$record = VirtualUser::findOrFail($request->id);
		$domain_id = $record->domain_id;
		VirtualUser::destroy($request->id);
		$this->addEmailUserToPasswdFile($domain_id);
		return ['status' => 'success'];
	}

	public function addNewDomainView()
	{
		\Log::Info(request()->ip()." visited add new domain page for app id ".$this->app_id);
		return view($this->theme.'.email.add_domain');
	}

	public function addNewDomain(Request $request)
	{
		\Log::Info(request()->ip()." added new domain ".$request->name." for app id ".$this->app_id);
		$request->validate([
			'name' => ['required', 'string', 'max:255', function($attribute, $value, $fail){
				if(substr_count($value,'.')!==1){
					$fail("Invalid domain name");
				}
			}],
		]);
		$record = VirtualDomain::where('name', $request->name)->first();
		if(empty($record)){
			$id = VirtualDomain::create([
				'app_id' => $this->app_id,
				'name' => $request->name,
				'verified' => bcrypt($request->name),
				'expiry_date' => $this->expiry_date($request->name),
			])->id;
		}else{
			$id = $record->id;
		}
		return redirect()->route('c.email.verify.domain.view', ['id' => $id]);
	}

	public function verifyNewDomainView($id)
	{
		$domain = VirtualDomain::findOrFail($id);
		\Log::Info(request()->ip()." visited verify domain page for domain ".$domain." for app id ".$this->app_id);
		return view($this->theme.'.email.add_domain')->with(['domain' => $domain]);
	}

	public function getTxtRecord(Request $request)
	{
		\Log::Info(request()->ip()." attempted to verify TXT record for domain ".$domain." for app id ".$this->app_id);
		$record = VirtualDomain::findOrFail($request->id);
		$result = dns_get_record($record->name, DNS_TXT);
		if($record->verified == $result[0]['txt']){
			\Log::Info(request()->ip()." Verified TXT record for domain ".$domain." for app id ".$this->app_id);
			$record->update(['verified' => 'done', 'expiry_date' => $this->expiry_date($record->name)]);
			$this->addMailAuthFolder($record->name);
			return ['status' => 'success'];
		}
		return ['status' => $result[0]['txt']];
	}

	public function getPageContents(Request $request)
	{
		\Log::Info(request()->ip()." attempted to verify page record for domain ".$domain." for app id ".$this->app_id);
		$record = VirtualDomain::findOrFail($request->id);
		$data = $this->curl_get_contents('http://'.$record->name.'/honeyweb-domain-verification');
		if($record->verified == $data){
			\Log::Info(request()->ip()." Verified page record for domain ".$domain." for app id ".$this->app_id);
			$record->update(['verified' => 'done', 'expiry_date' => $this->expiry_date($record->name)]);
			$this->addMailAuthFolder($record->name);
			return ['status' => 'success'];
		}
		return ['status' => $data];
	}

	private function curl_get_contents($url)
	{
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
	    $output = curl_exec($ch);
	    curl_close($ch); 
	    return $output;
	}

	private function expiry_date($domain){
		$current = \Carbon\Carbon::now();
		return $current->addDays(365);
	}

	private function ssha($passwordplain)
	{
		$salt = substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',4)),0,4); 
		$encrypted_password = '{SSHA}' . base64_encode(sha1( $passwordplain.$salt, TRUE ). $salt); 
		return $encrypted_password;
		// base64_encode(pack('H*',sha1($passwordplain))); 
	}

	public function addEmailUserToPasswdFile($domain_id)
	{
		\Log::Info(request()->ip()." added mail user to passwd file for domain ".$domain_id." for app id ".$this->app_id);
		$virtual_users = VirtualUser::where([
			'app_id' => $this->app_id, 
			'domain_id' => $domain_id,
		])->get();
		$cont = "";
		foreach ($virtual_users as $virtual_user) {
			$arr = explode('@',$virtual_user->email);
			$cont = $cont . $virtual_user->email.':'.$virtual_user->password.':5000:5000::/var/vmail/'.$arr[1].'/'.$arr[0];
			$cont = $cont . '::userdb_quota_rule=\*:storage=5G userdb_acl_groups=PublicMailboxAdmins'."\n";
		}
		$myfile = fopen(base_path() ."/auth.d/".$arr[1].'/'.'passwd', "w");
        fwrite($myfile, $cont);
        fclose($myfile);
	}

	public function addMailAuthFolder($domain)
	{
		\Log::Info(request()->ip()." added mail auth folder for domain ".$domain." for app id ".$this->app_id);
		if (!file_exists(base_path() ."/auth.d/".$domain)) {
		    mkdir(base_path() ."/auth.d/".$domain, 0777, true);
		}
	}
}