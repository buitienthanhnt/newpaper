<?php

namespace App\ViewBlock;

use Illuminate\Routing\Router;
use Illuminate\Contracts\Support\Htmlable;

class AdminLeftTab implements Htmlable
{
	protected $template = "adminhtml.templates.share.leftTab";
	protected $router;

	public function __construct(Router $router,)
	{
		$this->router = $router;
	}

	protected function actionByController($actions = []): array
	{
		if ($actions) {
			$controllerGroups = [];
			$prefixGroups = [];
			foreach ($actions as $action) {
				if (strpos($action['as'], 'edit') !== false || strpos($action['as'], 'detail') !== false) {
					continue;
				}
				$prefixGroups[$action["prefix"]][] = [
					"Name"     => explode("@", $action["controller"], 2)[1],
					"Number"   => str_replace(["\\", "/", "@"], ["-", "__", "_"], $action["controller"]),
					"Children" => [],
					"as" => $action["as"],
				];
			}

			$rulesTree = [];
			foreach ($prefixGroups as $key => $value) {
				$rulesTree[] = [
					"Name"      => str_replace("/", " ", $key),
					"Number"    => $key,
					"Children"  => $value,
				];
			}
			return [
				"Name" => "root admin",
				"Number" => "rootAdmin",
				"Children" => $rulesTree,
			];
		}
		return [];
	}

	protected function routeListOfGet()
	{
		$allOfRoute = $this->router->getRoutes();
		$actions = collect($allOfRoute->get('GET'))->map(function ($item) {
			try {
				$action =  $item->getAction();
				if (strpos($action["prefix"], "adminhtml") !== false) {
					return $action;
				}
			} catch (\Throwable $th) {
				//throw $th;
			}
		})->filter();
		return ($this->actionByController($actions)['Children']);
	}

	function toHtml(): string
	{
		$formatRoutes = $this->routeListOfGet();
		return view($this->template, ['formatRoutes' => $formatRoutes])->render();
	}
}
