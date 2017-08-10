<?php
/**
 * @author Wender Pinto Machado <wenderpmachado@gmail.com>
 */

namespace Core\Helper\ClassCreator;

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Core\Helper\ClassMaker;

class ClassCreatorController {
    private $classMaker;

    public function __construct(ClassMaker $classMaker) {
		$this->classMaker = $classMaker;
	}

	public function create(Request $request, Response $response, $args) {
		try{
			$array = $this->requestToArray($request);
			if(!$array) {
				return false;	
			} else {
				var_dump($array);
				$this->classMaker->makeModel($array['className'], $array['parameters']);
				$this->classMaker->makeRepository($array['className'], $array['parameters']);
				$this->classMaker->makeController($array['className'], $array['parameters']);
				$this->classMaker->makeRoutes($array['className']);
				return true;
			}
		}catch (Exception $e){
            return false;
		}
	}

	private function requestToArray(Request $request) {
		$params = $request->getParsedBody();
		if(	count($params) == 0 || 
			(count($params) > 0 && 
				(
					!(isset($params['className'])) || ($params['className'] == '') || 
					!(isset($params['parameterName'])) || (count($params['parameterName']) == 0)
				)
			)
		) {
			return false;
		} else {
			$array = [];
			$array['className'] = $params['className'];
			$size = count($params['parameterName']);

			for ($i=0; $i < $size; $i++) {
				if($params['parameterName'][$i] != '') {
					$array['parameters'][$params['parameterName'][$i]] = $params['parameterType'][$i];
				}
			}

			return $array;
		}
	}
}