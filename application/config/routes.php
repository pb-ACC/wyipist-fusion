<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/

$route['default_controller'] = 'login';
$route['start'] = 'login';
$route['pt'] = 'login/change_lang/pt';
$route['en'] = 'login/change_lang/en';
$route['(:any)/pt'] = 'login/change_lang/pt';
$route['(:any)/en'] = 'login/change_lang/en';
$route['home'] = 'homepage/Home';
$route['home/logout'] = 'homepage/Home/logout';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| STOCKS
| -------------------------------------------------------------------------
*/
$route['production/confirm_palettes'] = 'producao/ConfirmacaoPaletes';

/*
| -------------------------------------------------------------------------
| STOCKS
| -------------------------------------------------------------------------
*/
$route['stocks/production'] = 'stocks/producao/SaidaProducao';
$route['stocks/internal_movements/change_location'] = 'stocks/movimentacoes_internas/troca_localizacao/MudaLocalizacao';
$route['stocks/internal_movements/material_reception'] = 'stocks/movimentacoes_internas/rececao_material/RececaoMaterial';
$route['stocks/manage_palettes/cancel_palettes'] = 'stocks/gerir_paletes/AnularPaletes';
$route['stocks/stock_list/all-stock']='stocks/listas/GetStock';
$route['stocks/stock_list/stock-between-dates']='stocks/listas/GetStock_dates';
$route['stocks/generate_new_palette']='stocks/gerir_paletes/GerarPaletes';
$route['stocks/stock_correction/positive']='stocks/corrigir_stock/CorrigirStock/positivo';
$route['stocks/stock_correction/negative']='stocks/corrigir_stock/CorrigirStock/negativo';
$route['stocks/stock_correction/zero']='stocks/corrigir_stock/CorrigirStock/zero';

/*
| -------------------------------------------------------------------------
| PLANOS DE CARGA
| -------------------------------------------------------------------------
*/
$route['load_plans/load_preparation'] = 'planos_carga/preparacao/PreparacaoCarga';
$route['load_plans/load_preparation/(:any)/(:any)'] = 'planos_carga/preparacao/GetLinhasGG/linhasGG/$1/$2';
$route['load_plans/load_preparation/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'planos_carga/preparacao/PreparaRef/stockRef/$1/$2/$3/$4/$5';

$route['load_plans/cancellation_of_load_preparation'] = 'planos_carga/anulacao/AnulacaoCarga';
$route['load_plans/correction_of_load_preparation']='planos_carga/correcao/CorrecaoCarga';

/*
| -------------------------------------------------------------------------
| USERS
| -------------------------------------------------------------------------
*/
$route['users/all-users'] = 'users/GetUsers';
$route['users/add-user'] = 'users/ManageUsers';
$route['users/add-user/submit'] = 'users/ManageUsers/addUsers';