<?php if ( ! defined('BASEPATH')) exit('No se permite el acceso directo al script');

class Tokens{

	Public function deftoken($id){
		$data = array();

		switch ($id) {
			case '1':
				$data['ruta'] = "https://www.pse.pe/api/v1/782529ec6e184f9faf631b905df687ba6dae035978a643d1912dee909929a80e";
				$data['token'] = "eyJhbGciOiJIUzI1NiJ9.Ijk1Y2E1ZDEwY2I3YjQ1ODFhY2FlMGY1NzE5NTkxMmI2OWZiNTM4NGUwOGZmNDVkYmJmYTI0YmY4YjAyYTA5YzMi.SsNS80SG3XRAkCSqgeksrLQVRYgdhG4rPiPiDG6cwUU";
				break;
			default:
				$data['ruta'] = "https://www.pse.pe/api/v1/782529ec6e184f9faf631b905df687ba6dae035978a643d1912dee909929a80e";
				$data['token'] = "eyJhbGciOiJIUzI1NiJ9.Ijk1Y2E1ZDEwY2I3YjQ1ODFhY2FlMGY1NzE5NTkxMmI2OWZiNTM4NGUwOGZmNDVkYmJmYTI0YmY4YjAyYTA5YzMi.SsNS80SG3XRAkCSqgeksrLQVRYgdhG4rPiPiDG6cwUU";
				break;
		}
		return $data;
	}
}
?>